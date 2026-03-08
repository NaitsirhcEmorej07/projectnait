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

    <form method="POST" action="{{ route('subsystem.store') }}" class="mt-6">
        @csrf

        <div class="grid grid-cols-2 gap-4">

            <!-- Subsystem Name -->
            <div>
                <x-input-label for="name" :value="__('Subsystem Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Code -->
            <div>
                <x-input-label for="code" :value="__('Code')" />
                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code')"
                    oninput="updateRoute(this)" required />
                <x-input-error class="mt-2" :messages="$errors->get('code')" />
            </div>

            <!-- Description (FULL WIDTH) -->
            <div class="col-span-2">
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="3">{{ old('description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <!-- Route -->
            <div>
                <x-input-label for="route" :value="__('Route Name')" />
                <x-text-input id="route" name="route" type="text" class="mt-1 block w-full bg-gray-100"
                    :value="old('route')" readonly />
                <x-input-error class="mt-2" :messages="$errors->get('route')" />
            </div>

            <!-- Icon -->
            <div>
                <x-input-label for="icon" :value="__('Icon (Optional)')" />
                <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" :value="old('icon')"
                    placeholder="example: pi pi-th-large" />
                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
            </div>

            <!-- Active -->
            <div class="col-span-2 flex items-center">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="col-span-2">
                <x-primary-button>Save Subsystem</x-primary-button>
            </div>

        </div>
    </form>

    <script>
        function updateRoute(input) {

            let code = input.value
                .toLowerCase()
                .replace(/\s/g, '');

            input.value = code;

            let routeField = document.getElementById('route');

            routeField.value = code + '.index';
        }
    </script>
</section>
