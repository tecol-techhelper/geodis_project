<?php

namespace Tests\Feature;

use App\Jobs\DistributeSupportFilesJob;
use App\Livewire\Forms\Services\UploadFileForm;
use App\Models\Service;
use App\Models\SupportFile;
use App\Models\SupportFileCopy;
use App\Models\User;
use App\Services\SharePointUploader;
use App\Services\SupportDocumentNaming;
use App\Services\SupportFileDistributor;
use App\Services\SupportFileSftpUploader;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Volt\Volt;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class SupportDocumentDistributionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        foreach ([
            'services' => ['consecutive'],
            'users' => ['username'],
            'file_types' => ['file_type', 'file_type_full_name'],
            'purchase_orders' => ['service_id', 'purchase_order_number'],
            'reference_types' => ['reference_type_code'],
            'order_references' => ['purchase_order_id', 'reference_type_id', 'order_reference_value'],
        ] as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns) {
                $table->id();
                foreach ($columns as $column) {
                    str_ends_with($column, '_id') ? $table->unsignedBigInteger($column)->nullable() : $table->string($column)->nullable();
                }
                $table->timestamps();
                $table->softDeletes();
            });
        }
        foreach (['2025_05_30_093303_create_support_files_table.php', '2026_05_16_120000_add_purchase_order_and_reference_to_support_files_table.php', '2026_10_17_120000_add_document_copies_to_support_files.php', '2026_10_22_120000_group_support_copies_by_identifier_and_cni.php', '2026_10_23_160000_add_processing_state_to_support_files.php'] as $migration) {
            (require database_path('migrations/'.$migration))->up();
        }
        DB::table('file_types')->insert(['id' => 1, 'file_type' => 'CLP', 'file_type_full_name' => 'CHECK LIST']);
        foreach (array_values(array_diff(SupportDocumentNaming::TYPES, ['CLP'])) as $index => $code) {
            DB::table('file_types')->insert(['id' => $index + 2, 'file_type' => $code, 'file_type_full_name' => $code]);
        }
        DB::table('users')->insert(['id' => 1, 'username' => 'administrador']);
        $this->actingAs(User::find(1)->setRelation('roles', collect()));
        Queue::fake([DistributeSupportFilesJob::class]);
        Storage::fake('local');
        Storage::fake('sftp_geodis');
    }

    private int $processedQueueJobs = 0;

    private function runQueuedSupportUploads(?SupportFileDistributor $distributor = null, bool $expectFailure = false): void
    {
        while ($this->processedQueueJobs < Queue::pushed(DistributeSupportFilesJob::class)->count()) {
            $job = Queue::pushed(DistributeSupportFilesJob::class)[$this->processedQueueJobs++];
            try {
                $job->handle($distributor ?? app(SupportFileDistributor::class));
            } catch (RuntimeException $exception) {
                if (! $expectFailure) {
                    throw $exception;
                }
                $job->failed($exception);

                return;
            }
        }
    }

    private function service(string $type = 'DOM-CONSOL', array $references = ['5213111/1', '5213111/2', 'FN226786P125/1']): Service
    {
        $service = Service::create(['consecutive' => 'N3708865']);
        $acd = DB::table('reference_types')->insertGetId(['reference_type_code' => 'ACD']);
        $coi = DB::table('reference_types')->insertGetId(['reference_type_code' => 'COI']);
        $orders = [];
        foreach ($references as $index => $reference) {
            $isLog = in_array($type, ['DOM-CONSOL', 'POST-CARRIAGE', 'EMPTY-CONTAINER'], true);
            $identifier = explode('/', (string) $reference)[0];
            if ($isLog && isset($orders[$identifier])) {
                DB::table('order_references')->insert(['purchase_order_id' => $orders[$identifier], 'reference_type_id' => $coi, 'order_reference_value' => $reference]);

                continue;
            }
            $po = DB::table('purchase_orders')->insertGetId(['service_id' => $service->id, 'purchase_order_number' => ($isLog ? 'SHO00' : 'GR').($index + 1)]);
            DB::table('order_references')->insert(['purchase_order_id' => $po, 'reference_type_id' => $acd, 'order_reference_value' => $type]);
            $orders[$identifier] = $po;
            if ($reference !== null) {
                DB::table('order_references')->insert(['purchase_order_id' => $po, 'reference_type_id' => $coi, 'order_reference_value' => $reference]);
            }
        }

        return $service;
    }

    private function stage(Service $service, ?SupportFileDistributor $distributor = null): SupportFile
    {
        Storage::disk('local')->put('temp_support_files/original.pdf', 'test support');

        return ($distributor ?? app(SupportFileDistributor::class))->stage($service, [
            'temp_path' => 'temp_support_files/original.pdf', 'file_type' => 'CLP',
            'fileName' => 'original.pdf', 'extension' => 'pdf', 'observations' => '',
        ]);
    }

    private function form(Service $service): UploadFileForm
    {
        $component = new class extends Component
        {
            public UploadFileForm $form;
        };
        $form = new UploadFileForm($component, 'form');
        $component->form = $form;
        $form->service_id = $service->id;
        $form->file_type = 'CLP';

        return $form;
    }

    public static function documentaryFlows(): array
    {
        $cases = [];
        foreach (['DOM-CONSOL', 'POST-CARRIAGE', 'EMPTY-CONTAINER', 'DELIVERY-SO', 'ROAD'] as $flow) {
            foreach (SupportDocumentNaming::TYPES as $document) {
                $cases[$flow.' '.$document] = [$flow, $document];
            }
        }

        return $cases;
    }

    #[DataProvider('otxFlows')]
    public function test_large_upload_uses_small_jobs_and_preserves_original_until_all_81_copies_finish(string $flow): void
    {
        $service = $this->service($flow, array_fill(0, 81, null));
        $this->assertLargeQueuedUpload($service);
    }

    public function test_log_81_oc_sho_pairs_are_processed_in_27_batches(): void
    {
        $service = $this->service();
        DB::table('order_references')->delete();
        DB::table('purchase_orders')->delete();
        $acd = DB::table('reference_types')->where('reference_type_code', 'ACD')->value('id');
        $coi = DB::table('reference_types')->where('reference_type_code', 'COI')->value('id');
        for ($sho = 1; $sho <= 9; $sho++) {
            $order = DB::table('purchase_orders')->insertGetId(['service_id' => $service->id, 'purchase_order_number' => 'SHO'.$sho]);
            DB::table('order_references')->insert(['purchase_order_id' => $order, 'reference_type_id' => $acd, 'order_reference_value' => 'DOM-CONSOL']);
            for ($oc = 1; $oc <= 9; $oc++) {
                foreach ([1, 2] as $line) {
                    DB::table('order_references')->insert(['purchase_order_id' => $order, 'reference_type_id' => $coi, 'order_reference_value' => 'OC'.$oc.'/'.$line]);
                }
            }
        }
        $this->assertLargeQueuedUpload($service);
    }

    private function assertLargeQueuedUpload(Service $service): void
    {
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/original.pdf']);
        $sent = [];
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->times(81)->andReturnUsing(function ($directory, $name) use (&$sent) {
            $this->assertArrayNotHasKey($name, $sent);
            $sent[$name] = true;

            return [true, null];
        });
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $support = $this->stage($service, $distributor);
        $source = $support->local_path;
        $this->assertSame(81, $support->copies()->count());
        $distributor->enqueue($support);
        $this->assertSame('En espera', $support->fresh()->copyProgress());
        $this->assertCount(0, $sent);
        $first = Queue::pushed(DistributeSupportFilesJob::class)[0];
        $this->processedQueueJobs++;
        $first->handle($distributor);
        $this->assertSame([3, 81], $support->fresh()->copyCounts());
        Storage::disk('local')->assertExists($source);
        $this->assertSame(2, Queue::pushed(DistributeSupportFilesJob::class)->count());
        $this->runQueuedSupportUploads($distributor);
        $this->assertSame(27, Queue::pushed(DistributeSupportFilesJob::class)->count());
        $this->assertSame([81, 81], $support->fresh()->copyCounts());
        $this->assertSame('completed', $support->fresh()->processing_status);
        $this->assertSame(1, SupportFile::count());
        Storage::disk('local')->assertMissing($source);
    }

    public function test_repeated_retry_does_not_enqueue_duplicates_and_stale_requests_can_resume(): void
    {
        $distributor = app(SupportFileDistributor::class);
        $support = $this->stage($this->service());
        $distributor->enqueue($support);
        $oldToken = $support->fresh()->processing_token;
        $distributor->enqueue($support);
        Queue::assertPushed(DistributeSupportFilesJob::class, 1);
        $support->update(['processing_heartbeat_at' => now()->subMinutes(11)]);
        $distributor->enqueue($support);
        Queue::assertPushed(DistributeSupportFilesJob::class, 2);
        $this->assertNotSame($oldToken, $support->fresh()->processing_token);
        $inactive = Mockery::mock(SupportFileDistributor::class);
        $inactive->shouldNotReceive('distribute');
        (new DistributeSupportFilesJob($support->id, $oldToken))->handle($inactive);
        (new DistributeSupportFilesJob($support->id, $oldToken))->failed(new RuntimeException('old failure'));
        $this->assertSame('queued', $support->fresh()->processing_status);
    }

    public function test_large_partial_failure_resumes_without_resending_confirmed_copies(): void
    {
        $service = $this->service('DELIVERY-SO', array_fill(0, 81, null));
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/original.pdf']);
        $sent = [];
        $failed = false;
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->times(82)->andReturnUsing(function ($directory, $name) use (&$sent, &$failed) {
            if (str_starts_with($name, 'GR4-') && ! $failed) {
                $failed = true;

                return [false, 'offline'];
            }
            $this->assertArrayNotHasKey($name, $sent);
            $sent[$name] = true;

            return [true, null];
        });
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $support = $this->stage($service, $distributor);
        $source = $support->local_path;
        $distributor->enqueue($support);
        $this->runQueuedSupportUploads($distributor, expectFailure: true);
        $this->assertSame([5, 81], $support->fresh()->copyCounts());
        $this->assertSame('failed', $support->fresh()->processing_status);
        Storage::disk('local')->assertExists($source);
        $this->form($service)->retrySupportFile($support->id, $service, $distributor);
        $this->runQueuedSupportUploads($distributor);
        $this->assertSame([81, 81], $support->fresh()->copyCounts());
        $this->assertCount(81, $sent);
        Storage::disk('local')->assertMissing($source);
    }

    public function test_processing_state_migration_can_resume_and_preserves_old_supports(): void
    {
        $support = $this->stage($this->service());
        $migration = require database_path('migrations/2026_10_23_160000_add_processing_state_to_support_files.php');
        $migration->down();
        Schema::table('support_files', fn (Blueprint $table) => $table->string('processing_status', 20)->nullable());
        $migration->up();
        $migration->up();
        $this->assertSame('original.pdf', $support->fresh()->original_name);
        $this->assertNull($support->fresh()->processing_status);
        $this->assertTrue(Schema::hasColumn('support_files', 'processing_token'));
        $this->assertTrue(Schema::hasColumn('support_files', 'processing_heartbeat_at'));
    }

    public function test_job_waits_for_a_busy_support_lock_instead_of_failing_the_modal_retry(): void
    {
        $support = $this->stage($this->service());
        $distributor = app(SupportFileDistributor::class);
        $lock = \Illuminate\Support\Facades\Cache::lock('support-file:'.$support->id, 600);
        $this->assertTrue($lock->get());
        try {
            $this->form($support->service)->retrySupportFile($support->id, $support->service, $distributor);
            $job = Queue::pushed(DistributeSupportFilesJob::class)[0];
            $job->withFakeQueueInteractions()->handle($distributor);
            $job->assertReleased(30);
            $this->assertSame('queued', $support->fresh()->processing_status);
        } finally {
            $lock->release();
        }
    }

    public function test_deleted_support_is_ignored_by_queued_jobs(): void
    {
        $support = $this->stage($this->service());
        app(SupportFileDistributor::class)->enqueue($support);
        $job = Queue::pushed(DistributeSupportFilesJob::class)[0];
        $support->delete();
        $distributor = Mockery::mock(SupportFileDistributor::class);
        $distributor->shouldNotReceive('distribute');
        $job->handle($distributor);
    }

    public function test_modal_reports_counts_and_notifies_completion_only_once(): void
    {
        $service = $this->service();
        $support = $this->stage($service);
        app(SupportFileDistributor::class)->enqueue($support);
        $component = Volt::test('services.upload-file-modal', ['service' => $service, 'canUploadSupports' => true])
            ->assertSee('En espera')->assertSee('0 de 2 copias enviadas')
            ->assertSeeHtml('wire:poll.5s.visible="refreshSupportProgress"')
            ->assertDontSee('Reintentar guardado');
        $support->copies()->update(['sharepoint_uploaded_at' => now(), 'sftp_uploaded_at' => now()]);
        $support->update(['processing_status' => 'completed']);
        $component->call('refreshSupportProgress')->assertDispatched('support-files-saved')
            ->assertSee('Guardado')->assertSee('2 de 2 copias enviadas');
        $component->call('refreshSupportProgress')->assertNotDispatched('support-files-saved');
        $support->copies()->update(['sftp_uploaded_at' => null]);
        $support->update(['processing_status' => 'failed']);
        $component->call('refreshSupportProgress')->assertSee('Requiere reintento')->assertSee('Reintentar guardado');
    }

    public function test_actual_database_queue_runs_the_job_with_no_signed_in_user(): void
    {
        (require database_path('migrations/0001_01_01_000002_create_jobs_table.php'))->up();
        Queue::swap(Queue::getFacadeRoot()->queue);
        $service = $this->service(references: ['1/1', '2/1', '3/1', '4/1']);
        $support = $this->stage($service);
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/report.pdf']);
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->times(4)->andReturn([true, null]);
        $this->app->instance(SupportFileDistributor::class, new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp));
        app(SupportFileDistributor::class)->enqueue($support);
        $this->assertSame('support-files', DB::table('jobs')->value('queue'));
        $this->app['auth']->logout();
        $job = Queue::connection('support_uploads')->pop('support-files');
        $job->fire();
        $this->assertSame([3, 4], $support->fresh()->copyCounts());
        $this->assertSame(1, DB::table('jobs')->count());
        $this->travel(2)->seconds();
        Queue::connection('support_uploads')->pop('support-files')->fire();
        $this->assertSame('completed', $support->fresh()->processing_status);
        $this->assertSame(0, DB::table('jobs')->count());
    }

    public function test_actual_worker_marks_failed_after_three_exceptions_and_preserves_source(): void
    {
        (require database_path('migrations/0001_01_01_000002_create_jobs_table.php'))->up();
        config(['queue.failed.database' => 'sqlite']);
        Queue::swap(Queue::getFacadeRoot()->queue);
        $support = $this->stage($this->service());
        $source = $support->local_path;
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->times(3)->andThrow(new RuntimeException('offline'));
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldNotReceive('upload');
        $this->app->instance(SupportFileDistributor::class, new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp));
        app(SupportFileDistributor::class)->enqueue($support);
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->artisan('queue:work', ['connection' => 'support_uploads', '--queue' => 'support-files', '--once' => true, '--sleep' => 0])->assertExitCode(0);
            $this->travel(61)->seconds();
        }
        $this->assertSame('failed', $support->fresh()->processing_status);
        $this->assertSame(1, DB::table('failed_jobs')->count());
        $this->assertSame(0, DB::table('jobs')->count());
        Storage::disk('local')->assertExists($source);
    }

    public function test_timeout_and_reservation_allow_the_support_lock_to_expire_before_redelivery(): void
    {
        $job = new DistributeSupportFilesJob(1, 'test-token');
        $this->assertLessThan(SupportFileDistributor::LOCK_SECONDS, $job->timeout);
        $this->assertLessThan(config('queue.connections.support_uploads.retry_after'), SupportFileDistributor::LOCK_SECONDS);
        $this->assertSame('support_uploads', $job->connection);
        $this->assertSame('support-files', $job->queue);
        $this->assertTrue($job->afterCommit);
    }

    public function test_queue_dispatch_failure_preserves_original_and_allows_retry(): void
    {
        // Force an actual database queue failure after committing the processing state.
        Queue::swap(Queue::getFacadeRoot()->queue);
        $support = $this->stage($this->service());
        try {
            app(SupportFileDistributor::class)->enqueue($support);
            $this->fail('The jobs table is intentionally absent.');
        } catch (\Illuminate\Database\QueryException) {
            $this->assertFalse($support->fresh()->distributionIsActive());
            $this->assertSame('failed', $support->fresh()->processing_status);
            Storage::disk('local')->assertExists($support->local_path);
        }
    }

    public static function recoveryFlows(): array
    {
        return [
            'LOG SharePoint failure' => ['DOM-CONSOL', true],
            'LOG partial SFTP failure' => ['DOM-CONSOL', false],
            'TEBS SharePoint failure' => ['DELIVERY-SO', true],
            'TEBS partial SFTP failure' => ['DELIVERY-SO', false],
        ];
    }

    public static function azureRecoveryFlows(): array
    {
        $cases = [];
        foreach (['DOM-CONSOL', 'DELIVERY-SO'] as $flow) {
            foreach ([401, 503, 200] as $status) {
                $cases[$flow.' HTTP '.$status] = [$flow, $status];
            }
        }

        return $cases;
    }

    #[DataProvider('azureRecoveryFlows')]
    public function test_azure_authentication_failure_preserves_original_until_successful_retry(string $flow, int $status): void
    {
        config(['services.msgraph.tenant_id' => 'test-tenant', 'services.msgraph.site_id' => 'test-site']);
        Http::preventStrayRequests();
        $tokenRequests = 0;
        $uploadedBytes = [];
        Http::fake(function ($request) use ($status, &$tokenRequests, &$uploadedBytes) {
            if (str_contains($request->url(), 'login.microsoftonline.com/')) {
                $tokenRequests++;

                return $tokenRequests === 1
                    ? Http::response(['error_description' => 'sensitive response'], $status)
                    : Http::response(['access_token' => 'recovered-token']);
            }
            $this->assertTrue($request->hasHeader('Authorization', 'Bearer recovered-token'));
            if ($request->method() === 'PUT') {
                $uploadedBytes[] = $request->body();

                return Http::response(['webUrl' => 'https://example.sharepoint.com/report.pdf'], 201);
            }

            return Http::response(['folder' => (object) []]);
        });
        $service = $this->service($flow);
        $form = $this->form($service);
        $bytes = "%PDF-1.4\nRecoverable original\n%%EOF";
        $form->files = [UploadedFile::fake()->createWithContent('report.pdf', $bytes)];
        $form->uploadFiles();
        $this->assertTrue($form->saveFiles(app(SupportFileDistributor::class)));
        $this->runQueuedSupportUploads(expectFailure: true);
        $support = SupportFile::firstOrFail();
        $source = $support->local_path;
        $this->assertSame($bytes, Storage::disk('local')->get($source));
        $this->assertSame('Requiere reintento', $support->copyProgress());
        $this->assertCount(0, $form->tempFiles);
        $this->assertSame(0, $support->copies()->whereNotNull('sftp_uploaded_at')->count());
        $this->assertSame([], Storage::disk('sftp_geodis')->allFiles());
        $this->assertSame([], $uploadedBytes);
        $this->assertCount($flow === 'DOM-CONSOL' ? 2 : 3, $support->copies);
        $this->assertStringNotContainsString('sensitive response', implode(' ', $support->copies->pluck('error_message')->all()));
        $this->form($service)->retrySupportFile($support->id, $service, app(SupportFileDistributor::class));
        $this->runQueuedSupportUploads();
        $this->assertSame('Guardado', $support->fresh()->copyProgress());
        $this->assertSame([$bytes], $uploadedBytes);
        $this->assertSame(2, $tokenRequests);
        $this->assertTrue($support->copies()->get()->every(fn ($copy) => $copy->isComplete()));
        foreach ($support->copies()->get() as $copy) {
            $this->assertSame($bytes, Storage::disk('sftp_geodis')->get('DOCS/'.$copy->file_name));
        }
        Storage::disk('local')->assertMissing($source);
    }

    #[DataProvider('recoveryFlows')]
    public function test_original_can_be_recovered_after_remote_failures_in_both_flows(string $flow, bool $sharepointFails): void
    {
        $service = $this->service($flow);
        $form = $this->form($service);
        $form->files = [UploadedFile::fake()->createWithContent('report.pdf', "%PDF-1.4\nsupport\n%%EOF")];
        $form->uploadFiles();
        $sharepoint = Mockery::mock(SharePointUploader::class);
        if ($sharepointFails) {
            $sharepoint->shouldReceive('upload')->once()->andThrow(new RuntimeException('SharePoint unavailable'));
        } else {
            $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/report.pdf']);
        }
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $attempted = [];
        $sftp->shouldReceive('upload')->andReturnUsing(function ($directory, $name, $remotePath, $source) use (&$attempted) {
            $attempted[] = $name;
            if (count($attempted) === 1) {
                $this->assertSame('DOCS/'.$name, $remotePath);

                return [true, null];
            }

            return [false, 'SFTP unavailable'];
        });
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $this->assertTrue($form->saveFiles($distributor));
        $this->runQueuedSupportUploads($distributor, expectFailure: true);
        $this->assertCount(0, $form->tempFiles);
        $this->assertSame(1, SupportFile::count());
        $support = SupportFile::firstOrFail();
        $source = $support->local_path;
        $sourceBytes = Storage::disk('local')->get($source);
        $copies = $support->copies()->get();
        $this->assertCount($flow === 'DOM-CONSOL' ? 2 : 3, $copies);
        $this->assertCount($sharepointFails ? 0 : count($copies), $attempted);
        $completed = $copies->filter(fn ($copy) => $copy->isComplete())->pluck('file_name')->all();
        $pending = $copies->reject(fn ($copy) => $copy->isComplete())->pluck('file_name')->all();
        if ($sharepointFails) {
            $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/report.pdf']);
        }
        $recoveredSftp = Mockery::mock(SupportFileSftpUploader::class);
        foreach ($completed as $name) {
            $recoveredSftp->shouldNotReceive('upload')->with('DOCS', $name, Mockery::any(), Mockery::any(), Mockery::any());
        }
        foreach ($pending as $name) {
            $recoveredSftp->shouldReceive('upload')->once()
                ->with('DOCS', $name, 'DOCS/'.$name, Mockery::type('string'), $service->id)
                ->andReturnUsing(function ($directory, $fileName, $remotePath, $absoluteSource) use ($sourceBytes) {
                    $this->assertSame($sourceBytes, file_get_contents($absoluteSource));

                    return [true, null];
                });
        }
        $retryDistributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $recoveredSftp);
        $this->form($service)->retrySupportFile($support->id, $service, $retryDistributor);
        $this->runQueuedSupportUploads($retryDistributor);
        $this->assertSame('Guardado', $support->fresh()->copyProgress());
        Storage::disk('local')->assertMissing($source);
        $this->assertSame(1, $support->copies()->get()->pluck('file_url')->unique()->count());
    }

    #[DataProvider('documentaryFlows')]
    public function test_full_documentary_flow_writes_exact_bytes_and_deletes_only_sharepoint(string $flow, string $document): void
    {
        $service = $this->service($flow);
        config([
            'services.msgraph.tenant_id' => 'test-tenant',
            'services.msgraph.client_id' => 'test-client',
            'services.msgraph.client_secret' => 'test-secret',
            'services.msgraph.site_id' => 'test-site',
            'services.msgraph.folder_path' => 'Archivo documental',
        ]);
        $library = 'https://example.sharepoint.com/sites/Test/Shared%20Documents';
        $cloudFiles = [];
        Http::preventStrayRequests();
        Http::fake(function ($request) use ($library, &$cloudFiles) {
            if (str_contains($request->url(), 'login.microsoftonline.com/test-tenant/')) {
                return Http::response(['access_token' => 'test-token']);
            }
            $this->assertTrue($request->hasHeader('Authorization', 'Bearer test-token'));
            if ($request->method() === 'PUT' && str_ends_with($request->url(), ':/content')) {
                $path = explode(':/content', explode('/root:/', $request->url(), 2)[1], 2)[0];
                $url = $library.'/'.$path;
                $cloudFiles[$url] = $request->body();

                return Http::response(['id' => 'document-id', 'webUrl' => $url], 201);
            }
            if ($request->method() === 'POST' && str_ends_with($request->url(), '/children')) {
                return Http::response(['folder' => (object) [], 'name' => $request['name']], 201);
            }
            if ($request->method() === 'GET' && str_contains($request->url(), '/root?')) {
                return Http::response(['webUrl' => $library, 'parentReference' => ['driveId' => 'test-drive']]);
            }
            if ($request->method() === 'GET' && str_contains($request->url(), '.pdf')) {
                return Http::response(['id' => 'document-id', 'file' => (object) []]);
            }
            if ($request->method() === 'DELETE' && str_ends_with($request->url(), '/items/document-id')) {
                $cloudFiles = [];

                return Http::response([], 204);
            }

            return Http::response([], 404);
        });

        $bytes = "%PDF-1.4\n".$flow.' '.$document."\nDocument content\n%%EOF";
        $component = Volt::test('services.upload-file-modal', ['service' => $service, 'canUploadSupports' => true])
            ->set('form.file_type', $document)->set('form.free_text', 'Entrega / 1')
            ->set('form.files', [UploadedFile::fake()->createWithContent('report.pdf', $bytes)])
            ->call('uploadFiles')->assertHasNoErrors();
        $this->assertCount(1, $component->get('form.tempFiles'));
        $component->call('saveFiles')->assertHasNoErrors()->assertDispatched('support-files-queued');
        $this->runQueuedSupportUploads();
        $this->assertCount(0, $component->get('form.tempFiles'));
        $this->assertSame(1, SupportFile::count());
        $support = SupportFile::firstOrFail();
        $isLog = ! in_array($flow, ['DELIVERY-SO', 'ROAD'], true);
        $expected = $isLog ? [
            '5213111-N-'.$document.'-SHO001-Entrega1.pdf',
            'FN226786P125-N-'.$document.'-SHO003-Entrega1.pdf',
        ] : array_map(fn ($id) => $id.'-N-'.$document.'-N3708865-Entrega1.pdf', ['GR1', 'GR2', 'GR3']);
        $copies = $support->copies()->get();
        $this->assertSame($expected, $copies->pluck('file_name')->all());
        $this->assertTrue($copies->every(fn ($copy) => $copy->isComplete()));
        $this->assertTrue((bool) $support->uploaded_sftp);
        $this->assertNull($support->local_path);
        $this->assertSame([], Storage::disk('local')->allFiles('support_sources'));
        $this->assertSame([], Storage::disk('local')->allFiles('temp_support_files'));
        $this->assertSame([$support->file_url => $bytes], $cloudFiles);
        $this->assertStringContainsString('/Archivo%20documental/DOCS/'.now()->format('Y/m').'/N3708865/SUP-', $support->file_url);
        $this->assertCount(count($expected), Storage::disk('sftp_geodis')->allFiles());
        foreach ($expected as $name) {
            $this->assertLessThanOrEqual(50, strlen($name));
            $this->assertSame($bytes, Storage::disk('sftp_geodis')->get('DOCS/'.$name));
        }
        $this->assertCount(1, Http::recorded(fn ($request) => $request->method() === 'PUT'));
        // Retrying a completed group must not upload either destination again.
        $this->assertTrue(app(SupportFileDistributor::class)->distribute($support));
        $this->assertCount(1, Http::recorded(fn ($request) => $request->method() === 'PUT'));
        $component->call('deleteSupportFile', $support->id)->assertHasNoErrors();
        $this->assertSame(0, SupportFile::count());
        $this->assertSame([], $cloudFiles);
        $this->assertCount(1, Http::recorded(fn ($request) => $request->method() === 'DELETE'));
        foreach ($expected as $name) {
            $this->assertSame($bytes, Storage::disk('sftp_geodis')->get('DOCS/'.$name));
        }
    }

    public function test_log_deduplicates_oc_lines_and_preserves_all_associations(): void
    {
        $support = $this->stage($this->service());
        $copies = $support->copies()->get();
        $this->assertSame(1, SupportFile::count());
        $this->assertCount(2, $copies);
        $this->assertSame('5213111-N-CLP-SHO001.pdf', $copies[0]->file_name);
        $this->assertSame('FN226786P125-N-CLP-SHO003.pdf', $copies[1]->file_name);
        $this->assertCount(2, $copies[0]->associations);
        $this->assertCount(1, $copies->pluck('sharepoint_path')->unique());
        $this->assertStringEndsWith('/SUP-'.$support->id.'-original.pdf', $copies[0]->sharepoint_path);
        Storage::disk('local')->assertExists($support->local_path);
    }

    public function test_same_oc_in_two_shos_generates_only_the_associated_pairs(): void
    {
        $service = $this->service();
        DB::table('order_references')->where('order_reference_value', 'FN226786P125/1')
            ->update(['order_reference_value' => '5213111/3']);
        $support = $this->stage($service);
        $copies = $support->copies()->orderBy('id')->get();
        $this->assertSame(['5213111-N-CLP-SHO001.pdf', '5213111-N-CLP-SHO003.pdf'], $copies->pluck('file_name')->all());
        $this->assertSame(['SHO001', 'SHO003'], $copies->pluck('cni')->all());
        $this->assertCount(2, $copies[0]->associations);
        $this->assertCount(1, $copies[1]->associations);
        foreach ($copies as $copy) {
            $this->assertSame([$copy->cni], array_values(array_unique(array_column($copy->associations, 'cni'))));
        }
        $this->assertCount(1, $copies->pluck('sharepoint_path')->unique());
    }

    public function test_missing_or_invalid_sho_prevents_staging_and_keeps_the_pending_original(): void
    {
        $service = $this->service();
        $form = $this->form($service);
        foreach (['', 'SHO/001'] as $cni) {
            DB::table('purchase_orders')->where('service_id', $service->id)->update(['purchase_order_number' => $cni]);
            $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
            $form->uploadFiles();
            $this->assertCount(0, $form->tempFiles);
            $this->assertTrue($form->getComponent()->getErrorBag()->has('form.files'));
            $this->assertSame(0, SupportFile::count());
        }
    }

    public static function pairMigrationStates(): array
    {
        return [
            'original schema' => [false, false, true],
            'column already added' => [true, false, true],
            'both indexes present' => [true, true, true],
            'old index already removed' => [true, false, false],
            'already complete' => [true, true, false],
        ];
    }

    #[DataProvider('pairMigrationStates')]
    public function test_pair_migration_resumes_without_losing_records(bool $hasCni, bool $newIndex, bool $oldIndex): void
    {
        $support = $this->stage($this->service());
        $names = $support->copies()->pluck('file_name')->all();
        $migration = require database_path('migrations/2026_10_22_120000_group_support_copies_by_identifier_and_cni.php');
        $migration->down();
        if ($hasCni) {
            Schema::table('support_file_copies', fn (Blueprint $table) => $table->string('cni')->nullable());
            DB::table('support_file_copies')->update(['cni' => 'SHOEXISTING']);
        }
        if ($newIndex) {
            Schema::table('support_file_copies', fn (Blueprint $table) => $table->unique(['support_file_id', 'identifier', 'cni'], 'support_copies_identifier_cni_unique'));
        }
        if (! $oldIndex) {
            Schema::table('support_file_copies', fn (Blueprint $table) => $table->dropUnique(['support_file_id', 'identifier']));
        }
        $migration->up();
        $migration->up();
        $this->assertTrue(Schema::hasColumn('support_file_copies', 'cni'));
        $this->assertTrue(Schema::hasIndex('support_file_copies', ['support_file_id', 'identifier', 'cni'], 'unique'));
        $this->assertFalse(Schema::hasIndex('support_file_copies', ['support_file_id', 'identifier'], 'unique'));
        $this->assertSame($names, $support->copies()->pluck('file_name')->all());
        $this->assertSame($hasCni ? ['SHOEXISTING'] : [null], $support->copies()->pluck('cni')->unique()->values()->all());
    }

    public function test_pair_migration_creates_replacement_index_before_removing_foreign_key_index(): void
    {
        $migration = require database_path('migrations/2026_10_22_120000_group_support_copies_by_identifier_and_cni.php');
        $migration->down();
        DB::connection()->enableQueryLog();
        $migration->up();
        $queries = array_column(DB::getQueryLog(), 'query');
        DB::connection()->disableQueryLog();
        $create = array_keys(array_filter($queries, fn ($query) => str_contains(strtolower($query), 'create unique index') && str_contains($query, 'support_copies_identifier_cni_unique')));
        $drop = array_keys(array_filter($queries, fn ($query) => str_contains(strtolower($query), 'drop index') && str_contains($query, 'support_file_copies_support_file_id_identifier_unique')));
        $this->assertCount(1, $create);
        $this->assertCount(1, $drop);
        $this->assertLessThan($drop[0], $create[0]);
    }

    public function test_pair_migration_rollback_can_be_repeated(): void
    {
        $support = $this->stage($this->service());
        $names = $support->copies()->pluck('file_name')->all();
        $migration = require database_path('migrations/2026_10_22_120000_group_support_copies_by_identifier_and_cni.php');
        $migration->down();
        $migration->down();
        $this->assertFalse(Schema::hasColumn('support_file_copies', 'cni'));
        $this->assertTrue(Schema::hasIndex('support_file_copies', ['support_file_id', 'identifier'], 'unique'));
        $this->assertSame($names, $support->copies()->pluck('file_name')->all());
    }

    public function test_pair_migration_can_roll_back_without_renaming_existing_files(): void
    {
        $support = $this->stage($this->service());
        $names = $support->copies()->pluck('file_name')->all();
        (require database_path('migrations/2026_10_22_120000_group_support_copies_by_identifier_and_cni.php'))->down();
        $this->assertFalse(Schema::hasColumn('support_file_copies', 'cni'));
        $this->assertSame($names, $support->copies()->pluck('file_name')->all());
    }

    public function test_pair_migration_refuses_to_drop_existing_oc_sho_pairs(): void
    {
        $service = $this->service();
        DB::table('order_references')->where('order_reference_value', 'FN226786P125/1')
            ->update(['order_reference_value' => '5213111/3']);
        $this->stage($service);
        try {
            (require database_path('migrations/2026_10_22_120000_group_support_copies_by_identifier_and_cni.php'))->down();
            $this->fail('Rollback must preserve both pairs.');
        } catch (RuntimeException) {
            $this->assertTrue(Schema::hasColumn('support_file_copies', 'cni'));
            $this->assertSame(2, SupportFileCopy::count());
        }
    }

    public static function otxFlows(): array
    {
        return ['TEBS' => ['DELIVERY-SO'], 'ROAD' => ['ROAD']];
    }

    #[DataProvider('otxFlows')]
    public function test_otx_uses_full_cni_instead_of_coi_and_collapses_repeated_values(string $flow): void
    {
        $service = $this->service($flow);
        DB::table('purchase_orders')->where('service_id', $service->id)->update(['purchase_order_number' => 'GR2006541A87']);
        $support = $this->stage($service);
        $this->assertSame(1, $support->copies()->count());
        $this->assertSame('GR2006541A87-N-CLP-N3708865.pdf', $support->copies()->first()->file_name);
        $this->assertCount(3, $support->copies()->first()->associations);
    }

    public function test_delivery_so_accepts_a_cni_without_gr_prefix(): void
    {
        $service = $this->service('DELIVERY-SO', [null]);
        DB::table('purchase_orders')->where('service_id', $service->id)->update(['purchase_order_number' => 'CUSTOM123']);
        $this->assertSame('CUSTOM123', app(SupportDocumentNaming::class)->context($service)['targets'][0]['identifier']);
    }

    public function test_services_without_references_cannot_generate_copies(): void
    {
        $this->expectException(ValidationException::class);
        app(SupportDocumentNaming::class)->targets($this->service(references: [null]));
    }

    public function test_deleted_orders_and_references_are_not_available(): void
    {
        $service = $this->service();
        DB::table('purchase_orders')->where('purchase_order_number', 'SHO003')->update(['deleted_at' => now()]);
        DB::table('order_references')->where('order_reference_value', '5213111/2')->update(['deleted_at' => now()]);
        $targets = app(SupportDocumentNaming::class)->context($service)['targets'];
        $this->assertCount(1, $targets);
        $this->assertCount(1, $targets[0]['associations']);
    }

    public function test_names_reject_invalid_identifiers_and_never_truncate_them(): void
    {
        $naming = app(SupportDocumentNaming::class);
        $this->assertSame('N3708865Entrega1', $naming->normalizeObservations('N3708865 / Entrega-1'));
        $this->assertSame(50, strlen($naming->filename('5213111', 'CLP', str_repeat('A', 32), 'pdf')));
        $this->expectException(ValidationException::class);
        $naming->filename('5213111', 'CLP', str_repeat('A', 33), 'pdf');
    }

    public function test_identifiers_with_symbols_require_correction_at_origin(): void
    {
        $this->expectException(ValidationException::class);
        app(SupportDocumentNaming::class)->filename('GR/123', 'CLP', 'N1', 'pdf');
    }

    public function test_linked_sho_is_added_by_backend_when_observations_are_empty_or_have_extra_text(): void
    {
        $service = $this->service();
        $form = $this->form($service);
        $this->assertNull($form->free_text);
        $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
        $form->uploadFiles();
        $this->assertSame('', $form->tempFiles[0]['observations']);
        $this->assertSame('5213111-N-CLP-SHO001.pdf', $form->tempFiles[0]['names'][0]);
        $form->free_text = 'Entrega 1';
        $form->files = [UploadedFile::fake()->create('delivery.pdf', 1, 'application/pdf')];
        $form->uploadFiles();
        $this->assertSame('Entrega1', $form->tempFiles[1]['observations']);
        $this->assertSame('5213111-N-CLP-SHO001-Entrega1.pdf', $form->tempFiles[1]['names'][0]);
        $this->assertSame('N3708865', app(SupportDocumentNaming::class)->observations($service, 'N3708865'));
    }

    public function test_collisions_get_a_short_sequence_without_overwriting(): void
    {
        $service = $this->service();
        $first = $this->stage($service);
        $second = $this->stage($service);
        $this->assertSame('5213111-N-CLP-SHO001.pdf', $first->copies()->first()->file_name);
        $this->assertSame('5213111-N-CLP-SHO001-2.pdf', $second->copies()->first()->file_name);
    }

    public function test_partial_retry_skips_successful_copies_and_sharepoint_stages(): void
    {
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/support.pdf']);
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->with('DOCS', '5213111-N-CLP-SHO001.pdf', 'DOCS/5213111-N-CLP-SHO001.pdf', Mockery::type('string'), Mockery::type('int'))->once()->andReturn([true, null]);
        $sftp->shouldReceive('upload')->with('DOCS', 'FN226786P125-N-CLP-SHO003.pdf', 'DOCS/FN226786P125-N-CLP-SHO003.pdf', Mockery::type('string'), Mockery::type('int'))->twice()->andReturn([false, 'offline'], [true, null]);
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $support = $this->stage($this->service(), $distributor);
        $source = $support->local_path;
        $this->assertFalse($distributor->distribute($support));
        $this->assertSame('https://example.sharepoint.com/support.pdf', $support->fresh()->file_url);
        $this->assertSame(1, $support->copies()->get()->pluck('file_url')->unique()->count());
        Storage::disk('local')->assertExists($source);
        $this->assertSame('Pendiente de guardar', $support->fresh()->copyProgress());
        $this->assertTrue($distributor->distribute($support));
        $this->assertTrue($distributor->distribute($support));
        $this->assertSame('Guardado', $support->fresh()->copyProgress());
        Storage::disk('local')->assertMissing($source);
        $this->assertNull($support->fresh()->local_path);
    }

    public function test_partial_group_deletion_can_resume_and_never_touches_sftp(): void
    {
        $support = $this->stage($this->service());
        foreach ($support->copies as $copy) {
            $copy->update(['file_url' => 'https://example.sharepoint.com/'.$copy->id.'.pdf', 'sharepoint_uploaded_at' => now()]);
        }
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('delete')->with('https://example.sharepoint.com/1.pdf')->once();
        $sharepoint->shouldReceive('delete')->with('https://example.sharepoint.com/2.pdf')->once()->andThrow(new RuntimeException('offline'));
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldNotReceive('upload');
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        try {
            $distributor->deleteGroup($support);
            $this->fail('The second deletion should fail.');
        } catch (RuntimeException) {
            $this->assertNotNull($support->fresh()->deletion_started_at);
            $this->assertSame(1, SupportFile::count());
        }
        $sharepoint->shouldReceive('delete')->with('https://example.sharepoint.com/2.pdf')->once();
        $distributor->deleteGroup($support);
        $this->assertSame(0, SupportFile::count());
        $this->assertSame(1, SupportFile::withTrashed()->count());
        $this->assertSame(2, SupportFileCopy::whereNotNull('sharepoint_deleted_at')->count());
        Storage::disk('sftp_geodis')->assertDirectoryEmpty('/');
    }

    public function test_one_sharepoint_document_is_deleted_once_for_all_sftp_copies(): void
    {
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->with(Mockery::type('string'), 'DOCS/'.now()->format('Y/m').'/N3708865/SUP-1-original.pdf')
            ->andReturn(['webUrl' => 'https://example.sharepoint.com/original.pdf']);
        $sharepoint->shouldReceive('delete')->once()->with('https://example.sharepoint.com/original.pdf');
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->twice()->andReturn([true, null]);
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $support = $this->stage($this->service(), $distributor);
        $this->assertTrue($distributor->distribute($support));
        $this->assertSame(1, $support->copies()->get()->pluck('file_url')->unique()->count());
        $distributor->deleteGroup($support);
        $this->assertSame(0, SupportFile::count());
        $this->assertSame(2, SupportFileCopy::whereNotNull('sharepoint_deleted_at')->count());
    }

    public function test_sharepoint_failure_keeps_the_original_and_retry_uploads_it_only_once(): void
    {
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andThrow(new RuntimeException('offline'));
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $support = $this->stage($this->service(), $distributor);
        $source = $support->local_path;
        $this->assertFalse($distributor->distribute($support));
        $this->assertSame(0, $support->copies()->whereNotNull('sftp_uploaded_at')->count());
        Storage::disk('local')->assertExists($source);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/original.pdf']);
        $sftp->shouldReceive('upload')->twice()->andReturn([true, null]);
        $this->assertTrue($distributor->distribute($support));
        $this->assertSame('https://example.sharepoint.com/original.pdf', $support->fresh()->file_url);
    }

    public function test_deletion_uses_parent_link_if_the_process_stopped_before_linking_copies(): void
    {
        $support = $this->stage($this->service());
        $support->update(['file_url' => 'https://example.sharepoint.com/original.pdf']);
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('delete')->once()->with($support->file_url);
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldNotReceive('upload');
        (new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp))->deleteGroup($support);
        $this->assertSame(0, SupportFile::count());
    }

    public function test_form_keeps_one_pending_original_and_its_selection_snapshot(): void
    {
        $form = $this->form($this->service());
        $form->files = [UploadedFile::fake()->create('report.pdf', 10241, 'application/pdf')];
        $form->uploadFiles();
        $this->assertCount(1, $form->tempFiles);
        $this->assertSame('report.pdf', $form->tempFiles[0]['fileName']);
        $form->free_text = 'Changed';
        $this->assertSame(['5213111', 'FN226786P125'], $form->tempFiles[0]['identifiers']);
        $this->assertSame('', $form->tempFiles[0]['observations']);
        $this->assertSame(0, SupportFile::count());
    }

    public function test_form_rejects_over_20_mb_and_prohibited_extensions(): void
    {
        $form = $this->form($this->service());
        foreach ([UploadedFile::fake()->create('large.pdf', 20481, 'application/pdf'), UploadedFile::fake()->create('bad.gif', 1, 'image/gif')] as $file) {
            $form->files = [$file];
            $form->uploadFiles();
            $this->assertSame([], $form->tempFiles);
            $this->assertTrue($form->getComponent()->getErrorBag()->has('form.files'));
        }
    }

    public function test_form_reports_naming_errors_under_the_form_fields(): void
    {
        $form = $this->form($this->service());
        $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
        DB::table('order_references')->where('order_reference_value', '5213111/1')->update(['order_reference_value' => 'BAD-ID/1']);
        $form->uploadFiles();
        $this->assertTrue($form->getComponent()->getErrorBag()->has('form.files'));
        DB::table('order_references')->where('order_reference_value', 'BAD-ID/1')->update(['order_reference_value' => '5213111/1']);
        $form->free_text = str_repeat('X', 50);
        $form->uploadFiles();
        $this->assertTrue($form->getComponent()->getErrorBag()->has('form.free_text'));
        $this->assertCount(0, $form->tempFiles);
    }

    public function test_saving_keeps_one_group_and_retry_survives_a_new_form_instance(): void
    {
        $service = $this->service();
        $form = $this->form($service);
        $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
        $form->uploadFiles();
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/file.pdf']);
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->twice()->andReturn([false, 'offline']);
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        // A stale or partial snapshot must not narrow the backend target set.
        $form->tempFiles[0]['identifiers'] = ['5213111'];
        $this->assertTrue($form->saveFiles($distributor));
        $this->runQueuedSupportUploads($distributor, expectFailure: true);
        $this->assertCount(0, $form->tempFiles);
        $this->assertSame(1, SupportFile::count());
        $this->assertSame(2, SupportFileCopy::count());
        $sftp->shouldReceive('upload')->twice()->andReturn([true, null]);
        $newForm = $this->form($service);
        $newForm->retrySupportFile(SupportFile::first()->id, $service, $distributor);
        $this->runQueuedSupportUploads($distributor);
        $this->assertSame('Guardado', SupportFile::first()->copyProgress());
    }

    public function test_invalid_references_at_save_leave_the_original_pending(): void
    {
        $service = $this->service();
        $form = $this->form($service);
        $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
        $form->uploadFiles();
        DB::table('order_references')->whereIn('order_reference_value', ['5213111/1', '5213111/2', 'FN226786P125/1'])->update(['deleted_at' => now()]);
        $this->assertFalse($form->saveFiles(app(SupportFileDistributor::class)));
        $this->assertCount(1, $form->tempFiles);
        $this->assertSame(0, SupportFile::count());
    }

    public function test_save_includes_identifiers_added_after_the_original_was_loaded(): void
    {
        $service = $this->service();
        $form = $this->form($service);
        $form->files = [UploadedFile::fake()->create('report.pdf', 1, 'application/pdf')];
        $form->uploadFiles();
        $this->assertCount(2, $form->tempFiles[0]['identifiers']);
        DB::table('order_references')->insert([
            'purchase_order_id' => DB::table('purchase_orders')->where('service_id', $service->id)->value('id'),
            'reference_type_id' => DB::table('reference_types')->where('reference_type_code', 'COI')->value('id'),
            'order_reference_value' => '989999/1',
        ]);
        $sharepoint = Mockery::mock(SharePointUploader::class);
        $sharepoint->shouldReceive('upload')->once()->andReturn(['webUrl' => 'https://example.sharepoint.com/file.pdf']);
        $sftp = Mockery::mock(SupportFileSftpUploader::class);
        $sftp->shouldReceive('upload')->times(3)->andReturn([true, null]);
        $distributor = new SupportFileDistributor(app(SupportDocumentNaming::class), $sharepoint, $sftp);
        $this->assertTrue($form->saveFiles($distributor));
        $this->runQueuedSupportUploads($distributor);
        $this->assertSame(1, SupportFile::count());
        $this->assertEqualsCanonicalizing(['5213111', 'FN226786P125', '989999'], SupportFileCopy::pluck('identifier')->all());
    }

    public function test_sources_and_completed_copies_cannot_be_modified_by_the_client(): void
    {
        $this->expectException(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);
        Volt::test('services.upload-file-modal', ['service' => $this->service(), 'canUploadSupports' => true])
            ->set('form.tempFiles', [['temp_path' => 'secret']]);
    }

    public function test_group_retry_is_scoped_to_its_service(): void
    {
        $support = $this->stage($this->service());
        $other = $this->service();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->form($other)->retrySupportFile($support->id, $other, app(SupportFileDistributor::class));
    }

    public function test_twenty_mb_is_accepted_and_other_users_temporary_files_are_not_removed(): void
    {
        Storage::disk('local')->put('livewire-tmp/other-user.txt', 'keep');
        $form = $this->form($this->service());
        $form->files = [UploadedFile::fake()->create('boundary.pdf', 20480, 'application/pdf')];
        $form->uploadFiles();
        $this->assertCount(1, $form->tempFiles);
        $form->clearTempFiles();
        $this->assertCount(0, $form->tempFiles);
        Storage::disk('local')->assertExists('livewire-tmp/other-user.txt');
    }

    public function test_unauthorized_users_cannot_stage_or_retry_supports(): void
    {
        $service = $this->service();
        $this->app['auth']->forgetGuards();
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->form($service)->uploadFiles();
    }

    public function test_retry_cannot_resume_a_group_with_deletion_pending(): void
    {
        $support = $this->stage($this->service());
        $support->update(['deletion_started_at' => now()]);
        $this->expectException(RuntimeException::class);
        app(SupportFileDistributor::class)->distribute($support);
    }

    public function test_successful_sftp_write_is_not_repeated_when_receiver_consumes_the_file(): void
    {
        Storage::disk('local')->put('test.pdf', 'support');
        $path = Storage::disk('local')->path('test.pdf');
        $disk = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $disk->shouldReceive('putFileAs')->once()->with('DOCS', Mockery::type(\Illuminate\Http\File::class), 'named.pdf')->andReturn('DOCS/named.pdf');
        $disk->shouldNotReceive('fileExists');
        $disk->shouldNotReceive('makeDirectory');
        Storage::shouldReceive('disk')->with('sftp_geodis')->once()->andReturn($disk);
        $this->assertSame([true, null], app(SupportFileSftpUploader::class)->upload('DOCS', 'named.pdf', 'DOCS/named.pdf', $path, 1));
    }

    public function test_sftp_retries_a_failed_write_and_stops_after_acknowledgement(): void
    {
        Storage::disk('local')->put('test.pdf', 'support');
        $path = Storage::disk('local')->path('test.pdf');
        $disk = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $disk->shouldReceive('putFileAs')->twice()->with('DOCS', Mockery::type(\Illuminate\Http\File::class), 'named.pdf')
            ->andReturn(false, 'DOCS/named.pdf');
        $disk->shouldNotReceive('makeDirectory');
        Storage::shouldReceive('disk')->with('sftp_geodis')->twice()->andReturn($disk);
        $this->assertSame([true, null], app(SupportFileSftpUploader::class)->upload('DOCS', 'named.pdf', 'DOCS/named.pdf', $path, 1));
    }

    public function test_sftp_returns_failure_after_exhausting_write_attempts(): void
    {
        Storage::disk('local')->put('test.pdf', 'support');
        $path = Storage::disk('local')->path('test.pdf');
        $disk = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $disk->shouldReceive('putFileAs')->times(5)->andThrow(new RuntimeException('write denied'));
        $disk->shouldNotReceive('makeDirectory');
        Storage::shouldReceive('disk')->with('sftp_geodis')->times(5)->andReturn($disk);
        $this->assertSame([false, 'write denied'], app(SupportFileSftpUploader::class)->upload('DOCS', 'named.pdf', 'DOCS/named.pdf', $path, 1));
        $this->assertSame('support', file_get_contents($path));
    }

    public function test_schema_rollback_keeps_legacy_support_records(): void
    {
        $service = $this->service();
        DB::table('support_files')->insert(['file_name' => 'legacy', 'file_extension' => 'pdf', 'service_id' => $service->id, 'file_type_id' => 1, 'user_id' => 1, 'uploaded_at' => now()->toDateString()]);
        (require database_path('migrations/2026_10_17_120000_add_document_copies_to_support_files.php'))->down();
        $this->assertFalse(Schema::hasTable('support_file_copies'));
        $this->assertFalse(Schema::hasColumn('support_files', 'local_path'));
        $this->assertSame('legacy', DB::table('support_files')->value('file_name'));
    }

    public function test_modal_renders_one_row_per_original_and_legacy_supports_remain_visible(): void
    {
        $service = $this->service();
        $this->stage($service);
        SupportFile::create(['file_name' => 'legacy', 'file_extension' => 'pdf', 'service_id' => $service->id, 'file_type_id' => 1, 'user_id' => 1, 'uploaded_at' => now()->toDateString()]);
        Volt::test('services.upload-file-modal', ['service' => $service, 'canUploadSupports' => true])
            ->assertSet('form.free_text', null)
            ->set('form.file_type', 'CLP')->assertSee('19 caracteres restantes')
            ->set('form.free_text', 'abc')->assertSee('16 caracteres restantes')
            ->assertDontSee('Área N fija.')
            ->assertSee('original.pdf')->assertSee('legacy')->assertSee('Pendiente de guardar')
            ->assertDontSee('Cada archivo se carga una sola vez')
            ->assertDontSee('Ver nombres previstos')->assertDontSee('copia(s)')
            ->assertDontSee('Observaciones en el nombre')->assertDontSee('Ejemplo para PDF')
            ->assertDontSee('Identificadores')
            ->assertDontSee('Seleccionar todos')->assertDontSee('Quitar selección')
            ->assertDontSeeHtml('type="checkbox"')->assertDontSeeHtml('form.selected_identifiers')
            ->assertSeeHtml('wire:key="support-file-1"')->assertSeeHtml('wire:key="support-file-2"')
            ->assertDontSeeHtml('wire:key="support-file-3"');
    }
}
