<?php

namespace Tests\Feature;

use App\Livewire\Services\ServiceTable;
use App\Models\User;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class ServiceTableStateTest extends TestCase
{
    private function signIn(int $id, bool $admin = false): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = $id;
        $user->shouldReceive('hasPermission')->andReturn(false);
        $user->shouldReceive('hasRole')->with('admin')->andReturn($admin);
        $this->actingAs($user);
    }

    public function test_returning_to_table_restores_search_page_order_and_page_size(): void
    {
        $this->signIn(1);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->set('search', 'FN230056P003')
            ->set('setUp.footer.perPage', 25)
            ->call('sortBy', 'consecutive', 'desc')
            ->call('gotoPage', 5);

        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('search', 'FN230056P003')
            ->assertSet('setUp.footer.perPage', 25)
            ->assertSet('sortField', 'consecutive')
            ->assertSet('sortDirection', 'desc')
            ->assertSet('paginators.page', 5)
            ->set('search', 'N3740434')
            ->assertSet('paginators.page', 1);

        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('search', 'N3740434')
            ->assertSet('paginators.page', 1);
    }

    public function test_filters_survive_return_and_clearing_search_is_remembered(): void
    {
        $this->signIn(1);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->set('search', 'N3740434')
            ->set('filters', ['input_text' => ['consecutive' => 'N374']])
            ->set('enabledFilters', ['consecutive' => 'Consecutivo']);

        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('filters', ['input_text' => ['consecutive' => 'N374']])
            ->assertSet('enabledFilters', ['consecutive' => 'Consecutivo'])
            ->set('search', '');

        Livewire::test(ServiceTable::class, ['deferLoading' => true])->assertSet('search', '');
    }

    public function test_state_is_scoped_to_user_and_does_not_restore_row_selection(): void
    {
        $this->signIn(1);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->set('search', 'N3740434')
            ->set('checkboxValues', ['44'])
            ->call('gotoPage', 4);

        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('checkboxValues', []);
        $this->signIn(2);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('search', '')
            ->assertSet('paginators.page', 1);
    }

    public function test_saved_trash_mode_requires_current_admin_permission(): void
    {
        $this->signIn(1, admin: true);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])->call('setTrashMode', true);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])->assertSet('showTrash', true);

        $this->signIn(1);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])->assertSet('showTrash', false);
    }

    public function test_explicit_page_in_url_takes_priority_and_new_session_starts_clean(): void
    {
        $this->signIn(1);
        Livewire::test(ServiceTable::class, ['deferLoading' => true])
            ->set('search', 'N3740434')->call('gotoPage', 5);

        Livewire::withQueryParams(['page' => 2])
            ->test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('search', 'N3740434')
            ->assertSet('paginators.page', 2);

        session()->flush();
        Livewire::withQueryParams([])->test(ServiceTable::class, ['deferLoading' => true])
            ->assertSet('search', '')
            ->assertSet('paginators.page', 1)
            ->assertSet('setUp.footer.perPage', 10);
    }
}
