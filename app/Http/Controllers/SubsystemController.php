<?php

namespace App\Http\Controllers;

use App\Models\NaitNetworkRole;
use App\Models\NaitNetworkPerson;
use App\Models\NaitNetworkSocialSelect;
use App\Models\NaitCalendarEvent;

use App\Models\NaitNote;

use App\Models\Subsystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


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

    public function landing(Request $request, $code)
    {
        $subsystem = Subsystem::where('user_id', Auth::id())
            ->where('code', $code)
            ->where('is_active', 1)
            ->firstOrFail();

        $data = [
            'subsystem' => $subsystem,
            'roles' => [],
            'people' => collect(),
            'selectedRole' => null,
        ];

        $method = 'handle' . Str::studly($code);

        if (method_exists($this, $method)) {
            $data = array_merge($data, $this->$method($request, $subsystem));
        }

        return view('subsystem.landing', $data);
    }


    private function handleNaitnetwork(Request $request, $subsystem)
    {
        $roles = NaitNetworkRole::orderBy('name')->get();
        $selectedRole = $request->get('role');

        $query = NaitNetworkPerson::with('roles')
            ->where('user_id', Auth::id());

        if ($selectedRole) {
            $query->whereHas('roles', function ($q) use ($selectedRole) {
                $q->where('naitnetwork_roles_select_tbl.id', $selectedRole);
            });
        }

        $people = $query->paginate(10);

        $socials = NaitNetworkSocialSelect::where('is_active', 1)
            ->orderBy('name')
            ->get();

        return [
            'roles' => $roles,
            'people' => $people,
            'selectedRole' => $selectedRole,
            'socials' => $socials,
        ];
    }


    private function handleNaitnote(Request $request, $subsystem)
    {
        $notes = NaitNote::where('user_id', Auth::id())
            ->orderBy('position')
            ->paginate(10);

        return [
            'notes' => $notes,
        ];
    }

    private function handleNaitcalendar(Request $request, $subsystem)
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $currentDate = Carbon::createFromDate($year, $month, 1);

        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday
        $daysInMonth = $endOfMonth->day;

        $events = NaitCalendarEvent::where('user_id', Auth::id())
            ->whereBetween('event_date', [
                $startOfMonth->toDateString(),
                $endOfMonth->toDateString()
            ])
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->get()
            ->groupBy('event_date');

        return [
            'currentDate' => $currentDate,
            'month' => $month,
            'year' => $year,
            'startDayOfWeek' => $startDayOfWeek,
            'daysInMonth' => $daysInMonth,
            'events' => $events,
        ];
    }

    private function handleNaitfile(Request $request, $subsystem)
    {
        $files = Storage::disk('s3')->allFiles();

        return [
            'files' => $files,
        ];
    }
}
