<div x-data="{ open: false, openAddNetworkModal: false }">

    <div class="space-y-4">

        {{-- ROLE SEARCH --}}
        <div class="bg-white shadow-sm rounded-2xl p-5 sm:p-6">

            <div class="mb-3 flex items-center justify-between">

                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                    What are you looking for?
                </h2>

                <div class="relative">

                    <!-- SETTINGS BUTTON -->
                    <button type="button" @click="open = !open"
                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition">
                        <i class="pi pi-cog text-lg"></i>
                    </button>

                    <!-- DROPDOWN -->
                    <div x-show="open" x-transition @click.outside="open = false"
                        class="absolute right-0 mt-1 w-40 bg-white border rounded-xl shadow-lg z-40"
                        style="display: none;">

                        <!-- ADD NETWORK -->
                        <button type="button" @click="openAddNetworkModal = true; open = false"
                            class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <i class="pi pi-users"></i>
                            Add Network
                        </button>

                        <!-- MANAGE ROLES -->
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

        {{-- PEOPLE LIST --}}
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

                            <div class="flex justify-center gap-1 mt-2 whitespace-nowrap overflow-hidden">
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

    </div>

    {{-- ADD NETWORK MODAL --}}
    <template x-teleport="body">
        <div x-show="openAddNetworkModal" x-transition.opacity
            class="fixed inset-0 z-[9999] bg-black/50 overflow-y-auto" style="display: none;">

            <!-- BACKDROP -->
            <div class="absolute inset-0" @click="openAddNetworkModal = false"></div>

            <!-- MODAL -->
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto my-10 p-6" x-data="{ imagePreview: null }">

                <!-- CLOSE BUTTON -->
                <button @click="openAddNetworkModal = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="pi pi-times text-lg"></i>
                </button>

                <!-- BODY -->
                <form class="space-y-4">

                    <!-- PROFILE IMAGE -->
                    <div class="flex justify-center mt-2">
                        <div class="relative">

                            <div
                                class="h-24 w-24 rounded-full bg-gray-100 overflow-hidden flex items-center justify-center">
                                <!-- DEFAULT -->
                                <div x-show="!imagePreview"
                                    class="text-gray-400 text-2xl flex items-center justify-center w-full h-full">
                                    <i class="pi pi-user"></i>
                                </div>

                                <!-- PREVIEW -->
                                <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                            </div>

                            <!-- CAMERA -->
                            <label
                                class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center cursor-pointer shadow">
                                <i class="pi pi-camera text-xs"></i>

                                <input type="file" accept="image/*" class="hidden"
                                    @change="
                                    const file = $event.target.files[0];
                                    if (file) imagePreview = URL.createObjectURL(file);
                                ">
                            </label>

                        </div>
                    </div>

                    <!-- ROLE SELECTION (MINIMAL) -->
                    <div>
                        <select id="add_network_roles" name="roles[]" multiple
                            class="w-full text-sm border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 px-0 py-2 bg-transparent">

                            @foreach ($roles ?? [] as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- NAME -->
                    <div>
                        <input type="text" name="name" placeholder="Name"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <input type="email" name="email" placeholder="Email"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- PHONE -->
                    <div>
                        <input type="text" name="phone" placeholder="Phone"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- SUMMARY -->
                    <div>
                        <input type="text" name="summary" placeholder="Summary"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <!-- NOTES + SOCIALS GROUP -->
                    <div class="space-y-2">

                        <!-- NOTES -->
                        <div>
                            <textarea rows="3" name="notes" placeholder="Notes"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                        </div>

                        <!-- SOCIALS -->
                        <div x-data="{ socials: [], socialOptions: window.socialOptions || [] }" class="space-y-2">

                            <!-- ADD BUTTON -->
                            <button type="button"
                                @click="socials.push({ social_select_id: '', platform: '', icon: '', link: '', open: false })"
                                class="text-xs text-indigo-600 hover:text-indigo-700">
                                + Add social
                            </button>

                            <!-- LIST -->
                            <template x-for="(social, index) in socials" :key="index">
                                <div class="flex items-center gap-2 relative">

                                    <!-- PLATFORM ICON PICKER -->
                                    <div class="relative">

                                        <!-- BUTTON -->
                                        <button type="button" @click="social.open = !social.open"
                                            class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-indigo-600">

                                            <template x-if="!social.icon">
                                                <i class="pi pi-globe text-xs"></i>
                                            </template>

                                            <template x-if="social.icon">
                                                <i :class="social.icon + ' text-xs'"></i>
                                            </template>

                                        </button>

                                        <!-- HIDDEN INPUTS -->
                                        <input type="hidden" :name="`socials[${index}][social_select_id]`"
                                            :value="social.social_select_id">
                                        <input type="hidden" :name="`socials[${index}][platform]`"
                                            :value="social.platform">

                                        <!-- DROPDOWN -->
                                        <div x-show="social.open" @click.outside="social.open = false" x-transition
                                            class="absolute z-50 mt-1 bg-white border rounded-xl shadow p-2 flex gap-2">

                                            <template x-for="option in socialOptions" :key="option.id">
                                                <button type="button"
                                                    @click="
                                    social.social_select_id = option.id;
                                    social.platform = option.code;
                                    social.icon = option.icon;
                                    social.open = false;
                                "
                                                    class="text-gray-500 hover:text-indigo-600">
                                                    <i :class="option.icon"></i>
                                                </button>
                                            </template>

                                        </div>

                                    </div>

                                    <!-- LINK -->
                                    <input type="text" x-model="social.link" :name="`socials[${index}][link]`"
                                        placeholder="Link"
                                        class="flex-1 text-xs border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 bg-transparent px-0 py-1">

                                    <!-- REMOVE -->
                                    <button type="button" @click="socials.splice(index, 1)"
                                        class="text-gray-400 hover:text-red-500 text-xs">
                                        ✕
                                    </button>

                                </div>
                            </template>

                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="openAddNetworkModal = false"
                            class="px-4 py-2 text-sm rounded-xl bg-gray-100 hover:bg-gray-200">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                            Save
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </template>

    <script>
        new TomSelect("#role", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#add_network_roles', {
                plugins: ['remove_button'],
                create: false,
                placeholder: 'Add roles...',
                hideSelected: true,

                render: {
                    item: function(data, escape) {
                        return `<div class="inline-flex items-center gap-1 text-[12px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 mr-1">
                                ${escape(data.text)}
                            </div>`;
                    }
                }
            });
        });
    </script>

    <script>
        window.socialOptions = @json($socials);
    </script>

</div>
