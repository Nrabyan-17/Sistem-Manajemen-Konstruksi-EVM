<?php

namespace Tests\Feature;

use App\Livewire\Users;
use Livewire\Livewire;
use Tests\TestCase;

class UsersModalTest extends TestCase
{
    public function test_add_user_modal_opens_and_form_fields_are_visible(): void
    {
        Livewire::test(Users::class)
            ->call('openAddUserModal')
            ->assertSet('showAddUserModal', true)
            ->assertSee('Add New User')
            ->assertSee('Full Name')
            ->assertSee('Email Address');
    }

    public function test_detail_modal_can_open_for_selected_user(): void
    {
        Livewire::test(Users::class)
            ->call('openUserDetailModal', 2)
            ->assertSet('showDetailModal', true)
            ->assertSet('selectedUserId', 2)
            ->assertSee('User Details');
    }

    public function test_edit_modal_can_open_for_selected_user(): void
    {
        Livewire::test(Users::class)
            ->call('openEditUserModal', 3)
            ->assertSet('showEditUserModal', true)
            ->assertSet('selectedUserId', 3)
            ->assertSee('Edit User');
    }

    public function test_delete_modal_can_open_for_selected_user(): void
    {
        Livewire::test(Users::class)
            ->call('openDeleteUserModal', 4)
            ->assertSet('showDeleteUserModal', true)
            ->assertSet('selectedUserId', 4)
            ->assertSee('Delete User');
    }
}
