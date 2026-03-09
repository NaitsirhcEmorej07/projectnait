<?php

namespace App\Http\Controllers\NaitNetwork;

use App\Http\Controllers\Controller;
use App\Models\NaitNetworkRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $roles = NaitNetworkRole::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        return view('subsystem.workspaces.naitnetwork.roles.index', compact('roles', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        NaitNetworkRole::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Role added successfully.');
    }

    public function update(Request $request, NaitNetworkRole $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(NaitNetworkRole $role)
    {
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
