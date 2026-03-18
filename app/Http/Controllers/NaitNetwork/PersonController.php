<?php

namespace App\Http\Controllers\NaitNetwork;

use App\Http\Controllers\Controller;
use App\Models\NaitNetworkPerson;
use App\Models\NaitNetworkRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $selectedRole = $request->get('role');

        $roles = NaitNetworkRole::orderBy('name')->get();

        $query = NaitNetworkPerson::with(['roles', 'socials.socialSelect'])
            ->where('user_id', Auth::id())
            ->orderBy('name');

        if ($selectedRole) {
            $query->whereHas('roles', function ($q) use ($selectedRole) {
                $q->where('naitnetwork_roles_tbl.id', $selectedRole);
            });
        }

        $people = $query->get();

        return view('subsystem.workspaces.naitnetwork.people.index', compact(
            'people',
            'roles',
            'selectedRole'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'summary' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            'roles' => 'nullable|array',
            'roles.*' => 'exists:naitnetwork_roles_tbl,id',

            'socials' => 'nullable|array',
            'socials.*.social_select_id' => 'nullable|exists:naitnetwork_socials_select_tbl,id',
            'socials.*.platform' => 'nullable|string|max:100',
            'socials.*.link' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $profilePicturePath = null;

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->storePublicly('naitnetwork/profile_pictures');
            }

            $person = NaitNetworkPerson::create([
                'user_id' => Auth::id(),
                'profile_picture' => $profilePicturePath,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'summary' => $validated['summary'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            if (!empty($validated['roles'])) {
                $person->roles()->sync($validated['roles']);
            }

            if (!empty($validated['socials'])) {
                $filteredSocials = collect($validated['socials'])
                    ->filter(function ($social) {
                        return !empty($social['social_select_id']) && !empty($social['link']);
                    })
                    ->map(function ($social) {
                        return [
                            'social_select_id' => $social['social_select_id'],
                            'platform' => $social['platform'] ?? null,
                            'url' => $social['link'],
                        ];
                    })
                    ->values()
                    ->toArray();

                if (!empty($filteredSocials)) {
                    $person->socials()->createMany($filteredSocials);
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Network person added successfully.');
    }

    public function update(Request $request, NaitNetworkPerson $person)
    {
        if ($person->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'summary' => 'nullable|string|max:255',
            'notes' => 'nullable|string',

            'roles' => 'nullable|array',
            'roles.*' => 'exists:naitnetwork_roles_tbl,id',

            'socials' => 'nullable|array',
            'socials.*.social_select_id' => 'nullable|exists:naitnetwork_socials_select_tbl,id',
            'socials.*.platform' => 'nullable|string|max:100',
            'socials.*.link' => 'nullable|string|max:1000',
        ]);



        DB::transaction(function () use ($request, $validated, $person) {
            $profilePicturePath = $person->profile_picture;

            if ($request->hasFile('profile_picture')) {
                if ($profilePicturePath && Storage::disk('public')->exists($profilePicturePath)) {
                    Storage::disk('public')->delete($profilePicturePath);
                }

                $profilePicturePath = $request->file('profile_picture')->storePublicly('naitnetwork/profile_pictures');
            }

            $person->update([
                'profile_picture' => $profilePicturePath,
                'name' => strtoupper(trim($validated['name'])),
                'slug' => Str::slug($validated['name']),
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'summary' => $validated['summary'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $person->roles()->sync($validated['roles'] ?? []);

            $person->socials()->delete();

            if (!empty($validated['socials'])) {
                $filteredSocials = collect($validated['socials'])
                    ->filter(function ($social) {
                        return filled($social['link']);
                    })
                    ->map(function ($social) {
                        return [
                            'social_select_id' => $social['social_select_id'] ?? null,
                            'platform' => $social['platform'] ?? null,
                            'url' => $social['link'],
                        ];
                    })
                    ->values()
                    ->toArray();

                if (!empty($filteredSocials)) {
                    $person->socials()->createMany($filteredSocials);
                }
            }
        });

        return redirect()->back()->with('success', 'Network person updated successfully.');
    }

    public function destroy($id)
    {
        $person = NaitNetworkPerson::where('user_id', Auth::id())->findOrFail($id);

        DB::transaction(function () use ($person) {
            if ($person->profile_picture && Storage::disk('public')->exists($person->profile_picture)) {
                Storage::disk('public')->delete($person->profile_picture);
            }

            $person->socials()->delete();
            $person->roles()->detach();
            $person->delete();
        });

        return redirect()->back()->with('success', 'Network person deleted successfully.');
    }
}
