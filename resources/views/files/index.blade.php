<x-app-layout>
    <div class="p-4">
        <!-- SUCCESS MESSAGE -->
        @if (session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- UPLOAD FORM -->
        <form action="/files/upload" method="POST" enctype="multipart/form-data"
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
                @endphp

                <div class="bg-white p-3 rounded shadow">

                    <!-- PREVIEW -->
                    @if (\Illuminate\Support\Str::endsWith($file, ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ $url }}" class="w-full h-32 object-cover mb-2 rounded">
                    @else
                        <div class="h-32 flex items-center justify-center bg-gray-200 mb-2 rounded">
                            <a href="{{ $url }}" target="_blank" class="text-blue-500 text-sm">Open File</a>
                        </div>
                    @endif

                    <!-- FILE NAME -->
                    <p class="text-xs break-all">{{ $file }}</p>

                    <!-- DELETE -->
                    <form action="/files/delete" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="file" value="{{ $file }}">
                        <button class="text-red-500 text-xs">Delete</button>
                    </form>

                </div>

            @empty
                <p>No files found.</p>
            @endforelse

        </div>

</x-app-layout>
