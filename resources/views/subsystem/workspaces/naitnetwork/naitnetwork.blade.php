<div x-data="{
    open: false,
    openAddNetworkModal: {{ $errors->any() ? 'true' : 'false' }},
    openEditNetworkModal: false,
    openDeleteModal: false,
    deleteId: null,
    socialOptions: window.socialOptions || [],
    selectedPerson: {
        id: '',
        name: '',
        email: '',
        phone: '',
        summary: '',
        notes: '',
        profile_picture: '',
        roles: []
    },
    editSocials: []
}">
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
                    @php
                        $personData = [
                            'id' => $person->id,
                            'name' => $person->name,
                            'slug' => $person->slug,
                            'public_token' => $person->public_token,
                            'email' => $person->email,
                            'phone' => $person->phone,
                            'summary' => $person->summary,
                            'notes' => $person->notes,
                            'profile_picture' => $person->profile_picture,
                            'roles' => $person->roles->pluck('id')->values()->toArray(),
                            'socials' => $person->socials
                                ->map(function ($social) {
                                    return [
                                        'social_select_id' => $social->social_select_id,
                                        'platform' => $social->platform,
                                        'icon' => $social->socialSelect->icon ?? '',
                                        'link' => $social->url,
                                        'open' => false,
                                    ];
                                })
                                ->values()
                                ->toArray(),
                        ];
                    @endphp

                    <div class="bg-white shadow-sm rounded-xl p-4 hover:shadow-md transition cursor-pointer"
                        data-person='@json($personData)'
                        @click="
                                    const person = JSON.parse($el.dataset.person);

                                    selectedPerson = {
                                        id: person.id,
                                        name: person.name ?? '',
                                        email: person.email ?? '',
                                        phone: person.phone ?? '',
                                        summary: person.summary ?? '',
                                        notes: person.notes ?? '',
                                        profile_picture: person.profile_picture ?? '',
                                        roles: person.roles ?? []
                                    };

                                    deleteId = person.id;

                                    editSocials = (person.socials ?? []).map((social, i) => {
                                        let matched = socialOptions.find(option =>
                                            (option.code ?? '')
.toLowerCase() === (social.platform ?? '').toLowerCase()
                                        );

                                        return {
                                            ...social,
                                            icon: social.icon || (matched ? matched.icon : ''),
                                            _key: Date.now() + i + Math.random()
                                        };
                                    });

                                    openEditNetworkModal = true;

                                    setTimeout(() => {
                                    window.initEditRoleSelect(selectedPerson.roles ?? []);
                                }, 100);
                                ">

                        <div class="flex flex-col items-center text-center">

                            <div class="h-12 w-12 rounded-full overflow-hidden mb-2">
                                @if ($person->profile_picture)
                                    <img src="{{ Storage::url($person->profile_picture) }}" alt="{{ $person->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ strtoupper(substr($person->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h4 class="text-sm font-semibold text-gray-900 truncate w-full text-center">
                                {{ strtoupper($person->name) }}
                            </h4>

                            @if ($person->phone)
                                <p class="text-[11px] text-gray-500 mt-0">
                                    {{ $person->phone }}
                                </p>
                            @endif

                            @if ($person->summary)
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-tight min-h-[2rem]">
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
            class="fixed inset-0 z-[9999] bg-black/50 overflow-y-auto p-3" style="display: none;">

            <div class="absolute inset-0" @click="openAddNetworkModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto my-10 p-6"
                x-data="{
                    imagePreview: null,
                    socials: {{ json_encode(old('socials', [])) }},
                    socialOptions: window.socialOptions || []
                }">

                <button type="button" @click="openAddNetworkModal = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="pi pi-times text-lg"></i>
                </button>

                <form action="{{ route('naitnetwork.people.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                            Please check the form fields.
                        </div>
                    @endif

                    <div class="flex justify-center mt-2">
                        <div class="relative">

                            <div
                                class="h-24 w-24 rounded-full bg-gray-100 overflow-hidden flex items-center justify-center">
                                <div x-show="!imagePreview"
                                    class="text-gray-400 text-2xl flex items-center justify-center w-full h-full">
                                    <i class="pi pi-user"></i>
                                </div>

                                <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                            </div>

                            <label
                                class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center cursor-pointer shadow">
                                <i class="pi pi-camera text-xs"></i>

                                <input type="file" name="profile_picture" accept="image/*" class="hidden"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) imagePreview = URL.createObjectURL(file);
                                    ">
                            </label>
                        </div>
                    </div>

                    @error('profile_picture')
                        <p class="text-xs text-red-500 text-center">{{ $message }}</p>
                    @enderror

                    <div>
                        <select id="add_network_roles" name="roles[]" multiple
                            class="w-full text-sm border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 px-0 py-2 bg-transparent">
                            @foreach ($roles ?? [] as $role)
                                <option value="{{ $role->id }}"
                                    {{ collect(old('roles', []))->contains($role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        @error('roles.*')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="text" name="summary" placeholder="Summary" value="{{ old('summary') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('summary')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">

                        <div>
                            <textarea rows="3" name="notes" placeholder="Notes"
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">

                            <button type="button"
                                @click="socials.push({ social_select_id: '', platform: '', icon: '', link: '', open: false })"
                                class="text-xs text-indigo-600 hover:text-indigo-700">
                                + Add social
                            </button>

                            <template x-for="(social, index) in socials" :key="index">
                                <div class="flex items-center gap-2 relative">

                                    <div class="relative">
                                        <button type="button" @click="social.open = !social.open"
                                            class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-indigo-600">

                                            <template x-if="!social.icon">
                                                <i class="pi pi-globe text-xs"></i>
                                            </template>

                                            <template x-if="social.icon">
                                                <i :class="social.icon + ' text-xs'"></i>
                                            </template>

                                        </button>

                                        <input type="hidden" :name="`socials[${index}][social_select_id]`"
                                            :value="social.social_select_id">
                                        <input type="hidden" :name="`socials[${index}][platform]`"
                                            :value="social.platform">

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

                                    <input type="text" x-model="social.link" :name="`socials[${index}][link]`"
                                        placeholder="Link"
                                        class="flex-1 text-xs border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 bg-transparent px-0 py-1">

                                    <button type="button" @click="socials.splice(index, 1)"
                                        class="text-gray-400 hover:text-red-500 text-xs">
                                        ✕
                                    </button>

                                </div>
                            </template>

                            @error('socials')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            @error('socials.*.social_select_id')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            @error('socials.*.platform')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            @error('socials.*.link')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

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

    {{-- EDIT NETWORK MODAL --}}
    <template x-teleport="body">
        <div x-show="openEditNetworkModal" x-transition.opacity
            class="fixed inset-0 z-[9999] bg-black/50 overflow-y-auto p-3" style="display: none;">

            <div class="absolute inset-0" @click="openEditNetworkModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto my-10 p-6"
                x-data="{ imagePreview: null }">

                <button type="button" @click="openEditNetworkModal = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="pi pi-times text-lg"></i>
                </button>

                <form :action="`/naitnetwork/people/${selectedPerson.id}`" method="POST"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex justify-center mt-2">
                        <div class="relative">

                            <div
                                class="h-24 w-24 rounded-full bg-gray-100 overflow-hidden flex items-center justify-center">

                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                </template>

                                <template x-if="!imagePreview">
                                    <div>
                                        <template x-if="selectedPerson.profile_picture">
                                            <img src="{{ Storage::url('') }}"
                                                :src="'{{ Storage::url('') }}' + selectedPerson.profile_picture"
                                                class="w-full h-full object-cover">
                                        </template>

                                        <template x-if="!selectedPerson.profile_picture">
                                            <div
                                                class="w-full h-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                <span
                                                    x-text="selectedPerson.name ? selectedPerson.name.charAt(0).toUpperCase() : '?'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!imagePreview && !selectedPerson.profile_picture">
                                    <div class="text-gray-400 text-2xl flex items-center justify-center w-full h-full">
                                        <i class="pi pi-user"></i>
                                    </div>
                                </template>

                            </div>

                            <label
                                class="absolute bottom-0 right-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center cursor-pointer shadow">
                                <i class="pi pi-camera text-xs"></i>

                                <input type="file" name="profile_picture" accept="image/*" class="hidden"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) imagePreview = URL.createObjectURL(file);
                                    ">
                            </label>
                        </div>
                    </div>

                    <div>
                        <select id="edit_network_roles" name="roles[]" multiple
                            class="w-full text-sm border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 px-0 py-2 bg-transparent">
                            @foreach ($roles ?? [] as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <input type="text" name="name" x-model="selectedPerson.name" placeholder="Name"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <input type="email" name="email" x-model="selectedPerson.email" placeholder="Email"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <input type="text" name="phone" x-model="selectedPerson.phone" placeholder="Phone"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <input type="text" name="summary" x-model="selectedPerson.summary" placeholder="Summary"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <textarea rows="3" name="notes" x-model="selectedPerson.notes" placeholder="Notes"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                    </div>

                    <div class="space-y-1">

                        <button type="button"
                            @click="editSocials.push({ social_select_id: '', platform: '', icon: '', link: '', open: false, _key: Date.now() + Math.random() })"
                            class="text-xs text-indigo-600 hover:text-indigo-700">
                            + Add social
                        </button>

                        <template x-if="editSocials.length === 0">
                            <p class="text-xs text-gray-400">No social links yet.</p>
                        </template>

                        <template x-for="(social, index) in editSocials" :key="social._key">
                            <div class="flex items-center gap-2 relative">

                                <div class="relative">
                                    <button type="button" @click="social.open = !social.open"
                                        class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-indigo-600">

                                        <template x-if="!social.icon">
                                            <i class="pi pi-globe text-xs"></i>
                                        </template>

                                        <template x-if="social.icon">
                                            <i :class="social.icon + ' text-xs'"></i>
                                        </template>

                                    </button>

                                    <input type="hidden" :name="`socials[${index}][social_select_id]`"
                                        :value="social.social_select_id">
                                    <input type="hidden" :name="`socials[${index}][platform]`"
                                        :value="social.platform">

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

                                <input type="text" x-model="social.link" :name="`socials[${index}][link]`"
                                    placeholder="Link"
                                    class="flex-1 text-xs border-0 border-b border-gray-300 focus:ring-0 focus:border-indigo-500 bg-transparent px-0 py-1">

                                <button type="button" @click="editSocials.splice(index, 1)"
                                    class="text-gray-400 hover:text-red-500 text-xs">
                                    ✕
                                </button>

                            </div>
                        </template>

                    </div>

                    <div class="flex justify-center items-center gap-2 pt-4">

                        <!-- Update -->
                        <button type="submit"
                            class="p-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition">
                            <i class="pi pi-check text-sm"></i>
                        </button>

                        <!-- Delete -->
                        <button type="submit" form="delete-person-form"
                            class="p-2 text-gray-700 hover:text-red-600 hover:bg-gray-100 rounded-lg transition">
                            <i class="pi pi-trash text-sm"></i>
                        </button>

                        <!-- Share -->
                        <button type="button"
                            @click="
                                        fetch(`/naitnetwork/people/${selectedPerson.id}/share`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                selectedPerson.public_token = data.token;

                                                if (navigator.share) {
                                                    navigator.share({
                                                        title: selectedPerson.name,
                                                        text: `Check out ${selectedPerson.name}'s profile`,
                                                        url: data.url
                                                    });
                                                } else {
                                                    navigator.clipboard.writeText(data.url);
                                                    alert('Link copied! (Sharing not supported)');
                                                }

                                            } else {
                                                alert('Failed to generate public link.');
                                            }
                                        })
                                        .catch(() => {
                                            alert('Something went wrong while sharing.');
                                        });
                                    "
                            class="p-2 text-gray-700 hover:text-emerald-600 hover:bg-gray-100 rounded-lg transition">
                            <i class="pi pi-share-alt text-sm"></i>
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </template>

    <form id="delete-person-form" :action="`/naitnetwork/people/${deleteId}`" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('#role')) {
                new TomSelect("#role", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('#add_network_roles')) {
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
            }
        });
    </script>

    <script>
        window.socialOptions = @json($socials ?? []);
    </script>


    <script>
        window.initEditRoleSelect = function(selectedRoles = []) {
            const el = document.querySelector('#edit_network_roles');
            if (!el) return;

            if (window.editTom) {
                window.editTom.destroy();
            }

            window.editTom = new TomSelect(el, {
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

            window.editTom.setValue(selectedRoles);
        };
    </script>

</div>
