<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Subsystem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $subsystems = Subsystem::where('user_id', Auth::id())
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('core.dashboard', compact('subsystems'));
    }
}
