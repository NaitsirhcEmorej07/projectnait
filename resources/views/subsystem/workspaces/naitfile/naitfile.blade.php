<div class="">

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @error('file')
        <div class="text-red-500 text-sm mb-2">{{ $message }}</div>
    @enderror

    <!-- UPLOAD -->
    <form action="{{ route('naitfile.upload') }}" method="POST" enctype="multipart/form-data"
        class="mb-6 flex items-center gap-2">
        @csrf

        <input type="file" name="file" class="border p-2 bg-white text-sm rounded flex-1 min-w-0">

        <button
            class="bg-black text-white px-3 py-2 rounded-md 
               hover:bg-gray-800 transition shadow text-sm flex-shrink-0">
            Upload
        </button>
    </form>

    <!-- FILE GRID -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        @forelse($files as $file)
            @php
                $url = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(5));
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            @endphp

            <div class="bg-white p-3 rounded shadow">

                <!-- PREVIEW -->
                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                    <img src="{{ $url }}" class="w-full h-32 object-cover mb-2 rounded">
                @else
                    <div class="h-32 flex items-center justify-center bg-gray-200 mb-2 rounded">
                        <a href="{{ $url }}" target="_blank" class="text-blue-500 text-sm">Open File</a>
                    </div>
                @endif

                <!-- FILE NAME -->
                <p class="text-xs break-all mt-1">
                    {{ \Illuminate\Support\Str::limit($file, 30) }}
                </p>

                <!-- ACTIONS -->
                <div class="flex justify-between items-center mt-2">

                    <!-- DELETE -->
                    <form action="{{ route('naitfile.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="file" value="{{ $file }}">

                        <button class="text-red-500 text-xs" onclick="return confirm('Delete this file?')">
                            Delete
                        </button>
                    </form>

                    <!-- COPY BUTTON -->
                    <button type="button" onclick="copyToClipboard('{{ $url }}')"
                        class="text-gray-500 hover:text-blue-500 text-sm" title="Copy URL">
                        <i class="pi pi-copy"></i>
                    </button>

                </div>

            </div>

        @empty
            <p>No files found.</p>
        @endforelse

    </div>
</div>

<!-- 🔥 TOAST NOTIFICATION -->
<div id="copyToast" class="fixed bottom-5 right-5 bg-black text-white text-sm px-4 py-2 rounded shadow hidden">
    Copied to clipboard!
</div>

<!-- COPY SCRIPT -->
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast();
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }

    function showToast() {
        const toast = document.getElementById('copyToast');

        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 2000);
    }
</script>
