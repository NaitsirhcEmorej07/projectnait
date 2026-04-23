<?php

namespace App\Http\Controllers\NaitFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NaitFileController extends Controller
{
    public function index()
    {
        $path = 'uploads/' . Auth::id();

        $files = Storage::disk('s3')->exists($path)
            ? Storage::disk('s3')->allFiles($path)
            : [];

        return view('subsystem.workspaces.naitfile.naitfile', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');

        // 1. original name
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        // 2. clean filename
        $cleanName = Str::slug($originalName);

        // 3. date format
        $date = now()->format('Y_m_d');

        // 4. base filename
        $baseName = $date . '_' . $cleanName;
        $fileName = $baseName . '.' . $extension;

        $path = 'uploads/' . Auth::id();

        // 5. prevent overwrite (Windows style)
        $counter = 1;
        while (Storage::disk('s3')->exists($path . '/' . $fileName)) {
            $fileName = $baseName . '(' . $counter . ').' . $extension;
            $counter++;
        }

        // 6. upload
        Storage::disk('s3')->putFileAs(
            $path,
            $file,
            $fileName
        );

        return back()->with('success', 'File uploaded successfully!');
    }

    public function delete(Request $request)
    {
        Storage::disk('s3')->delete($request->file);

        return back()->with('success', 'File deleted successfully!');
    }
}
