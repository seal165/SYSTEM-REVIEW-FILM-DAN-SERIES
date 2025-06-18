<?php

namespace App\Http\Controllers;

use App\Models\Theater;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TheaterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/theaters",
     *     summary="List all theaters",
     *     tags={"Theaters"},
     *     @OA\Response(
     *         response=200,
     *         description="List of theaters"
     *     )
     * )
     */
    public function index(): View
    {
        $theaters = Theater::latest()->paginate(10);
        return view('admin.theaters.index', compact('theaters'));
    }

    /**
     * @OA\Get(
     *     path="/admin/theaters/create",
     *     summary="Show form to create a new theater",
     *     tags={"Theaters"},
     *     @OA\Response(
     *         response=200,
     *         description="Create theater form"
     *     )
     * )
     */
    public function create(): View
    {
        return view('admin.theaters.create');
    }

    /**
     * @OA\Post(
     *     path="/admin/theaters",
     *     summary="Store a new theater",
     *     tags={"Theaters"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "capacity", "type"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="capacity", type="integer", minimum=1, maximum=1000),
     *             @OA\Property(property="type", type="string", enum={"regular", "imax", "vip", "4dx"}),
     *             @OA\Property(property="facilities", type="string"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after success")
     * )
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
            'type' => 'required|in:regular,imax,vip,4dx',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        Theater::create($validated);

        return redirect()->route('theaters.index')
            ->with('success', 'Theater created successfully.');
    }

    /**
     * @OA\Get(
     *     path="/admin/theaters/{id}",
     *     summary="Show theater detail",
     *     tags={"Theaters"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Theater detail page"
     *     )
     * )
     */
    public function show(Theater $theater): View
    {
        $theater->load('showtimes.movie');
        return view('admin.theaters.show', compact('theater'));
    }

    /**
     * @OA\Get(
     *     path="/admin/theaters/{id}/edit",
     *     summary="Edit theater form",
     *     tags={"Theaters"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Edit theater view")
     * )
     */
    public function edit(Theater $theater): View
    {
        return view('admin.theaters.edit', compact('theater'));
    }

    /**
     * @OA\Put(
     *     path="/admin/theaters/{id}",
     *     summary="Update existing theater",
     *     tags={"Theaters"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "capacity", "type"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="capacity", type="integer"),
     *             @OA\Property(property="type", type="string", enum={"regular", "imax", "vip", "4dx"}),
     *             @OA\Property(property="facilities", type="string"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=302, description="Redirect after success")
     * )
     */
    public function update(Request $request, Theater $theater): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
            'type' => 'required|in:regular,imax,vip,4dx',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $theater->update($validated);

        return redirect()->route('theaters.index')
            ->with('success', 'Theater updated successfully.');
    }

    /**
     * @OA\Delete(
     *     path="/admin/theaters/{id}",
     *     summary="Delete theater",
     *     tags={"Theaters"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=302, description="Redirect after deletion")
     * )
     */
    public function destroy(Theater $theater): RedirectResponse
    {
        $theater->delete();

        return redirect()->route('theaters.index')
            ->with('success', 'Theater deleted successfully.');
    }

    public function apiIndex()
    {
        $theaters = Theater::all();
        return response()->json([
            'status' => 'success',
            'data' => $theaters
        ]);
    }
}
