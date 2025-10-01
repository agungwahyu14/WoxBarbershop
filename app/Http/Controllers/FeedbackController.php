<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show the form for creating feedback
     */
    public function create(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->route('bookings.index')
                ->with('error', 'You can only provide feedback for completed bookings.');
        }

        // Check if feedback already exists
        if ($booking->feedback) {
            return redirect()->route('feedback.show', $booking->feedback)
                ->with('info', 'You have already provided feedback for this booking.');
        }

        return view('feedback.create', compact('booking'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'You can only provide feedback for completed bookings.'], 400);
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('user_id', Auth::id())
                                   ->where('booking_id', $booking->id)
                                   ->first();

        if ($existingFeedback) {
            return response()->json(['success' => false, 'message' => 'You have already provided feedback for this booking.'], 400);
        }

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_public' => $request->boolean('is_public', true),
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you for your feedback!', 'feedback' => $feedback]);
        }

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Thank you for your feedback!');
    }

    /**
     * Display feedback
     */
    public function show(Feedback $feedback)
    {
        // Check if user owns this feedback
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        $feedback->load(['booking.service']);
        
        return view('feedback.show', compact('feedback'));
    }

    /**
     * Show edit form
     */
    public function edit(Feedback $feedback)
    {
        // Check if user owns this feedback
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        $feedback->load(['booking.service']);
        
        return view('feedback.edit', compact('feedback'));
    }

    /**
     * Update feedback
     */
    public function update(Request $request, Feedback $feedback)
    {
        // Check if user owns this feedback
        if ($feedback->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $feedback->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_public' => $request->boolean('is_public', true),
        ]);

        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Feedback updated successfully!');
    }
}