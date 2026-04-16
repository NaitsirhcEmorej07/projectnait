<div x-data="{
    openAddNoteModal: {{ $errors->any() ? 'true' : 'false' }},
    openViewNoteModal: false,

    selectedNote: {
        id: '',
        title: '',
        content: '',
        color: '#6366F1'
    },

    init() {
        this.$watch('selectedNote', (note) => {
            this.$nextTick(() => {
                if (this.$refs.editEditor) {
                    this.$refs.editEditor.innerHTML = note.content || '';
                    this.$refs.editHidden.value = note.content || '';
                }
            });
        });
    }
}">
    <div class="space-y-4">



        {{-- NOTES LIST --}}
        <div id="notes-list" class="bg-white shadow-sm rounded-2xl p-4 sm:p-6">

            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Notes List
                </h2>

                <button type="button" @click="openAddNoteModal = true"
                    class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition">
                    <i class="pi pi-plus text-lg"></i>
                </button>
            </div>

            @forelse ($notes as $note)
                <div class="flex items-stretch mb-3 group cursor-move" data-id="{{ $note->id }}"
                    @click='
                                selectedNote = {
                                    id: @json($note->id),
                                    title: @json($note->title),
                                    content: @json($note->content),
                                    color: @json($note->color ?? '#6366F1')
                                };

                                openViewNoteModal = true;

                                $nextTick(() => {
                                    if ($refs.editEditor) {
                                        $refs.editEditor.innerHTML = selectedNote.content || "";
                                        $refs.editHidden.value = selectedNote.content || "";
                                    }
                                });
                            '>


                    {{-- LEFT COLOR MARK --}}
                    <div class="w-3 rounded-l-md cursor-grab drag-handle"
                        style="background-color: {{ $note->color ?? '#6366F1' }}">
                    </div>

                    {{-- NOTE CARD --}}
                    <div class="flex-1 border border-gray-200 border-l-0 rounded-r-xl p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900 truncate">
                                {{ $note->title }}
                            </h3>

                            <span class="text-xs text-gray-400 whitespace-nowrap ml-3">
                                {{ $note->created_at->format('M d') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl text-center text-sm text-gray-500 py-6">
                    No notes yet.
                </div>
            @endforelse

            @if (method_exists($notes, 'links'))
                <div class="mt-2">
                    {{ $notes->links() }}
                </div>
            @endif


            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

        </div>

    </div>

    {{-- ADD NOTE MODAL --}}
    <template x-teleport="body">
        <div x-show="openAddNoteModal" x-transition.opacity
            class="fixed inset-0 z-[9999] bg-black/50 overflow-y-auto p-3" style="display: none;">

            <div class="absolute inset-0" @click="openAddNoteModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-auto my-10 p-6">

                <button type="button"
                    @click="if (confirm('This note cannot be save. Close anyway?')) openAddNoteModal = false"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="pi pi-times text-lg"></i>
                </button>

                <form action="{{ route('naitnote.store') }}" method="POST" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                            Please check the form fields.
                        </div>
                    @endif

                    {{-- TITLE --}}
                    <div>
                        <input type="text" name="title" placeholder="Title" value="{{ old('title') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            required>
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONTENT --}}
                    <div>
                        <div contenteditable="true"
                            class="w-full border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm leading-relaxed min-h-[300px] p-3 outline-none"
                            x-ref="addEditor" @input="$refs.addHidden.value = $el.innerHTML">
                        </div>

                        <input type="hidden" name="content" x-ref="addHidden">
                        @error('content')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- COLOR PICKER --}}
                    <div x-data="{ selectedColor: '{{ old('color', '#6366F1') }}' }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Note Color
                        </label>

                        @php
                            $colors = [
                                '#6366F1', // indigo
                                '#22C55E', // green
                                '#EF4444', // red
                                '#F59E0B', // orange
                                '#3B82F6', // blue
                                '#A855F7', // purple

                                '#EAB308', // yellow
                                '#000000', // black
                                '#6B7280', // gray
                            ];
                        @endphp

                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="sr-only"
                                        x-model="selectedColor">

                                    <div class="h-6 w-6 rounded-full transition flex items-center justify-center"
                                        :class="selectedColor === '{{ $color }}'
                                            ?
                                            'ring-2 ring-offset-1 ring-gray-900 scale-110' :
                                            'ring-1 ring-gray-200'"
                                        style="background-color: {{ $color }}">

                                        <template x-if="selectedColor === '{{ $color }}'">
                                            <i class="pi pi-check text-white text-[9px]"></i>
                                        </template>

                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('color')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex justify-end items-center gap-2 pt-2">

                        {{-- CANCEL --}}
                        <button type="button" @click="openAddNoteModal = false"
                            class="h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                            <i class="pi pi-times text-sm"></i>
                        </button>

                        {{-- SAVE --}}
                        <button type="submit"
                            class="h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition">
                            <i class="pi pi-check text-sm"></i>
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </template>

    {{-- VIEW / UPDATE / DELETE NOTE MODAL --}}
    <template x-teleport="body">
        <div x-show="openViewNoteModal" x-transition.opacity
            class="fixed inset-0 z-[9999] bg-black/50 overflow-y-auto p-3" style="display: none;">

            <div class="absolute inset-0"></div>

            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-auto my-10 p-6">

                <button type="button"
                    @click="confirm('This note cannot be updated. Close anyway?') && (openViewNoteModal = false)"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    <i class="pi pi-times text-lg"></i>
                </button>

                {{-- UPDATE FORM --}}
                <form :action="`/naitnote/${selectedNote.id}/update`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <input type="text" name="title" x-model="selectedNote.title"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            required>
                    </div>

                    <div>
                        <div contenteditable="true"
                            class="w-full border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm leading-relaxed min-h-[300px] p-3 outline-none"
                            x-ref="editEditor" @input="$refs.editHidden.value = $el.innerHTML">
                        </div>

                        <input type="hidden" name="content" x-ref="editHidden">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Note Color
                        </label>

                        @php
                            $colors = [
                                '#6366F1', // indigo
                                '#22C55E', // green
                                '#EF4444', // red
                                '#F59E0B', // orange
                                '#3B82F6', // blue
                                '#A855F7', // purple

                                '#EAB308', // yellow
                                '#000000', // black
                                '#6B7280', // gray
                            ];
                        @endphp

                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}"
                                        class="sr-only" x-model="selectedNote.color">

                                    <div class="h-6 w-6 rounded-full transition flex items-center justify-center"
                                        :class="selectedNote.color === '{{ $color }}' ?
                                            'ring-2 ring-offset-1 ring-gray-900 scale-110' :
                                            'ring-1 ring-gray-200'"
                                        style="background-color: {{ $color }}">

                                        <template x-if="selectedNote.color === '{{ $color }}'">
                                            <i class="pi pi-check text-white text-[9px]"></i>
                                        </template>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-2 pt-2">

                        {{-- UPDATE --}}
                        <button type="submit"
                            class="h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition">
                            <i class="pi pi-check text-sm"></i>
                        </button>


                        {{-- DELETE --}}
                        <button type="button"
                            @click="
                                        if (confirm('Delete this note?')) {
                                            const form = document.createElement('form');
                                            form.method = 'POST';
                                            form.action = `/naitnote/${selectedNote.id}/delete`;

                                            const csrf = document.createElement('input');
                                            csrf.type = 'hidden';
                                            csrf.name = '_token';
                                            csrf.value = '{{ csrf_token() }}';

                                            const method = document.createElement('input');
                                            method.type = 'hidden';
                                            method.name = '_method';
                                            method.value = 'DELETE';

                                            form.appendChild(csrf);
                                            form.appendChild(method);
                                            document.body.appendChild(form);
                                            form.submit();
                                        }
                                    "
                            class="h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:text-red-600 hover:bg-red-50 transition">
                            <i class="pi pi-trash text-sm"></i>
                        </button>



                    </div>
                </form>

            </div>
        </div>
    </template>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let el = document.getElementById('notes-list');

            Sortable.create(el, {
                animation: 150,

                handle: '.drag-handle', // 🔥 IMPORTANT

                onEnd: function() {
                    let order = [];

                    document.querySelectorAll('#notes-list [data-id]').forEach((item, index) => {
                        order.push({
                            id: item.dataset.id,
                            position: index + 1
                        });
                    });

                    fetch('/naitnote/reorder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    });
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                document.execCommand('bold');
            }
        });
    </script>
</div>
