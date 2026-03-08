<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if ($subsystem->icon)
                    <i class="{{ $subsystem->icon }} text-2xl text-indigo-600"></i>
                @endif

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $subsystem->name }}
                </h2>
            </div>

            <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $subsystem->name }} Subsystem
                </h1>

                <p class="text-gray-600">
                    {{ $subsystem->description }}
                </p>
            </div>

            @if ($subsystem->code === 'naitnetwork')
                @include('subsystem.workspaces.naitnetwork')
            @elseif($subsystem->code === 'naittask')
                @include('subsystem.workspaces.naittask')
            @elseif($subsystem->code === 'naitknowledge')
                @include('subsystem.workspaces.naitknowledge')
            @elseif($subsystem->code === 'naitgpt')
                @include('subsystem.workspaces.naitgpt')
            @else
                @include('subsystem.workspaces.default')
            @endif

        </div>
    </div>
</x-app-layout>
