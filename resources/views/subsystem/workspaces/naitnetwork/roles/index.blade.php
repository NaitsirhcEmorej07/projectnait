<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Roles') }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4" x-data="{
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        editRole: { id: '', name: '', slug: '' },
        deleteRoleId: null
    }">

        <div class="max-w-7xl mx-auto space-y-2">

            @if (session('success'))
                <div class="rounded-xl bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-100 border border-red-200 text-red-700 px-4 py-3">
                    <div class="font-medium mb-1">Please fix the following:</div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- HEADER CARD -->
            <div class="bg-white shadow-sm rounded-2xl p-4 sm:p-6">
                <div class="flex items-start justify-between">

                    <!-- LEFT SIDE -->
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                            Role Manager
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Add, edit, or delete roles for NaitNetwork.
                        </p>
                    </div>

                    <!-- RIGHT ICON BUTTON -->
                    <button @click="openAddModal = true"
                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition">
                        <i class="pi pi-user-plus text-lg"></i>
                    </button>

                </div>
            </div>

            <!-- SEARCH BAR -->
            <div class="bg-white shadow-sm rounded-2xl p-3 sm:p-4">
                <form method="GET" action="{{ route('naitnetwork.roles.index') }}">
                    <div class="flex items-center gap-2">

                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="pi pi-search text-sm"></i>
                            </span>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search role "
                                class="w-full rounded-xl border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        @if (request('search'))
                            <a href="{{ route('naitnetwork.roles.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Clear
                            </a>
                        @endif

                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white shadow-sm rounded-2xl p-3 sm:p-6 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">

                        <thead>
                            <tr class="border-b border-gray-200 text-gray-700">
                                <th class="px-2 sm:px-4 py-2 sm:py-3 font-semibold">#</th>

                                <th class="px-2 sm:px-4 py-2 sm:py-3 font-semibold">
                                    Role
                                </th>

                                <th class="hidden sm:table-cell px-4 py-3 font-semibold">
                                    Slug
                                </th>

                                <th class="px-2 sm:px-4 py-2 sm:py-3 font-semibold text-center">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($roles as $index => $role)
                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-2 sm:px-4 py-2 sm:py-3 text-gray-600">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-2 sm:px-4 py-2 sm:py-3 font-medium text-gray-900">
                                        {{ $role->name }}

                                        <!-- Mobile slug -->
                                        <div class="sm:hidden text-xs text-gray-500">
                                            {{ $role->slug }}
                                        </div>
                                    </td>

                                    <td class="hidden sm:table-cell px-4 py-3 text-gray-600">
                                        {{ $role->slug }}
                                    </td>

                                    <td class="px-2 sm:px-4 py-2 sm:py-3">
                                        <div class="flex items-center justify-center gap-1 sm:gap-2">

                                            <!-- EDIT -->
                                            <button
                                                @click='editRole = @json(['id' => $role->id, 'name' => $role->name, 'slug' => $role->slug]); openEditModal = true'
                                                class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition"
                                                title="Edit">

                                                <i class="pi pi-pencil text-xs sm:text-sm"></i>

                                            </button>

                                            <!-- DELETE -->
                                            <button @click="deleteRoleId = {{ $role->id }}; openDeleteModal = true"
                                                class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-red-500 text-white hover:bg-red-600 transition"
                                                title="Delete">

                                                <i class="pi pi-trash text-xs sm:text-sm"></i>

                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">
                                        No roles found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>

        </div>

        <!-- ADD MODAL -->
        <div x-show="openAddModal" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

            <div @click.outside="openAddModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add Role</h3>
                    <button @click="openAddModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="pi pi-times"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('naitnetwork.roles.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name
                        </label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. Web Developer" required>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="openAddModal = false"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </button>

                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div x-show="openEditModal" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

            <div @click.outside="openEditModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Role</h3>
                    <button @click="openEditModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="pi pi-times"></i>
                    </button>
                </div>

                <form method="POST" :action="`{{ url('/naitnetwork/roles') }}/${editRole.id}`" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name
                        </label>
                        <input type="text" name="name" id="edit_name" x-model="editRole.name"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Slug
                        </label>
                        <input type="text" x-model="editRole.slug"
                            class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500" disabled>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="openEditModal = false"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </button>

                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div x-show="openDeleteModal" x-cloak x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

            <div @click.outside="openDeleteModal = false" class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Delete Role</h3>
                    <button @click="openDeleteModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="pi pi-times"></i>
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete this role?
                </p>

                <form method="POST" :action="`{{ url('/naitnetwork/roles') }}/${deleteRoleId}`"
                    class="flex items-center justify-end gap-2">
                    @csrf
                    @method('DELETE')

                    <button type="button" @click="openDeleteModal = false"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
