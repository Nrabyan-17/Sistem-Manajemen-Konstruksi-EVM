<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-start gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Users</h1>
            <p class="mt-1 text-sm text-slate-500">Manage users, roles, and access permissions across the EVM Dashboard.</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="flex gap-4">

        {{-- Total Users --}}
        <div class="flex-1 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Total Users</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs text-slate-500">Directory size</p>
        </div>

        {{-- Active Users --}}
        <div class="flex-1 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Active Users</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $activeUsers }}</p>
            <p class="mt-1 text-xs text-slate-500">Authenticated</p>
        </div>

        {{-- Pending Invitations --}}
        <div class="flex-1 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Pending Invitations</p>
            <p class="mt-2 text-3xl font-bold text-amber-500">{{ $pendingUsers }}</p>
            <p class="mt-1 text-xs text-slate-500">Awaiting acceptance</p>
        </div>

        {{-- Inactive Users --}}
        <div class="flex-1 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Inactive Users</p>
            <p class="mt-2 text-3xl font-bold text-slate-400">{{ $inactiveUsers }}</p>
            <p class="mt-1 text-xs text-slate-500">Access revoked</p>
        </div>

    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[220px]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5A6.5 6.5 0 114 10.5a6.5 6.5 0 0113 0z" />
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search users by name, email, or department..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
        </div>

        <select wire:model.live="roleFilter"
                class="px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter"
                class="px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="Pending">Pending</option>
            <option value="Inactive">Inactive</option>
        </select>

        <select wire:model.live="departmentFilter"
                class="px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}">{{ $dept }}</option>
            @endforeach
        </select>

        <button type="button"
                wire:click="openAddUserModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/30 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            + Add User
        </button>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-900">All Users</h2>
            <p class="text-xs text-slate-500 mt-0.5">Showing {{ count($filteredUsers) }} of {{ $totalUsers }} registered accounts</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="w-10 px-5 py-3 text-left">
                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 cursor-pointer focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Department</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Projects</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Last Active</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest text-slate-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredUsers as $user)
                    <tr class="hover:bg-slate-50/60 transition group">
                        {{-- Checkbox --}}
                        <td class="px-5 py-4">
                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 cursor-pointer focus:ring-blue-500">
                        </td>

                        {{-- User Info --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $user['avatar_color'] }} flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                    {{ $user['initials'] }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $user['name'] }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $user['email'] }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td class="px-4 py-4">
                            <span class="text-sm text-slate-700 font-medium">{{ $user['role'] }}</span>
                        </td>

                        {{-- Department --}}
                        <td class="px-4 py-4">
                            <span class="text-sm text-slate-600">{{ $user['department'] }}</span>
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
                        <td class="px-4 py-4">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 top-full mt-1 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 py-1.5"
                                     style="display:none;">
                                    {{-- View Profile --}}
                                    <button type="button"
                                            wire:click="openUserDetailModal({{ $user['id'] }})"
                                            @click="open = false"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-slate-700 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Profile
                                    </button>
                                    {{-- Edit User --}}
                                    <button type="button"
                                            wire:click="openEditUserModal({{ $user['id'] }})"
                                            @click="open = false"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-slate-700 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit User
                                    </button>
                                    {{-- Permissions --}}
                                    <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-slate-700 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Permissions
                                    </button>
                                    {{-- Reset Password --}}
                                    <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-slate-700 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                        Reset Password
                                    </button>
                                    <hr class="my-1.5 border-slate-100">
                                    {{-- Deactivate User --}}
                                    <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-orange-600 hover:bg-orange-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Deactivate User
                                    </button>
                                    {{-- Delete User --}}
                                    <button type="button"
                                            wire:click="openDeleteUserModal({{ $user['id'] }})"
                                            @click="open = false"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left text-red-600 hover:bg-red-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete User
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
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

    @if($showAddUserModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.outside="$wire.closeUserModals()" class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Add New User</h3>
                        <p class="text-xs text-slate-500 mt-1">Create a new account and assign access.</p>
                    </div>
                    <button type="button" wire:click="closeUserModals" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
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

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Role</label>
                            <select wire:model="userForm.role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Executive Director">Executive Director</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Site Engineer">Site Engineer</option>
                                <option value="Finance Controller">Finance Controller</option>
                                <option value="QA Inspector">QA Inspector</option>
                                <option value="Site Supervisor">Site Supervisor</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Department</label>
                            <select wire:model="userForm.department" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Management">Management</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Finance">Finance</option>
                                <option value="Quality Assurance">Quality Assurance</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Status</label>
                            <select wire:model="userForm.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Active">Active</option>
                                <option value="Pending">Pending</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="closeUserModals" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/25">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDetailModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.outside="$wire.closeUserModals()" class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">User Details</h3>
                        <p class="text-xs text-slate-500 mt-1">Profile overview and account details</p>
                    </div>
                    <button type="button" wire:click="closeUserModals" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl {{ $selectedUser['avatar_color'] }} flex items-center justify-center text-white text-lg font-bold shadow-sm">
                            {{ $selectedUser['initials'] }}
                        </div>
                        <div>
                            <p class="text-xl font-bold text-slate-900">{{ $selectedUser['name'] }}</p>
                            <p class="text-sm text-slate-500">{{ $selectedUser['role'] }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Email</p>
                            <p class="mt-1 text-sm text-slate-700 font-medium">{{ $selectedUser['email'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Department</p>
                            <p class="mt-1 text-sm text-slate-700 font-medium">{{ $selectedUser['department'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Projects</p>
                            <p class="mt-1 text-sm text-slate-700 font-medium">{{ $selectedUser['projects'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Status</p>
                            <p class="mt-1 text-sm font-semibold {{ $selectedUser['status'] === 'Active' ? 'text-emerald-600' : ($selectedUser['status'] === 'Pending' ? 'text-amber-600' : 'text-slate-500') }}">{{ $selectedUser['status'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showEditUserModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.outside="$wire.closeUserModals()" class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Edit User</h3>
                        <p class="text-xs text-slate-500 mt-1">Update user details and access.</p>
                    </div>
                    <button type="button" wire:click="closeUserModals" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="updateUser" class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Full Name</label>
                            <input type="text" wire:model="userForm.name" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Email Address</label>
                            <input type="email" wire:model="userForm.email" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Role</label>
                            <select wire:model="userForm.role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Executive Director">Executive Director</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Site Engineer">Site Engineer</option>
                                <option value="Finance Controller">Finance Controller</option>
                                <option value="QA Inspector">QA Inspector</option>
                                <option value="Site Supervisor">Site Supervisor</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Department</label>
                            <select wire:model="userForm.department" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Management">Management</option>
                                <option value="Project Management">Project Management</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Finance">Finance</option>
                                <option value="Quality Assurance">Quality Assurance</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold tracking-wider uppercase text-slate-400 mb-1.5">Status</label>
                            <select wire:model="userForm.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Active">Active</option>
                                <option value="Pending">Pending</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="closeUserModals" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm shadow-blue-500/25">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteUserModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div @click.outside="$wire.closeUserModals()" class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
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
                            <p class="font-bold">Delete {{ $selectedUser['name'] }}?</p>
                            <p class="text-sm mt-1">This will permanently remove the user account and all associated access for this record.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeUserModals" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button type="button" wire:click="deleteUser" class="px-4 py-2.5 rounded-xl bg-red-600 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm shadow-red-500/25">
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
