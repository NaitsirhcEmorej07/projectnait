<?php

namespace App\Http\Controllers\NaitNetwork;

use App\Http\Controllers\Controller;
use App\Models\NaitNetworkPerson;
use App\Models\NaitNetworkRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    public function index(Request $request)
{
    $selectedRole = $request->get('role');

    $roles = NaitNetworkRole::orderBy('name')->get();

    $query = NaitNetworkPerson::with(['roles', 'socials'])
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
}


