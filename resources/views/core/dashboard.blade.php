<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project NAIT
        </h2>
    </x-slot>

    <div class="p-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <!-- Welcome Card -->

            <div class="bg-white border border-gray-200 rounded-xl p-4">


                <h1 class="text-lg font-semibold tracking-widest text-gray-900">
                    Nait<span class="text-indigo-600 font-bold">Core</span>
                </h1>

                <p class="text-xs text-gray-500">
                    Central Operations & Resource Environment — Centralized dashboard accessing all active
                    subsystems.
                </p>

            </div>

            <!-- Dynamic Subsystem Cards -->
            <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @forelse($subsystems ?? [] as $subsystem)
                    <a href="{{ route('subsystem.landing', $subsystem->code) }}"
                        class="block bg-white shadow-sm rounded-xl p-4 hover:shadow-md transition h-full flex flex-col">

                        <div class="flex items-center gap-1 mb-3">

                            @if ($subsystem->icon)
                                <i class="{{ $subsystem->icon }} text-2xl text-indigo-600"></i>
                            @endif

                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $subsystem->name }}
                            </h3>

                        </div>

                        <p class="text-xs text-gray-500 line-clamp-2 leading-tight ">
                        {{-- <p class="text-xs text-gray-500 line-clamp-2 leading-tight  min-h-[4.5rem]"> --}}
                            {{ $subsystem->description }}
                        </p>

                    </a>

                @empty
                    <div class="col-span-full bg-white shadow-sm rounded-xl p-6 text-center text-gray-500">
                        No active subsystems found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
