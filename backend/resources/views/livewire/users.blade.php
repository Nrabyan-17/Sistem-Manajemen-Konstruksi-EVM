<div class="space-y-6" x-data="{
    selectAllUsers: false,
    selectedUsers: [],
    allUserIds: @js(array_map('strval', array_column($filteredUsers, 'id'))),
    addUserModal: $wire.entangle('showAddUserModal').live,
    detailModal: $wire.entangle('showDetailModal').live,
    editUserModal: $wire.entangle('showEditUserModal').live,
    deleteUserModal: $wire.entangle('showDeleteUserModal').live,
    selectedUserData: {}
}">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Users</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Manage users, roles, and access permissions across the EVM Dashboard.</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-bold tracking-wider text-slate-400 uppercase truncate">Total Users</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-slate-900 truncate">{{ $totalUsers }}</p>
            </div>
            <p class="mt-1 text-[11px] sm:text-xs text-slate-400 truncate">Directory size</p>
        </div>

        {{-- Active Users --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-bold tracking-wider text-slate-400 uppercase truncate">Active Users</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600 truncate">{{ $activeUsers }}</p>
            </div>
            <p class="mt-1 text-[11px] sm:text-xs text-slate-400 truncate">Authenticated</p>
        </div>

        {{-- Pending Invitations --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-bold tracking-wider text-slate-400 uppercase truncate">Pending Invitations</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-amber-500 truncate">{{ $pendingUsers }}</p>
            </div>
            <p class="mt-1 text-[11px] sm:text-xs text-slate-400 truncate">Awaiting acceptance</p>
        </div>

        {{-- Inactive Users --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-bold tracking-wider text-slate-400 uppercase truncate">Inactive Users</p>
                <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-slate-400 truncate">{{ $inactiveUsers }}</p>
            </div>
            <p class="mt-1 text-[11px] sm:text-xs text-slate-400 truncate">Access revoked</p>
        </div>

    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 sm:gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5A6.5 6.5 0 114 10.5a6.5 6.5 0 0113 0z" />
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search users by name, email, or role..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
        </div>

        <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">
            <select wire:model.live="roleFilter"
                    class="w-full sm:w-auto px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition cursor-pointer">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>

            <select wire:model.live="statusFilter"
                    class="w-full sm:w-auto px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition cursor-pointer">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Pending">Pending</option>
                <option value="Inactive">Inactive</option>
            </select>

            <button type="button"
                @click="addUserModal = true; $wire.openAddUserModal()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-md shadow-blue-500/20 shrink-0 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add User
            </button>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-900">All Users</h2>
                <p class="text-xs text-slate-500 mt-0.5">Showing {{ count($filteredUsers) }} of {{ $totalUsers }} registered accounts</p>
            </div>
              <div x-show="selectedUsers.length > 0"
                  x-cloak
                  x-transition:enter="transition ease-out duration-500"
                  x-transition:enter-start="opacity-0 -translate-y-2"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-500"
                  x-transition:leave-start="opacity-100 translate-y-0"
                  x-transition:leave-end="opacity-0 -translate-y-2"
                  class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                        <span x-text="selectedUsers.length"></span>
                        <span>user dipilih</span>
                    </span>
                    <button type="button" 
                            @click="selectedUsers = []; selectAllUsers = false; $wire.set('selectedUserIds', [])" 
                            class="text-xs text-slate-500 hover:text-rose-600 font-medium transition">
                        Batal Pilih
                    </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="w-10 px-5 py-3 text-left">
                            <input type="checkbox" 
                                   id="selectAllUsers"
                                   x-model="selectAllUsers"
                                   @change="
                                       if ($event.target.checked) {
                                           selectedUsers = [...allUserIds];
                                           $wire.set('selectedUserIds', allUserIds);
                                       } else {
                                           selectedUsers = [];
                                           $wire.set('selectedUserIds', []);
                                       }
                                   "
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 cursor-pointer focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                            <label for="selectAllUsers" class="cursor-pointer select-none hover:text-slate-600 transition">User</label>
                        </th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Projects</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Last Active</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredUsers as $user)
                    @php
                        $isSelected = in_array((string)$user['id'], array_map('strval', $selectedUserIds));
                    @endphp
                    <tr @click="selectedUserData = @js(['name' => $user['name'], 'email' => $user['email'], 'role' => $user['role'], 'initials' => $user['initials'], 'avatar_color' => $user['avatar_color'], 'last_active' => $user['last_active'], 'projects' => $user['projects'], 'status' => $user['status']]); detailModal = true; $wire.openUserDetailModal({{ $user['id'] }})"
                        class="hover:bg-slate-50/80 transition group cursor-pointer {{ $isSelected ? 'bg-blue-50/30' : '' }}">
                        {{-- Checkbox --}}
                        <td class="px-5 py-4" @click.stop>
                            <input type="checkbox" 
                                   x-bind:checked="selectedUsers.includes('{{ $user['id'] }}')"
                                   @change="
                                       const id = '{{ $user['id'] }}';
                                       if ($event.target.checked) {
                                           if (!selectedUsers.includes(id)) selectedUsers = [...selectedUsers, id];
                                       } else {
                                           selectedUsers = selectedUsers.filter(u => u !== id);
                                           selectAllUsers = false;
                                       }
                                       $wire.set('selectedUserIds', selectedUsers);
                                   "
                                   value="{{ $user['id'] }}"
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 cursor-pointer focus:ring-blue-500">
                        </td>

                        {{-- User Info --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $user['avatar_color'] }} flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                    {{ $user['initials'] }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-blue-600 transition-colors">{{ $user['name'] }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $user['email'] }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td class="px-4 py-4">
                            <span class="text-sm text-slate-700 font-medium">{{ $user['role'] }}</span>
                        </td>

                        {{-- Projects --}}
                        <td class="px-4 py-4">
                            @if($user['projects'] > 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                                    {{ $user['projects'] }} Projects
                                </span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4">
                            @if($user['status'] === 'Active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold tracking-wider uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                            @elseif($user['status'] === 'Pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold tracking-wider uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-[10px] font-bold tracking-wider uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Last Active --}}
                        <td class="px-4 py-4">
                            <span class="text-sm text-slate-500">{{ $user['last_active'] }}</span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-4" @click.stop>
                            <div class="flex items-center gap-1">
                                {{-- Edit User --}}
                                <button type="button"
                                        @click.stop="selectedUserData = @js(['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role'], 'status' => $user['status']]); editUserModal = true; $wire.openEditUserModal({{ $user['id'] }})"
                                        title="Edit User"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                {{-- Delete User --}}
                                <button type="button"
                                        @click.stop="selectedUserData = @js(['name' => $user['name']]); deleteUserModal = true; $wire.openDeleteUserModal({{ $user['id'] }})"
                                        title="Delete User"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">No users found</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search or filter criteria</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
    <div x-show="addUserModal" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex h-screen min-h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div @click.outside="$wire.closeUserModals()" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Add New User</h3>
                        <p class="text-xs text-slate-500 mt-1">Create a new account and assign access.</p>
                    </div>
                    <button type="button" @click="addUserModal = false; $wire.closeUserModals()" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveUser" class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Full Name</label>
                            <input type="text" wire:model="userForm.name" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Bunga Kharisma" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Email Address</label>
                            <input type="email" wire:model="userForm.email" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="name@ptbintanggandari.com" />
                        </div>

                        <div class="md:col-span-2" x-data="{ showPassword: false }">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" wire:model="userForm.password" class="w-full pl-3.5 pr-11 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="••••••••" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" style="right: 14px;" tabindex="-1">
                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Role</label>
                            <select wire:model="userForm.role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Status</label>
                            <select wire:model="userForm.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Active">Active</option>
                                <option value="Pending">Pending</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button type="button" @click="addUserModal = false; $wire.closeUserModals()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/25">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

    <template x-teleport="body">
    <div x-show="detailModal" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div @click.outside="detailModal = false; $wire.closeUserModals()" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">User Details</h3>
                    <p class="text-xs text-slate-500 mt-1">Profile overview and account details</p>
                </div>
                <button type="button" @click="detailModal = false; $wire.closeUserModals()" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-sm" :class="selectedUserData.avatar_color" x-text="selectedUserData.initials"></div>
                    <div>
                        <p class="text-xl font-bold text-slate-900" x-text="selectedUserData.name"></p>
                        <p class="text-sm text-slate-500" x-text="selectedUserData.role"></p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Email</p>
                        <p class="mt-1 text-sm text-slate-700 font-medium" x-text="selectedUserData.email"></p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Last Active</p>
                        <p class="mt-1 text-sm text-slate-700 font-medium" x-text="selectedUserData.last_active"></p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Projects</p>
                        <p class="mt-1 text-sm text-slate-700 font-medium" x-text="selectedUserData.projects"></p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Status</p>
                        <p class="mt-1 text-sm font-semibold" :class="selectedUserData.status === 'Active' ? 'text-emerald-600' : (selectedUserData.status === 'Pending' ? 'text-amber-600' : 'text-slate-500')" x-text="selectedUserData.status"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </template>

    <template x-teleport="body">
    <div x-show="editUserModal" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div @click.outside="editUserModal = false; $wire.closeUserModals()" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Edit User</h3>
                    <p class="text-xs text-slate-500 mt-1">Update user details and access.</p>
                </div>
                <button type="button" @click="editUserModal = false; $wire.closeUserModals()" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateUser" class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Full Name</label>
                        <input type="text" wire:model="userForm.name"
                               x-init="$el.value = selectedUserData.name || ''"
                               x-effect="if (editUserModal) $el.value = selectedUserData.name || ''"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Email Address</label>
                        <input type="email" wire:model="userForm.email"
                               x-init="$el.value = selectedUserData.email || ''"
                               x-effect="if (editUserModal) $el.value = selectedUserData.email || ''"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>

                    <div class="md:col-span-2" x-data="{ showPassword: false }">
                        <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Password (optional)</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" wire:model="userForm.password" placeholder="Leave blank to keep unchanged" class="w-full pl-3.5 pr-11 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" style="right: 14px;" tabindex="-1">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Role</label>
                        <select wire:model="userForm.role"
                                x-init="$el.value = selectedUserData.role || ''"
                                x-effect="if (editUserModal) $el.value = selectedUserData.role || ''"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Status</label>
                        <select wire:model="userForm.status"
                                x-init="$el.value = selectedUserData.status || 'Active'"
                                x-effect="if (editUserModal) $el.value = selectedUserData.status || 'Active'"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" @click="editUserModal = false; $wire.closeUserModals()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/25">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
    </template>

    <template x-teleport="body">
    <div x-show="deleteUserModal" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div @click.outside="deleteUserModal = false; $wire.closeUserModals()" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-900">Delete User</h3>
                <p class="text-xs text-slate-500 mt-1">This action cannot be undone.</p>
            </div>

            <div class="p-6">
                <div class="flex items-start gap-4 rounded-2xl bg-red-50 border border-red-100 p-4 text-red-700">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">Delete <span x-text="selectedUserData.name"></span>?</p>
                        <p class="text-sm mt-1">This will permanently remove the user account and all associated access for this record.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" @click="deleteUserModal = false; $wire.closeUserModals()" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteUser" class="px-4 py-2.5 rounded-xl bg-red-600 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm shadow-red-500/25">
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>
    </template>

</div>
