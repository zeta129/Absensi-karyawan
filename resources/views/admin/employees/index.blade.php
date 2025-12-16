<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-white leading-tight">
                👥 {{ __('Employee Management') }}
            </h2>
            <a href="{{ route('admin.employees.create') }}" class="btn-primary">
                ➕ Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 text-green-700 rounded animate-slide-in-down">
                ✓ {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded animate-slide-in-down">
                ✗ {{ session('error') }}
            </div>
            @endif

            <!-- Employees Table -->
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">Name</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">NIP</th>
                                    <th class="px-4 py-3 text-left">Department</th>
                                    <th class="px-4 py-3 text-left">Role</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr class="transition hover:bg-purple-50/40">
                                    <td class="px-4 py-3 font-medium">{{ $employee->name }}</td>
                                    <td class="px-4 py-3">{{ $employee->email }}</td>
                                    <td class="px-4 py-3 font-mono text-sm">{{ $employee->nip ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $employee->department ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $roleNames = [1 => 'Super Admin', 2 => 'Manager', 3 => 'Employee'];
                                            $roleBadges = [1 => 'badge-danger', 2 => 'badge-warning', 3 => 'badge-success'];
                                        @endphp
                                        <span class="{{ $roleBadges[$employee->role_id] ?? 'badge-info' }}">
                                            {{ $roleNames[$employee->role_id] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="{{ route('admin.employees.show', $employee) }}" 
                                           class="btn-primary" style="display:inline-block;padding:0.35rem 0.6rem;margin-right:4px;font-size:0.875rem;">
                                            View
                                        </a>
                                        <a href="{{ route('admin.employees.edit', $employee) }}" 
                                           class="btn-success" style="display:inline-block;padding:0.35rem 0.6rem;margin-right:4px;font-size:0.875rem;">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" style="padding:0.35rem 0.6rem;font-size:0.875rem;" 
                                                    onclick="return confirm('Delete {{ $employee->name }}? This cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No employees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Add button for mobile -->
    <style>
    #floatingAdd {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 60;
        border-radius: 9999px;
        padding: 12px 18px;
        background: linear-gradient(90deg,#6d28d9,#ec4899);
        color: #fff;
        box-shadow: 0 10px 30px rgba(99,102,241,0.12);
        display:flex;align-items:center;gap:8px;
        font-weight:600;
        text-decoration:none;
        transition: all 0.3s ease;
    }
    #floatingAdd:hover{ transform: translateY(-4px); box-shadow: 0 15px 40px rgba(99,102,241,0.2); }
    </style>

    <a id="floatingAdd" href="{{ route('admin.employees.create') }}" title="Add new employee">
        ➕ Add
    </a>
</x-app-layout>
