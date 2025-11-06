<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FeedbackController extends Controller
{
    /**
     * Display a listing of resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Feedback::with(['user', 'booking'])->select('feedback.*')->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user.name', function ($row) {
                    $user = $row->user;
                    if (!$user) {
                        return '<span class="text-gray-400">Unknown User</span>';
                    }
                    
                    return '<div class="flex items-center gap-3">
                       
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">' . $user->name . '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">' . $user->email . '</div>
                        </div>
                    </div>';
                })
                ->editColumn('booking.id', function ($row) {
                    $booking = $row->booking;
                    if (!$booking) {
                        return '<span class="text-gray-400">No booking</span>';
                    }
                    
                    return '<div class="text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            #' . $booking->id . '
                        </span>
                    </div>';
                })
                ->editColumn('rating', function ($row) {
                    $stars = str_repeat('★', $row->rating) . str_repeat('☆', 5 - $row->rating);
                    $color = $row->rating >= 4 ? 'text-green-500' : ($row->rating >= 3 ? 'text-yellow-500' : 'text-red-500');
                    
                    return '<div class="text-center">
                        <div class="' . $color . ' text-lg">' . $stars . '</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">' . $row->rating . '/5</div>
                    </div>';
                })
                ->editColumn('comment', function ($row) {
                    if (!$row->comment) {
                        return '<span class="text-gray-400 italic">No comment</span>';
                    }
                    
                    $truncated = strlen($row->comment) > 50 ? substr($row->comment, 0, 50) . '...' : $row->comment;
                    return '<div class="max-w-xs">
                        <p class="text-sm text-gray-900 dark:text-white">' . $truncated . '</p>
                    </div>';
                })
               ->editColumn('is_public', function ($row) {
    // Gunakan fungsi __() agar bisa diterjemahkan dari file language
    $status = $row->is_public ? __('admin.public') : __('admin.private');
    $color = $row->is_public ? 'green' : 'gray';
    $icon = $row->is_public
        ? '<i class="fas fa-globe mr-1"></i>'
        : '<i class="fas fa-lock mr-1"></i>';

    return '
        <div class="text-center">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                ' . $icon . '
                ' . $status . '
            </span>
        </div>
    ';
})

                ->editColumn('is_active', function ($row) {
    $status = $row->is_active ? __('admin.active') : __('admin.inactive');
    $color = $row->is_active ? 'green' : 'red';

    return '
        <div class="text-center">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                <div class="w-1.5 h-1.5 rounded-full bg-' . $color . '-600 mr-1"></div>
                ' . $status . '
            </span>
        </div>
    ';
})

                ->editColumn('created_at', function ($row) {
                    return '<div class="text-sm text-gray-900 dark:text-white">
                        ' . $row->created_at->format('d M Y') . '
                        <div class="text-xs text-gray-500 dark:text-gray-400">' . $row->created_at->format('H:i') . '</div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
    $actions = '<div class="flex items-center gap-2 justify-center">';

    // Tombol View (semua role bisa lihat)
    $actions .= '<a href="' . route('admin.feedbacks.show', $row->id) . '" 
        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-colors duration-200">
        <i class="fas fa-eye"></i>
    </a>';

    // Hanya tampil jika user punya role 'admin'
    if (auth()->user()->hasRole('admin')) {

        // Toggle Public button
        $publicColor = $row->is_public ? 'yellow' : 'blue';
        $icon = $row->is_public ? 'fa-eye-slash' : 'fa-eye';

        $actions .= '<button type="button" data-id="' . $row->id . '" data-action="toggle-public" 
            class="toggleBtn inline-flex items-center justify-center w-8 h-8 rounded-lg text-' . $publicColor . '-600 bg-' . $publicColor . '-50 hover:bg-' . $publicColor . '-100 transition-colors">
            <i class="fas ' . $icon . '"></i>
        </button>';

        // Delete button
        $actions .= '<button type="button" data-id="' . $row->id . '" 
            class="deleteBtn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200">
            <i class="fas fa-trash"></i>
        </button>';
    }

    $actions .= '</div>';

    return $actions;
})

                ->rawColumns(['user.name', 'booking.id', 'rating', 'comment', 'is_public', 'is_active', 'created_at', 'action'])
                ->make(true);
        }

        return view('admin.feedbacks.index');
    }

    /**
     * Display specified resource.
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('admin.feedback_updated_successfully')
            ]);
        }

        return redirect()->route('admin.feedbacks.index')
            ->with('success', __('admin.feedback_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Feedback $feedback)
    {
        try {
            $feedback->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.feedback_deleted_successfully')
                ]);
            }

            return redirect()->route('admin.feedbacks.index')
                ->with('success', __('admin.feedback_deleted_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.failed_to_delete_feedback') . ': ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', __('admin.failed_to_delete_feedback'));
        }
    }

    /**
     * Toggle public status
     */
    public function togglePublic(Request $request, Feedback $feedback)
    {
        $feedback->update(['is_public' => !$feedback->is_public]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('admin.feedback_publication_status_updated_successfully'),
                'new_status' => $feedback->is_public
            ]);
        }

        return redirect()->back()
            ->with('success', __('admin.feedback_publication_status_updated_successfully'));
    }
}
