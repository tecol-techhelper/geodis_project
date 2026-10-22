<?php

namespace Tests\Unit;

use App\Livewire\Forms\Services\UploadFileForm;
use App\Models\Service;
use App\Models\SupportFile;
use App\Models\User;
use App\Services\AzureGraphService;
use App\Services\SharePointUploader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SupportFileDeletionTest extends TestCase
{
    private const GRAPH = 'https://graph.microsoft.com/v1.0/sites/test-site/drive';
    private const LIBRARY = 'https://example.sharepoint.com/sites/Test/Shared%20Documents';
    private const FILE = self::LIBRARY . '/DOCS/2026/10/N123/informe%20%231.docx';
    private const OFFICE_FILE = 'https://example.sharepoint.com/sites/Test/_layouts/15/Doc.aspx?sourcedoc=%7Btest-guid%7D&file=informe.docx&action=default';

    private function uploader(): SharePointUploader
    {
        config(['services.msgraph.site_id' => 'test-site']);
        Http::preventStrayRequests();
        $auth = Mockery::mock(AzureGraphService::class);
        $auth->shouldReceive('getAccessToken')->once()->andReturn('test-token');

        return new SharePointUploader($auth);
    }

    private function fakeGraph(int $itemStatus = 200, int $deleteStatus = 204): void
    {
        Http::fake(function ($request) use ($itemStatus, $deleteStatus) {
            if ($request->method() === 'DELETE') {
                return Http::response([], $deleteStatus);
            }
            if (str_contains($request->url(), '/root:/')) {
                return Http::response(['id' => 'file-id', 'file' => (object) []], $itemStatus);
            }

            return Http::response(['webUrl' => self::LIBRARY, 'parentReference' => ['driveId' => 'test-drive']]);
        });
    }

    public function test_it_deletes_the_stored_sharepoint_file_by_id(): void
    {
        $uploader = $this->uploader();
        $this->fakeGraph();
        $uploader->delete(self::FILE);

        Http::assertSent(fn ($r) => $r->method() === 'GET'
            && str_contains($r->url(), '/root:/DOCS/2026/10/N123/informe%20%231.docx'));
        Http::assertSent(fn ($r) => $r->method() === 'DELETE' && $r->url() === self::GRAPH . '/items/file-id');
        Http::assertSentCount(3);
    }

    public function test_it_accepts_an_already_missing_file(): void
    {
        $uploader = $this->uploader();
        $this->fakeGraph(404);
        $uploader->delete(self::FILE);

        Http::assertNotSent(fn ($r) => $r->method() === 'DELETE');
        Http::assertSentCount(2);
    }

    public function test_it_resolves_an_office_link_before_deleting_by_id(): void
    {
        $uploader = $this->uploader();
        Http::fake(function ($request) {
            if ($request->method() === 'DELETE') {
                return Http::response([], 204);
            }
            if (str_contains($request->url(), '/shares/')) {
                return Http::response([
                    'id' => 'office-file-id', 'file' => (object) [],
                    'parentReference' => ['driveId' => 'test-drive'],
                ]);
            }

            return Http::response(['webUrl' => self::LIBRARY, 'parentReference' => ['driveId' => 'test-drive']]);
        });

        $uploader->delete(self::OFFICE_FILE);

        $shareId = 'u!' . rtrim(strtr(base64_encode(self::OFFICE_FILE), '+/', '-_'), '=');
        Http::assertSent(fn ($r) => $r->method() === 'GET'
            && str_starts_with($r->url(), "https://graph.microsoft.com/v1.0/shares/{$shareId}/driveItem?"));
        Http::assertSent(fn ($r) => $r->method() === 'DELETE' && $r->url() === self::GRAPH . '/items/office-file-id');
        Http::assertSentCount(3);
    }

    #[DataProvider('unresolvedOfficeItems')]
    public function test_it_rejects_unverified_office_items(int $status, ?string $driveId, bool $isFile): void
    {
        $uploader = $this->uploader();
        Http::fake(function ($request) use ($status, $driveId, $isFile) {
            if (str_contains($request->url(), '/shares/')) {
                $item = ['id' => 'office-file-id', 'parentReference' => ['driveId' => $driveId]];
                $item[$isFile ? 'file' : 'folder'] = (object) [];

                return Http::response($item, $status);
            }

            return Http::response(['webUrl' => self::LIBRARY, 'parentReference' => ['driveId' => 'test-drive']]);
        });

        try {
            $uploader->delete(self::OFFICE_FILE);
            $this->fail('Unverified Office items must not be deleted.');
        } catch (RuntimeException $e) {
            $this->assertNotEmpty($e->getMessage());
        }

        Http::assertNotSent(fn ($r) => $r->method() === 'DELETE');
        Http::assertSentCount(2);
    }

    public static function unresolvedOfficeItems(): array
    {
        return [
            'missing link' => [404, 'test-drive', true],
            'denied access' => [403, 'test-drive', true],
            'different library' => [200, 'other-drive', true],
            'no library identity' => [200, null, true],
            'folder' => [200, 'test-drive', false],
        ];
    }

    public function test_it_rejects_links_outside_the_library(): void
    {
        $uploader = $this->uploader();
        $this->fakeGraph();

        try {
            $uploader->delete('https://other.sharepoint.com/sites/Test/Shared%20Documents/support.docx');
            $this->fail('An external library must be rejected.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('no pertenece', $e->getMessage());
        }

        Http::assertSentCount(1);
    }

    public function test_it_cannot_delete_a_folder(): void
    {
        $uploader = $this->uploader();
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/root:/')) {
                return Http::response(['id' => 'folder-id', 'folder' => (object) []]);
            }

            return Http::response(['webUrl' => self::LIBRARY]);
        });

        try {
            $uploader->delete(self::FILE);
            $this->fail('Only files can be deleted.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('identificar el archivo', $e->getMessage());
        }

        Http::assertNotSent(fn ($r) => $r->method() === 'DELETE');
    }

    public function test_it_reports_a_sharepoint_deletion_failure(): void
    {
        $uploader = $this->uploader();
        $this->fakeGraph(200, 403);
        $this->expectException(RuntimeException::class);
        $uploader->delete(self::FILE);
    }

    public function test_it_preserves_the_record_when_sharepoint_fails(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new User(['username' => 'administrador']));
        Log::shouldReceive('error')->once();
        Storage::shouldReceive('disk')->never();
        $file = Mockery::mock(SupportFile::class)->makePartial();
        $file->file_url = self::FILE;
        $file->shouldReceive('delete')->never();
        $service = $this->serviceWithFile($file);
        $uploader = Mockery::mock(SharePointUploader::class);
        $uploader->shouldReceive('delete')->once()->with(self::FILE)->andThrow(new RuntimeException('SharePoint unavailable'));
        $component = new class extends Component {};
        $form = new UploadFileForm($component, 'form');

        $form->deleteSupportFile(42, $service, $uploader);

        $this->assertTrue($component->getErrorBag()->has('form.supportDeletion'));
    }

    public function test_it_removes_the_record_after_sharepoint_without_touching_sftp(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new User(['username' => 'administrador']));
        Storage::shouldReceive('disk')->never();
        $file = Mockery::mock(SupportFile::class)->makePartial();
        $file->file_url = self::FILE;
        $file->shouldReceive('delete')->once()->andReturn(true);
        $service = $this->serviceWithFile($file);
        $uploader = Mockery::mock(SharePointUploader::class);
        $uploader->shouldReceive('delete')->once()->with(self::FILE)->andReturnNull();
        $component = new class extends Component {};
        $form = new UploadFileForm($component, 'form');

        $form->deleteSupportFile(42, $service, $uploader);

        $this->assertFalse($component->getErrorBag()->has('form.supportDeletion'));
    }

    public function test_it_rejects_unauthorized_deletion(): void
    {
        Auth::shouldReceive('user')->once()->andReturnNull();
        $service = Mockery::mock(Service::class);
        $service->shouldReceive('support_files')->never();
        $uploader = Mockery::mock(SharePointUploader::class);
        $uploader->shouldReceive('delete')->never();
        $form = new UploadFileForm(new class extends Component {}, 'form');

        try {
            $form->deleteSupportFile(42, $service, $uploader);
            $this->fail('Unauthorized deletion must be rejected.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
    }

    public function test_it_cannot_delete_a_support_from_another_service(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(new User(['username' => 'administrador']));
        $relation = Mockery::mock(HasMany::class);
        $relation->shouldReceive('findOrFail')->once()->with(42)->andThrow(new ModelNotFoundException());
        $service = Mockery::mock(Service::class);
        $service->shouldReceive('support_files')->once()->andReturn($relation);
        $uploader = Mockery::mock(SharePointUploader::class);
        $uploader->shouldReceive('delete')->never();
        $form = new UploadFileForm(new class extends Component {}, 'form');

        $this->expectException(ModelNotFoundException::class);
        $form->deleteSupportFile(42, $service, $uploader);
    }

    private function serviceWithFile(SupportFile $file): Service
    {
        $relation = Mockery::mock(HasMany::class);
        $relation->shouldReceive('findOrFail')->once()->with(42)->andReturn($file);
        $service = Mockery::mock(Service::class)->makePartial();
        $service->id = 8;
        $service->shouldReceive('support_files')->once()->andReturn($relation);

        return $service;
    }
}
