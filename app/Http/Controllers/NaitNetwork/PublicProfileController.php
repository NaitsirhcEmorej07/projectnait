<?php

namespace App\Http\Controllers\NaitNetwork;

use App\Http\Controllers\Controller;
use App\Models\NaitNetworkPerson;

class PublicProfileController extends Controller
{
    public function show($slug, $token)
    {
        $person = NaitNetworkPerson::with([
                'roles',
                'socials.socialSelect'
            ])
            ->where('slug', $slug)
            ->where('public_token', $token)
            ->firstOrFail();

        if (!$person->isPubliclyViewable()) {
            abort(403, 'This profile is not available for public viewing.');
        }

        return view('subsystem.workspaces.naitnetwork.public_profile.index', compact('person'));
    }
}