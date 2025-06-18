<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'staff' => User::where('role', 'staff')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,manager,staff',
            'is_active' => 'boolean'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        // Get user statistics if booking model exists
        $userStats = [
            'total_bookings' => 0,
            'total_spent' => 0,
            'last_booking' => null,
            'favorite_genre' => 'N/A'
        ];

        if (class_exists('App\Models\Booking')) {
            $bookings = $user->bookings ?? collect();
            $userStats = [
                'total_bookings' => $bookings->count(),
                'total_spent' => $bookings->sum('total_amount'),
                'last_booking' => $bookings->first()?->created_at,
                'favorite_genre' => $this->getUserFavoriteGenre($user)
            ];
        }

        return view('admin.users.show', compact('user', 'userStats'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,staff',
            'is_active' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if user has bookings
        if (class_exists('App\Models\Booking') && $user->bookings()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete user with existing bookings.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return redirect()->back()
            ->with('success', 'User activated successfully.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', 'User deactivated successfully.');
    }

    private function getUserFavoriteGenre(User $user)
    {
        if (!class_exists('App\Models\Booking')) {
            return 'N/A';
        }

        $favoriteGenre = DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.user_id', $user->id)
            ->select('movies.genre', DB::raw('COUNT(*) as count'))
            ->groupBy('movies.genre')
            ->orderBy('count', 'desc')
            ->first();

        return $favoriteGenre ? $favoriteGenre->genre : 'N/A';
    }
}