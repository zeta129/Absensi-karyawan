@if($errors->any())
<div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded">
    <p class="font-semibold mb-2">Please fix the errors below:</p>
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="space-y-4">
    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
        <input type="text" id="name" name="name" value="{{ old('name', $employee->name ?? '') }}" 
               class="input-modern" required>
        @error('name')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
        <input type="email" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}" 
               class="input-modern" required>
        @error('email')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- NIP -->
    <div>
        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP (Employee ID)</label>
        <input type="text" id="nip" name="nip" value="{{ old('nip', $employee->nip ?? '') }}" 
               class="input-modern">
        @error('nip')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Department -->
    <div>
        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
        <input type="text" id="department" name="department" value="{{ old('department', $employee->department ?? '') }}" 
               class="input-modern" placeholder="e.g., IT, HR, Finance">
        @error('department')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" 
               class="input-modern" placeholder="+1-XXX-XXX-XXXX">
        @error('phone')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Role -->
    <div>
        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
        <select id="role_id" name="role_id" class="select-modern" required>
            <option value="">-- Select Role --</option>
            <option value="1" @selected(old('role_id', $employee->role_id ?? null) == 1)>Super Admin</option>
            <option value="2" @selected(old('role_id', $employee->role_id ?? null) == 2)>Manager</option>
            <option value="3" @selected(old('role_id', $employee->role_id ?? null) == 3)>Employee</option>
        </select>
        @error('role_id')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password (only on create) -->
    @if(!isset($employee) || !$employee->exists)
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
        <input type="password" id="password" name="password" class="input-modern" required minlength="8">
        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
        @error('password')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="input-modern" required minlength="8">
        @error('password_confirmation')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>
    @else
    <!-- Password change (optional on edit) -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Change Password (leave blank to keep current)</label>
        <input type="password" id="password" name="password" class="input-modern" minlength="8">
        @error('password')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="input-modern" minlength="8">
        @error('password_confirmation')
        <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>
    @endif
</div>
