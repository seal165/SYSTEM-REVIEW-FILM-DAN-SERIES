<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Theater;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShowtimeController extends Controller
{
    public function index(): View
    {
        $showtimes = Showtime::with(['movie', 'theater'])
            ->orderBy('show_date', 'desc')
            ->orderBy('show_time', 'asc')
            ->paginate(15);
            
        return view('admin.showtime.index', compact('showtimes'));
    }

    public function apiIndex()
    {
        $showtimes = Showtime::with(['movie', 'theater'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $showtimes
        ]);
    }

    public function create(): View
    {
        $movies = Movie::where('status', '!=', 'ended')->get();
        $theaters = Theater::where('is_active', true)->get();
        
        return view('admin.showtime.create', compact('movies', 'theaters'));
    }
    

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required|date_format:H:i',
            'ticket_price' => 'required|numeric|min:0'
        ]);

        // Get theater capacity for available seats
        $theater = Theater::find($validated['theater_id']);
        $validated['available_seats'] = $theater->capacity;

        // Check for conflicting showtimes
        $movie = Movie::find($validated['movie_id']);
        $showDateTime = $validated['show_date'] . ' ' . $validated['show_time'];
        $endTime = date('Y-m-d H:i:s', strtotime($showDateTime . ' +' . $movie->duration . ' minutes'));

        $conflict = Showtime::where('theater_id', $validated['theater_id'])
            ->where('show_date', $validated['show_date'])
            ->where(function($query) use ($showDateTime, $endTime) {
                $query->whereBetween('show_time', [
                    date('H:i:s', strtotime($showDateTime)),
                    date('H:i:s', strtotime($endTime))
                ]);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['show_time' => 'This time slot conflicts with another showtime in the same theater.']);
        }

        Showtime::create($validated);

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime created successfully.');
    }
    public function show(Showtime $showtime): View
    {
        $showtime->load(['movie', 'theater']);
        return view('admin.showtimes.show', compact('showtime'));
    }
    

    public function edit(Showtime $showtime): View
    {
        $movies = Movie::where('status', '!=', 'ended')->get();
        $theaters = Theater::where('is_active', true)->get();
        
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'theaters'));
    }

    public function update(Request $request, Showtime $showtime): RedirectResponse
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required|date_format:H:i',
            'ticket_price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0'
        ]);

        // Check for conflicting showtimes (excluding current showtime)
        $movie = Movie::find($validated['movie_id']);
        $showDateTime = $validated['show_date'] . ' ' . $validated['show_time'];
        $endTime = date('Y-m-d H:i:s', strtotime($showDateTime . ' +' . $movie->duration . ' minutes'));

        $conflict = Showtime::where('theater_id', $validated['theater_id'])
            ->where('show_date', $validated['show_date'])
            ->where('id', '!=', $showtime->id)
            ->where(function($query) use ($showDateTime, $endTime) {
                $query->whereBetween('show_time', [
                    date('H:i:s', strtotime($showDateTime)),
                    date('H:i:s', strtotime($endTime))
                ]);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['show_time' => 'This time slot conflicts with another showtime in the same theater.']);
        }

        $showtime->update($validated);

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime updated successfully.');
    }

    public function destroy(Showtime $showtime): RedirectResponse
    {
        $showtime->delete();

        return redirect()->route('showtimes.index')
            ->with('success', 'Showtime deleted successfully.');
    }

    public function getTheaterCapacity(Request $request)
    {
        $theater = Theater::find($request->theater_id);
        return response()->json(['capacity' => $theater ? $theater->capacity : 0]);
    }
}