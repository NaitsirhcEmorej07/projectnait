<div class="space-y-4">

    {{-- HERO / INTRO --}}
    {{-- <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow p-6 sm:p-8">
        <div class="max-w-3xl">

            <p class="text-sm font-medium uppercase tracking-wider text-indigo-100 mb-2">
                Project NAIT • NaitNetwork
            </p>

            <h1 class="text-2xl sm:text-3xl font-bold leading-tight mb-3">
                What are you looking for?
            </h1>

            <p class="text-sm sm:text-base text-indigo-100 leading-relaxed">
                Search your personal network by role and quickly find the right person
                in your circle. Store profiles, notes, social links, and contact
                information in one organized system.
            </p>

        </div>
    </div> --}}


    {{-- ROLE SEARCH --}}
    <div class="bg-white shadow-sm rounded-2xl p-5 sm:p-6">

        <div class="mb-3 flex items-center justify-between">

            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                What are you looking for?
            </h2>

            <div x-data="{ open: false }" class="relative">

                <!-- SETTINGS BUTTON -->
                <button @click="open = !open"
                    class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition">
                    <i class="pi pi-cog text-lg"></i>
                </button>

                <!-- DROPDOWN -->
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute right-0 mt-1 w-40 bg-white border rounded-xl shadow-lg z-50">

                    <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                        <i class="pi pi-users"></i>
                        Add Network
                    </a>

                    <a href="{{ route('naitnetwork.roles.index') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                        <i class="pi pi-id-card text-gray-500"></i>
                        Manage Roles
                    </a>

                </div>

            </div>

        </div>


        <form action="{{ route('subsystem.landing', 'naitnetwork') }}" method="GET">

            <div class="flex items-center gap-3 w-full">

                {{-- <label for="role" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                    Role
                </label> --}}

                <select id="role" name="role" onchange="this.form.submit()"
                    class="flex-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                    <option value="">Select a role</option>

                    @foreach ($roles ?? [] as $role)
                        <option value="{{ $role->id }}"
                            {{ (string) ($selectedRole ?? '') === (string) $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach

                </select>

            </div>

        </form>

    </div>

    @if ($people->count() > 0)

        <div class="grid grid-cols-2 gap-3">

            @foreach ($people as $person)
                <div class="bg-white shadow-sm rounded-xl p-4 hover:shadow-md transition">

                    <div class="flex flex-col items-center text-center">

                        <div
                            class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mb-2">
                            {{ strtoupper(substr($person->name, 0, 1)) }}
                        </div>

                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ $person->name }}
                        </h3>

                        @if ($person->summary)
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                {{ $person->summary }}
                            </p>
                        @endif

                        <div class="flex flex-wrap justify-center gap-1 mt-2">
                            @foreach ($person->roles->take(2) as $role)
                                <span class="px-2 py-0.5 text-[10px] bg-indigo-100 text-indigo-700 rounded-full">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $people->appends(['role' => $selectedRole])->links() }}
        </div>
    @elseif(isset($selectedRole) && $selectedRole)
        <div class="bg-white rounded-xl shadow-sm p-6 text-center text-sm text-gray-500">
            No people found for this role yet.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-6 text-center text-sm text-gray-500">
            Select a role to view matching people.
        </div>

    @endif





    <script>
        new TomSelect("#role", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>


</div>
