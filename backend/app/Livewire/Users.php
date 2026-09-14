<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Users - EVM Dashboard')]
class Users extends Component
{
    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';
    public string $departmentFilter = '';

    public bool $showAddUserModal = false;
    public bool $showDetailModal = false;
    public bool $showEditUserModal = false;
    public bool $showDeleteUserModal = false;
    public ?int $selectedUserId = null;

    public array $userForm = [
        'name' => '',
        'email' => '',
        'role' => 'Project Manager',
        'department' => 'Project Management',
        'status' => 'Active',
    ];

    public array $users = [
        ['id'=>1,'name'=>'Alex Thompson','email'=>'alex.thompson@ptbintanggandari.com','role'=>'Executive Director','department'=>'Management','projects'=>12,'status'=>'Active','last_active'=>'Just now','avatar_color'=>'bg-blue-500','initials'=>'AT'],
        ['id'=>2,'name'=>'Sarah Anderson','email'=>'sarah.anderson@ptbintanggandari.com','role'=>'Project Manager','department'=>'Project Management','projects'=>8,'status'=>'Active','last_active'=>'5 min ago','avatar_color'=>'bg-pink-500','initials'=>'SA'],
        ['id'=>3,'name'=>'Budi Santoso','email'=>'budi.santoso@ptbintanggandari.com','role'=>'Site Engineer','department'=>'Engineering','projects'=>5,'status'=>'Active','last_active'=>'1 hr ago','avatar_color'=>'bg-green-500','initials'=>'BS'],
        ['id'=>4,'name'=>'Dewi Rahayu','email'=>'dewi.rahayu@ptbintanggandari.com','role'=>'Finance Controller','department'=>'Finance','projects'=>7,'status'=>'Active','last_active'=>'2 hrs ago','avatar_color'=>'bg-purple-500','initials'=>'DR'],
        ['id'=>5,'name'=>'Rudi Hermawan','email'=>'rudi.hermawan@ptbintanggandari.com','role'=>'QA Inspector','department'=>'Quality Assurance','projects'=>4,'status'=>'Active','last_active'=>'Yesterday','avatar_color'=>'bg-amber-500','initials'=>'RH'],
        ['id'=>6,'name'=>'Irene Kusuma','email'=>'irene.kusuma@ptbintanggandari.com','role'=>'Project Manager','department'=>'Project Management','projects'=>0,'status'=>'Pending','last_active'=>'Invited 2 days ago','avatar_color'=>'bg-orange-500','initials'=>'IK'],
        ['id'=>7,'name'=>'Hendra Wijaya','email'=>'hendra.wijaya@ptbintanggandari.com','role'=>'Site Supervisor','department'=>'Engineering','projects'=>3,'status'=>'Inactive','last_active'=>'30 days ago','avatar_color'=>'bg-slate-400','initials'=>'HW'],
    ];

    public function getSelectedUserProperty(): ?array
    {
        if ($this->selectedUserId === null) {
            return null;
        }

        foreach ($this->users as $user) {
            if ((int) $user['id'] === (int) $this->selectedUserId) {
                return $user;
            }
        }

        return null;
    }

    public function getFilteredUsersProperty(): array
    {
        return array_values(array_filter($this->users, function ($user) {
            $matchSearch = empty($this->search)
                || str_contains(strtolower($user['name']), strtolower($this->search))
                || str_contains(strtolower($user['email']), strtolower($this->search))
                || str_contains(strtolower($user['department']), strtolower($this->search));
            $matchRole   = empty($this->roleFilter)       || $user['role']       === $this->roleFilter;
            $matchStatus = empty($this->statusFilter)     || $user['status']     === $this->statusFilter;
            $matchDept   = empty($this->departmentFilter) || $user['department'] === $this->departmentFilter;
            return $matchSearch && $matchRole && $matchStatus && $matchDept;
        }));
    }

    public function resetUserForm(): void
    {
        $this->userForm = [
            'name' => '',
            'email' => '',
            'role' => 'Project Manager',
            'department' => 'Project Management',
            'status' => 'Active',
        ];
    }

    public function closeUserModals(): void
    {
        $this->showAddUserModal = false;
        $this->showDetailModal = false;
        $this->showEditUserModal = false;
        $this->showDeleteUserModal = false;
        $this->selectedUserId = null;
    }

    public function openAddUserModal(): void
    {
        $this->closeUserModals();
        $this->resetUserForm();
        $this->showAddUserModal = true;
    }

    public function openUserDetailModal(int $userId): void
    {
        $this->closeUserModals();
        $this->selectedUserId = $userId;
        $this->showDetailModal = true;
    }

    public function openEditUserModal(int $userId): void
    {
        $this->closeUserModals();
        $this->selectedUserId = $userId;

        $user = $this->selectedUser;
        if ($user) {
            $this->userForm = [
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'department' => $user['department'],
                'status' => $user['status'],
            ];
        }

        $this->showEditUserModal = true;
    }

    public function openDeleteUserModal(int $userId): void
    {
        $this->closeUserModals();
        $this->selectedUserId = $userId;
        $this->showDeleteUserModal = true;
    }

    public function saveUser(): void
    {
        $name = trim($this->userForm['name']);
        $email = trim($this->userForm['email']);

        if ($name === '' || $email === '') {
            return;
        }

        $nextId = 1;
        foreach ($this->users as $user) {
            $nextId = max($nextId, (int) $user['id'] + 1);
        }

        $initials = strtoupper(substr(preg_replace('/\s+/', ' ', $name), 0, 2));
        $this->users[] = [
            'id' => $nextId,
            'name' => $name,
            'email' => $email,
            'role' => $this->userForm['role'],
            'department' => $this->userForm['department'],
            'projects' => 0,
            'status' => $this->userForm['status'],
            'last_active' => 'Just now',
            'avatar_color' => 'bg-blue-500',
            'initials' => $initials,
        ];

        $this->resetUserForm();
        $this->showAddUserModal = false;
    }

    public function updateUser(): void
    {
        if ($this->selectedUserId === null) {
            return;
        }

        foreach ($this->users as $index => $user) {
            if ((int) $user['id'] === (int) $this->selectedUserId) {
                $this->users[$index]['name'] = trim($this->userForm['name']);
                $this->users[$index]['email'] = trim($this->userForm['email']);
                $this->users[$index]['role'] = $this->userForm['role'];
                $this->users[$index]['department'] = $this->userForm['department'];
                $this->users[$index]['status'] = $this->userForm['status'];
                $this->users[$index]['initials'] = strtoupper(substr(preg_replace('/\s+/', ' ', $this->userForm['name']), 0, 2));
                break;
            }
        }

        $this->resetUserForm();
        $this->showEditUserModal = false;
        $this->selectedUserId = null;
    }

    public function deleteUser(): void
    {
        if ($this->selectedUserId === null) {
            return;
        }

        $this->users = array_values(array_filter($this->users, fn ($user) => (int) $user['id'] !== (int) $this->selectedUserId));
        $this->showDeleteUserModal = false;
        $this->selectedUserId = null;
    }

    public function getTotalUsersProperty(): int   { return count($this->users); }
    public function getActiveUsersProperty(): int   { return count(array_filter($this->users, fn($u) => $u['status'] === 'Active')); }
    public function getPendingUsersProperty(): int  { return count(array_filter($this->users, fn($u) => $u['status'] === 'Pending')); }
    public function getInactiveUsersProperty(): int { return count(array_filter($this->users, fn($u) => $u['status'] === 'Inactive')); }
    public function getRolesProperty(): array       { return array_unique(array_column($this->users, 'role')); }
    public function getDepartmentsProperty(): array { return array_unique(array_column($this->users, 'department')); }

    public function render()
    {
        return view('livewire.users', [
            'filteredUsers'  => $this->filteredUsers,
            'totalUsers'     => $this->totalUsers,
            'activeUsers'    => $this->activeUsers,
            'pendingUsers'   => $this->pendingUsers,
            'inactiveUsers'  => $this->inactiveUsers,
            'roles'          => $this->roles,
            'departments'    => $this->departments,
            'selectedUser'   => $this->selectedUser,
        ])->layout('components.layouts.app');
    }
}