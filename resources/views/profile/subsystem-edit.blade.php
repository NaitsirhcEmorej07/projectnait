<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Subsystem
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            Update Subsystem
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Revise the subsystem details below.
                        </p>
                    </header>

                    <form method="POST" action="{{ route('subsystem.update', $subsystem->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Subsystem Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $subsystem->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="code" :value="__('Code')" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full"
                                :value="old('code', $subsystem->code)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3">{{ old('description', $subsystem->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="route" :value="__('Route Name')" />
                            <x-text-input id="route" name="route" type="text" class="mt-1 block w-full"
                                :value="old('route', $subsystem->route)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('route')" />
                        </div>

                        <div>
                            <x-input-label for="icon" :value="__('Icon (Optional)')" />
                            <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full"
                                :value="old('icon', $subsystem->icon)" />
                            <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                    {{ old('is_active', $subsystem->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Update Subsystem</x-primary-button>

                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>