<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            ✏️ {{ __('Edit Employee') }}: {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @include('admin.employees._form')

                        <div class="mt-6 flex justify-end gap-2">
                            <a href="{{ route('admin.employees.index') }}" class="btn-outline">Cancel</a>
                            <button type="submit" class="btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
