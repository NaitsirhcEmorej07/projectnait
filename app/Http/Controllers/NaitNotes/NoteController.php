<?php

namespace App\Http\Controllers\NaitNotes;

use App\Http\Controllers\Controller;
use App\Models\NaitNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
        ]);

        NaitNote::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'color' => $validated['color'] ?? '#6366F1',
        ]);

        return redirect()
            ->route('subsystem.landing', 'naitnote')
            ->with('success', 'Note added successfully.');
    }

    public function update(Request $request, NaitNote $note)
    {
        abort_if($note->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
        ]);

        $note->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'color' => $validated['color'] ?? '#6366F1',
        ]);

        return redirect()
            ->route('subsystem.landing', 'naitnote')
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(NaitNote $note)
    {
        abort_if($note->user_id !== Auth::id(), 403);

        $note->delete();

        return redirect()
            ->route('subsystem.landing', 'naitnote')
            ->with('success', 'Note deleted successfully.');
    }
}