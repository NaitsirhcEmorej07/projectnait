<?php

namespace App\Http\Controllers\NaitFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NaitFileController extends Controller
{
    public function index()
    {
        $files = Storage::disk('s3')->allFiles();

        return view('subsystem.workspaces.naitfile.naitfile', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        Storage::disk('s3')->put('uploads', $request->file('file'));

        return back();
    }

    public function delete(Request $request)
    {
        Storage::disk('s3')->delete($request->file);

        return back();
    }
}
