<?php

namespace App\Http\Controllers\NaitCalendar;

use App\Http\Controllers\Controller;
use App\Models\NaitCalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NaitCalendarController extends Controller
{
   

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'type' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
        ]);

        NaitCalendarEvent::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Event added successfully.');
    }

    public function update(Request $request, $id)
    {
        $event = NaitCalendarEvent::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'type' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = NaitCalendarEvent::where('user_id', Auth::id())->findOrFail($id);
        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully.');
    }
}