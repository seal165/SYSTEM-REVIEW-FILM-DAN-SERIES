<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function index(): View
    {
        $movies = Movie::latest()->paginate(12);
        return view('admin.movies.index', compact('movies'));
    }

    public function create(): View
    {
        return view('admin.movies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:500',
            'release_date' => 'required|date',
            'rating' => 'required|in:G,PG,PG-13,R,NC-17',
            'description' => 'required|string',
            'poster_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:now_showing,coming_soon,ended'
        ]);

        Movie::create($validated);

        return redirect()->route('movies.index')
            ->with('success', 'Movie added successfully.');
    }

    public function show(Movie $movie): View
    {
        $movie->load('showtimes.theater');
        return view('admin.movies.show', compact('movie'));
    }

    public function edit(Movie $movie): View
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:500',
            'release_date' => 'required|date',
            'rating' => 'required|in:G,PG,PG-13,R,NC-17',
            'description' => 'required|string',
            'poster_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:now_showing,coming_soon,ended'
        ]);

        $movie->update($validated);

        return redirect()->route('movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();

        return redirect()->route('movies.index')
            ->with('success', 'Movie deleted successfully.');
    }

    public function apiIndex()
    {
        $movies = Movie::all();
        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }
}