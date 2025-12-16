<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-white leading-tight">
                👤 {{ $employee->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn-success">Edit</a>
                <a href="{{ route('admin.employees.index') }}" class="btn-outline">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Full Name</p>
                                    <p class="text-base font-medium">{{ $employee->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Email Address</p>
                                    <p class="text-base font-medium">{{ $employee->email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Phone Number</p>
                                    <p class="text-base font-medium">{{ $employee->phone ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Employee ID (NIP)</p>
                                    <p class="text-base font-medium font-mono">{{ $employee->nip ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Organization Info</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Department</p>
                                    <p class="text-base font-medium">{{ $employee->department ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Role</p>
                                    <div class="mt-1">
                                        @php
                                            $roleNames = [1 => 'Super Admin', 2 => 'Manager', 3 => 'Employee'];
                                            $roleBadges = [1 => 'badge-danger', 2 => 'badge-warning', 3 => 'badge-success'];
                                        @endphp
                                        <span class="{{ $roleBadges[$employee->role_id] ?? 'badge-info' }}">
                                            {{ $roleNames[$employee->role_id] ?? 'Unknown' }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Account Status</p>
                                    <p class="text-base font-medium mt-1">
                                        <span class="badge-success">Active</span>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Member Since</p>
                                    <p class="text-base font-medium">{{ $employee->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="mt-8 pt-6 border-t border-gray-300">
                        <h3 class="text-lg font-semibold text-red-600 mb-4">Danger Zone</h3>
                        <p class="text-sm text-gray-600 mb-4">Deleting this employee will remove all associated data. This action cannot be undone.</p>
                        
                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" 
                                    onclick="return confirm('Are you sure you want to permanently delete {{ $employee->name }}? This cannot be undone.')">
                                🗑️ Delete Employee
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
