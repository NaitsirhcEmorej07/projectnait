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
        class="mb-6 flex flex-col sm:flex-row gap-2">
        @csrf

        <input type="file" name="file" class="border p-2 bg-white w-full text-sm rounded">

        <button class="bg-blue-500 text-white px-4 py-2 rounded w-full sm:w-auto">
            Upload
        </button>
    </form>

    <!-- FILE GRID -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        @forelse($files as $file)
            @php
                $url = Storage::disk('s3')->url($file);
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
                <p class="text-xs break-all">
                    {{ \Illuminate\Support\Str::limit($file, 30) }}
                </p>

                <!-- DELETE -->
                <form action="{{ route('naitfile.delete') }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="file" value="{{ $file }}">

                    <button class="text-red-500 text-xs" onclick="return confirm('Delete this file?')">
                        Delete
                    </button>
                </form>

            </div>

        @empty
            <p>No files found.</p>
        @endforelse

    </div>
</div>
