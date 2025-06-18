<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['showtime.movie', 'showtime.theater', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('showtime.movie', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhere('booking_reference', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $bookings = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'today_bookings' => Booking::whereDate('created_at', today())->count(),
            'today_revenue' => Booking::whereDate('created_at', today())->sum('total_amount'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['showtime.movie', 'showtime.theater', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function store(Request $request, Showtime $showtime): RedirectResponse
    {
        $validated = $request->validate([
            'seats_booked' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        // Check seat availability
        if ($showtime->available_seats < $validated['seats_booked']) {
            return redirect()->back()
                ->with('error', 'Not enough seats available. Only ' . $showtime->available_seats . ' seats left.');
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'showtime_id' => $showtime->id,
                'booking_reference' => $this->generateBookingReference(),
                'seats_booked' => $validated['seats_booked'],
                'total_amount' => $showtime->ticket_price * $validated['seats_booked'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);

            // Update available seats
            $showtime->decrement('available_seats', $validated['seats_booked']);

            DB::commit();

            return redirect()->route('booking.my')
                ->with('success', 'Booking confirmed! Reference: ' . $booking->booking_reference);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Booking failed. Please try again.');
        }
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // If booking is cancelled, return seats to showtime
        if ($oldStatus !== 'cancelled' && $validated['status'] === 'cancelled') {
            $booking->showtime->increment('available_seats', $booking->seats_booked);
        }

        // If booking is reconfirmed from cancelled, reduce available seats
        if ($oldStatus === 'cancelled' && $validated['status'] === 'confirmed') {
            if ($booking->showtime->available_seats < $booking->seats_booked) {
                return redirect()->back()
                    ->with('error', 'Cannot reconfirm booking. Not enough seats available.');
            }
            $booking->showtime->decrement('available_seats', $booking->seats_booked);
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        // Return seats to showtime if booking was confirmed
        if ($booking->status === 'confirmed') {
            $booking->showtime->increment('available_seats', $booking->seats_booked);
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function myBookings(): View
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.theater'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    public function createManual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'user_id' => 'nullable|exists:users,id',
            'seats_booked' => 'required|integer|min:1|max:10',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,card,online',
            'notes' => 'nullable|string|max:500',
        ]);

        $showtime = Showtime::findOrFail($validated['showtime_id']);

        // Check seat availability
        if ($showtime->available_seats < $validated['seats_booked']) {
            return redirect()->back()
                ->with('error', 'Not enough seats available.');
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => $validated['user_id'] ?? auth()->id(),
                'showtime_id' => $showtime->id,
                'booking_reference' => $this->generateBookingReference(),
                'seats_booked' => $validated['seats_booked'],
                'total_amount' => $showtime->ticket_price * $validated['seats_booked'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);

            // Update available seats
            $showtime->decrement('available_seats', $validated['seats_booked']);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Manual booking created successfully! Reference: ' . $booking->booking_reference);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Booking creation failed. Please try again.');
        }
    }

    public function downloadTicket(Booking $booking)
    {
        // Check if user owns this booking or is admin/manager/staff
        if ($booking->user_id !== auth()->id() && 
            !in_array(auth()->user()->role, ['admin', 'manager', 'staff'])) {
            abort(403);
        }

        $booking->load(['showtime.movie', 'showtime.theater', 'user']);

        // Generate PDF ticket (you'll need to install a PDF library like dompdf)
        // For now, return a simple view
        return view('bookings.ticket', compact('booking'));
    }

    private function generateBookingReference(): string
    {
        do {
            $reference = 'BK' . date('Ymd') . strtoupper(Str::random(6));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }
}