<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'booking'])
            ->latest()
            ->paginate(10);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load(['user', 'booking']);
        return view('admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $feedback->update($request->only('is_public', 'is_active'));

        return redirect()->route('admin.feedbacks.index')
            ->with('success', 'Feedback berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }

    /**
     * Toggle public status
     */
    public function togglePublic(Feedback $feedback)
    {
        $feedback->update(['is_public' => !$feedback->is_public]);

        return redirect()->back()
            ->with('success', 'Status publikasi feedback berhasil diubah.');
    }
}
