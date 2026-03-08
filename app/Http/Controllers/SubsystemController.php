<?php

namespace App\Http\Controllers;

use App\Models\Subsystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubsystemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subsystem_tbl,code',
            'description' => 'nullable|string',
            'route' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        Subsystem::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'code' => strtolower(str_replace(' ', '', $validated['code'])),
            'description' => $validated['description'] ?? null,
            'route' => strtolower(str_replace(' ', '', $validated['code'])) . '.index',
            'icon' => $validated['icon'] ?? null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Subsystem created successfully.');
    }

    public function edit($id)
    {
        $subsystem = Subsystem::findOrFail($id);

        return view('profile.subsystem-edit', compact('subsystem'));
    }

    public function update(Request $request, $id)
    {
        $subsystem = Subsystem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subsystem_tbl,code,' . $subsystem->id,
            'description' => 'nullable|string',
            'route' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $subsystem->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'route' => $validated['route'],
            'icon' => $validated['icon'] ?? null,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Subsystem updated successfully.');
    }

    public function toggle($id)
    {
        $subsystem = Subsystem::findOrFail($id);

        $subsystem->update([
            'is_active' => !$subsystem->is_active,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Subsystem status updated successfully.');
    }

    public function destroy($id)
    {
        $subsystem = Subsystem::findOrFail($id);
        $subsystem->delete();

        return redirect()->route('profile.edit')->with('success', 'Subsystem deleted successfully.');
    }

    public function landing($code)
    {
        $subsystem = Subsystem::where('user_id', Auth::id())
            ->where('code', $code)
            ->where('is_active', 1)
            ->firstOrFail();

        return view('subsystem.landing', compact('subsystem'));
    }
}
