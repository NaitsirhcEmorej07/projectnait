<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project NAIT
        </h2>
    </x-slot>

    <div class="p-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Card -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Welcome to NAIT Core
                </h1>
                <p class="text-gray-600">
                    This is your central dashboard for accessing all active NAIT subsystems.
                </p>
            </div>

            <!-- Dynamic Subsystem Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                @forelse($subsystems ?? [] as $subsystem)
                    <a href="{{ route('subsystem.landing', $subsystem->code) }}"
                        class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">

                        <div class="flex items-center gap-3 mb-3">

                            @if ($subsystem->icon)
                                <i class="{{ $subsystem->icon }} text-2xl text-indigo-600"></i>
                            @endif

                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $subsystem->name }}
                            </h3>

                        </div>

                        <p class="text-sm text-gray-600">
                            {{ $subsystem->description }}
                        </p>

                    </a>

                @empty
                    <div class="col-span-full bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                        No active subsystems found.
                    </div>
                @endforelse
            </div>
        </div>
</x-app-layout>
