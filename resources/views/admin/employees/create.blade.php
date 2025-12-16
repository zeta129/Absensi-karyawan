<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            ➕ {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <form action="{{ route('admin.employees.store') }}" method="POST">
                        @csrf
                        @include('admin.employees._form')

                        <div class="mt-6 flex justify-end gap-2">
                            <a href="{{ route('admin.employees.index') }}" class="btn-outline">Cancel</a>
                            <button type="submit" class="btn-primary">Create Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
