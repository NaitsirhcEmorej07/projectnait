<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project NAIT
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Card -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Welcome to NAIT Core
                </h1>
                <p class="text-gray-600">
                    Network Assistance Intelligence Tool is my personal modular life platform
                    built to manage networks, tasks, knowledge, and AI-assisted thinking
                    from one centralized dashboard.
                </p>
            </div>

            <!-- Subsystem Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <a href="{{ route('naitnetwork.index') }}"
                   class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">NaitNetwork</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Manage people, relationships, and connections.
                    </p>
                </a>

                <a href="{{ route('naittask.index') }}"
                   class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">NaitTask</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Organize tasks, priorities, and execution.
                    </p>
                </a>

                <a href="{{ route('naitknowledge.index') }}"
                   class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">NaitKnowledge</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Store notes, learnings, and references.
                    </p>
                </a>

                <a href="{{ route('naitgpt.index') }}"
                   class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">NaitGPT</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        Use AI for planning, thinking, and decision support.
                    </p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>