<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Subsystem;

class DashboardController extends Controller
{
    public function index()
    {
        $subsystems = Subsystem::where('is_active', 1)->get();

        return view('core.dashboard', compact('subsystems'));
    }
}