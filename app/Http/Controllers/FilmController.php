<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        $films = Film::all();
        return view('films.index', compact('films'));
    }

    public function create()
    {
        return view('films.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'durasi' => 'required|integer',
            'jadwal_tayang' => 'required|date',
            'sinopsis' => 'required|string',
        ]);

        Film::create($request->all());

        return redirect()->route('films.index')->with('success', 'Film berhasil ditambahkan.');
    }

    public function show(Film $film)
    {
        return view('films.show', compact('film'));
    }

    public function edit(Film $film)
    {
        return view('films.edit', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'durasi' => 'required|integer',
            'jadwal_tayang' => 'required|date',
            'sinopsis' => 'required|string',
        ]);

        $film->update($request->all());

        return redirect()->route('films.index')->with('success', 'Film berhasil diperbarui.');
    }

    public function destroy(Film $film)
    {
        $film->delete();
        return redirect()->route('films.index')->with('success', 'Film berhasil dihapus.');
    }
}
