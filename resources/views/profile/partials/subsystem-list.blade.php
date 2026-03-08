<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            Subsystem Manager
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            View, edit, activate, deactivate, or delete your NAIT subsystems.
        </p>
    </header>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($subsystems->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Route</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($subsystems as $subsystem)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $subsystem->name }}
                                @if ($subsystem->description)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $subsystem->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $subsystem->code }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $subsystem->route }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($subsystem->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap gap-2">

                                    <a href="{{ route('subsystem.edit', $subsystem->id) }}"
                                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('subsystem.toggle', $subsystem->id) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="inline-flex items-center rounded-md px-3 py-2 text-xs font-medium text-white {{ $subsystem->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }}">
                                            {{ $subsystem->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('subsystem.destroy', $subsystem->id) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this subsystem?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-md bg-gray-50 p-4 text-sm text-gray-500">
            No subsystems found yet.
        </div>
    @endif
</section>