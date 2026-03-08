<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Create Subsystem
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Add a new subsystem that will appear dynamically in the NAIT dashboard.
        </p>
    </header>

    @if (session('success'))
        <div class="mt-4 p-3 rounded-md bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('subsystem.store') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Subsystem Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name')" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="code" :value="__('Code')" />
            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full"
                :value="old('code')" required />
            <x-input-error class="mt-2" :messages="$errors->get('code')" />
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                rows="3">{{ old('description') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div>
            <x-input-label for="route" :value="__('Route Name')" />
            <x-text-input id="route" name="route" type="text" class="mt-1 block w-full"
                :value="old('route')" placeholder="example: naittask.index" required />
            <x-input-error class="mt-2" :messages="$errors->get('route')" />
        </div>

        <div>
            <x-input-label for="icon" :value="__('Icon (Optional)')" />
            <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full"
                :value="old('icon')" placeholder="example: pi pi-th-large" />
            <x-input-error class="mt-2" :messages="$errors->get('icon')" />
        </div>

        <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                <span class="ml-2 text-sm text-gray-600">Active</span>
            </label>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Save Subsystem</x-primary-button>
        </div>
    </form>
</section>