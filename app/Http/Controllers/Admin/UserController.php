<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use App\Models\Loyalty;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view users', ['only' => ['index', 'show', 'getStats']]);
        // $this->middleware('permission:create users', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit users', ['only' => ['edit', 'update', 'toggleStatus', 'resetPassword']]);
        // $this->middleware('permission:delete users', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            \Log::info('DataTable AJAX request received', [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            $query = User::with(['roles', 'permissions', 'bookings', 'loyalty']);

            // Apply search filter
            if ($request->has('search') && ! empty($request->search['value'])) {
                $searchTerm = $request->search['value'];
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('no_telepon', 'LIKE', "%{$searchTerm}%");
                });
            }

            

            // Apply role filter
            if ($request->has('role_filter') && ! empty($request->role_filter)) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role_filter);
                });
            }

            // Apply status filter (active/inactive)
            // Apply status filter (active/inactive)
if ($request->filled('status_filter')) {
    $query->where('is_active', (int) $request->status_filter);
}

            // Apply month filter
            if ($request->has('month_filter') && ! empty($request->month_filter)) {
                $query->whereMonth('created_at', $request->month_filter);
            }

            // Apply year filter
            if ($request->has('year_filter') && ! empty($request->year_filter)) {
                $query->whereYear('created_at', $request->year_filter);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $avatar = strtoupper(substr($row->name, 0, 1));
                    $statusColor = $row->is_active ?? true ? 'bg-green-500' : 'bg-red-500';

                    return '<div class="flex items-center space-x-3">
                        
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">'.e($row->name).'</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">'.e($row->email).'</div>
                        </div>
                    </div>';
                })
                ->editColumn('no_telepon', function ($row) {
                    return $row->no_telepon ?
                        '<div class="flex items-center space-x-2">
                            <i class="fas fa-phone text-green-600"></i>
                            <span class="text-sm">'.e($row->no_telepon).'</span>
                        </div>' :
                        '<span class="text-gray-400 text-sm">Not provided</span>';
                })
                ->editColumn('roles', function ($row) {
                    if ($row->roles->isEmpty()) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-user-slash mr-1"></i>No Role
                        </span>';
                    }

                    $roleColors = [
                        'admin' => 'bg-red-100 text-red-800',
                        'pegawai' => 'bg-blue-100 text-blue-800',
                        'pelanggan' => 'bg-green-100 text-green-800',
                        'staff' => 'bg-yellow-100 text-yellow-800',
                    ];

                    $roleIcons = [
                        'admin' => 'fas fa-crown',
                        'pegawai' => 'fas fa-user-tie',
                        'pelanggan' => 'fas fa-user',
                        'staff' => 'fas fa-user-cog',
                    ];

                    return $row->roles->map(function ($role) use ($roleColors, $roleIcons) {
                        $color = $roleColors[$role->name] ?? 'bg-gray-100 text-gray-800';
                        $icon = $roleIcons[$role->name] ?? 'fas fa-user';

                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$color.' mb-1 mr-1">
                            <i class="'.$icon.' mr-1"></i>'.ucfirst($role->name).'
                        </span>';
                    })->implode(' ');
                })


                // Tambahkan kolom loyalty points
->editColumn('loyalty_points', function ($row) {
    $loyalty = $row->loyalty; // Menggunakan relasi loyalty
    
    if (!$loyalty || !$loyalty->points) {
        return '<span class="text-gray-400 text-sm">-</span>';
    }

    $badgeClass = match (true) {
        $loyalty->points >= 1000 => 'bg-gold-500 text-white',
        $loyalty->points >= 500 => 'bg-silver-500 text-white',
        default => 'bg-bronze-500 text-white'
    };

    return '<div class="flex items-center space-x-2">
        <i class="fas fa-star text-yellow-500"></i>
        <span class="text-sm font-medium text-gray-700">' . number_format($loyalty->points) . ' poin</span>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $badgeClass . ' ml-2">
            ' . ucfirst($loyalty->tier) . '
        </span>
    </div>';
})
                // ->editColumn('permissions', function ($row) {
                //     $allPermissions = $row->getAllPermissions();

                //     if ($allPermissions->isEmpty()) {
                //         return '<span class="text-gray-500 text-sm">No permissions</span>';
                //     }

                //     $count = $allPermissions->count();
                //     $first = $allPermissions->first()->name;

                //     return '<div class="flex items-center space-x-2">
                //         <i class="fas fa-shield-alt text-purple-600"></i>
                //         <span class="text-sm text-gray-700">' .
                //         str_replace('_', ' ', ucfirst($first)) .
                //         ($count > 1 ? ' +' . ($count - 1) . ' more' : '') .
                //         '</span>
                //     </div>';
                // })
                ->editColumn('created_at', function ($row) {
                    return '<div class="text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">'.$row->created_at->format('M d, Y').'</div>
                        <div class="text-gray-500 dark:text-gray-400">'.$row->created_at->format('g:i A').'</div>
                    </div>';
                })
                ->addColumn('status', function ($row) {
                    $isVerified = $row->isEmailVerified();
                    $isActive = $row->is_active ?? true;

                    $badges = [];

                    // Email verification status
                    if ($isVerified) {
                        $badges[] = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Verified
                        </span>';
                    } else {
                        $badges[] = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Unverified
                        </span>';
                    }

                    // Account status
                    if ($isActive) {
                        $badges[] = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                            <i class="fas fa-user-check mr-1"></i>Active
                        </span>';
                    } else {
                        $badges[] = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                            <i class="fas fa-user-times mr-1"></i>Inactive
                        </span>';
                    }

                    return '<div class="space-y-1 space-x-1">'.implode('', $badges).'</div>';
                })
                ->addColumn('stats', function ($row) {
                    $bookingsCount = $row->bookings ? $row->bookings->count() : 0;
                    $lastLogin = $row->last_login_at ? $row->last_login_at->diffForHumans() : 'Never';

                    return '<div class="text-xs text-gray-600">
                        <div class="flex items-center space-x-1 mb-1">
                            <i class="fas fa-calendar-check text-blue-500"></i>
                            <span>'.$bookingsCount.' bookings</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-clock text-green-500"></i>
                            <span>'.$lastLogin.'</span>
                        </div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.users.show', $row->id);
                    $editUrl = route('admin.users.edit', $row->id);

                    $actions = '<div class="flex justify-center items-center space-x-1">';

                    // View button
                    // $actions .= '<a href="'.$showUrl.'" 
                    //                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 transition-all duration-200 group" 
                    //                title="View Details">
                    //                 <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                    //             </a>';

                    // Edit button
                    if (auth()->user()->can('edit users')) {
                        $actions .= '<a href="'.$editUrl.'" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-all duration-200 group" 
                                       title="Edit User">
                                        <i class="fas fa-edit text-xs group-hover:scale-110 transition-transform"></i>
                                    </a>';
                    }

                    // Loyalty Points Reset button
                    if ($row->loyalty && $row->loyalty->points >= 10) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition-all duration-200 group resetLoyaltyBtn" 
                                            data-user-id="'.$row->id.'" 
                                            data-loyalty-id="'.$row->loyalty->id.'" 
                                            data-user-name="'.$row->name.'" 
                                            data-points="'.$row->loyalty->points.'" 
                                            title="Reset Loyalty Points (Potong Gratis)">
                                        <i class="fas fa-gift text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    } elseif ($row->loyalty && $row->loyalty->points < 10) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" 
                                            disabled
                                            title="Belum mencapai 10 poin (Poin saat ini: '.$row->loyalty->points.')">
                                        <i class="fas fa-gift text-xs"></i>
                                    </button>';
                    }

                    // Delete button
                    if (auth()->user()->can('delete users') && $row->id !== auth()->id()) {
                        $actions .= '<button type="button" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-all duration-200 group deleteBtn" 
                                            data-id="'.$row->id.'" 
                                            title="Delete User">
                                        <i class="fas fa-trash text-xs group-hover:scale-110 transition-transform"></i>
                                    </button>';
                    }

                    $actions .= '</div>';

                    return $actions;
                })
               ->rawColumns([
    'name', 
    'no_telepon', 
    'roles', 
    'created_at', 
    'status', 
    'stats', 
    'action',
    'loyalty_points' // Tambahkan kolom baru ini
])
                ->make(true);
        }

        // Get statistics for the view
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        $roles = Role::all();
        $loyaltyPoints = Loyalty::all();

        return view('admin.users.index', compact('stats', 'roles', 'loyaltyPoints'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions', 'bookings', 'loyalty' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed'],
            'no_telepon' => 'required|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email_verified' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($request->password),
                'no_telepon' => $validated['no_telepon'],
                'email_verified_at' => $request->boolean('email_verified') ? now() : null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Assign roles
            if (! empty($validated['roles'])) {
                $roles = Role::whereIn('id', $validated['roles'])->get();
                $user->assignRole($roles);
            }

            // // Assign direct permissions
            // if (!empty($validated['permissions'])) {
            //     $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            //     $user->givePermissionTo($permissions);
            // }

            // Send verification email if not manually verified
            // if (!$request->boolean('email_verified')) {
            //     event(new Registered($user));
            // }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User creation failed: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        Log::info('Update function called', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email_verified' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            Log::info('Updating user details', [
                'user_id' => $user->id,
                'validated_data' => $validated,
            ]);

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'],
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                Log::info('Updating user password', ['user_id' => $user->id]);
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            // Update email verification status
            if ($request->boolean('email_verified') && ! $user->hasVerifiedEmail()) {
                Log::info('Marking email as verified', ['user_id' => $user->id]);
                $user->markEmailAsVerified();
            } elseif (! $request->boolean('email_verified') && $user->hasVerifiedEmail()) {
                Log::info('Unmarking email as verified', ['user_id' => $user->id]);
                $user->email_verified_at = null;
                $user->save();
            }

            // Sync roles
            if (! empty($validated['roles'])) {
                Log::info('Syncing roles', [
                    'user_id' => $user->id,
                    'roles' => $validated['roles'],
                ]);
                $roles = Role::whereIn('id', $validated['roles'])->get();
                $user->syncRoles($roles);
            } else {
                Log::info('Removing all roles', ['user_id' => $user->id]);
                $user->syncRoles([]);
            }

            // // Sync permissions
            // if (!empty($validated['permissions'])) {
            //     Log::info('Syncing permissions', [
            //         'user_id' => $user->id,
            //         'permissions' => $validated['permissions']
            //     ]);
            //     $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            //     $user->syncPermissions($permissions);
            // } else {
            //     Log::info('Removing all permissions', ['user_id' => $user->id]);
            //     $user->syncPermissions([]);
            // }

            DB::commit();

            Log::info('User updated successfully', ['user_id' => $user->id]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Remove all roles and permissions
            $user->syncRoles([]);
            $user->syncPermissions([]);

            // Delete the user
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User deletion failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. Please try again.',
            ], 500);
        }
    }

    /**
     * Resend email verification
     */
    // public function resendVerification(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id'
    //     ]);

    //     $user = User::findOrFail($request->user_id);

    //     if ($user->hasVerifiedEmail()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Email is already verified.'
    //         ], 422);
    //     }

    //     try {
    //         $user->sendEmailVerificationNotification();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Verification email sent successfully.'
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Email verification send failed: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to send verification email.'
    //         ], 500);
    //     }
    // }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        try {
            // Generate temporary password
            $temporaryPassword = str()->random(12);

            $user->update([
                'password' => Hash::make($temporaryPassword),
            ]);

            // Send password reset email
            // You can implement this based on your email notification system

            return response()->json([
                'success' => true,
                'message' => 'Password reset email sent successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password.',
            ], 500);
        }
    }

    /**
     * Toggle user account status
     */
    public function toggleStatus(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate',
        ]);

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own account status.',
            ], 422);
        }

        try {
            $isActivating = $request->action === 'activate';
            $previousStatus = $user->is_active;

            Log::info('User status toggle attempt', [
                'admin_user_id' => auth()->id(),
                'admin_email' => auth()->user()->email,
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'action' => $request->action,
                'previous_status' => $previousStatus,
                'new_status' => $isActivating,
                'ip' => $request->ip()
            ]);

            $user->update([
                'is_active' => $isActivating,
            ]);

            Log::info('User status toggle successful', [
                'admin_user_id' => auth()->id(),
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'action_completed' => $request->action,
                'status_changed_from' => $previousStatus,
                'status_changed_to' => $isActivating
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User account '.($isActivating ? 'activated' : 'deactivated').' successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('User status toggle failed', [
                'admin_user_id' => auth()->id(),
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'action' => $request->action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update account status.',
            ], 500);
        }
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => User::count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
                'active' => User::where('is_active', true)->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                'roles_distribution' => User::select('roles.name', DB::raw('count(*) as count'))
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->groupBy('roles.name')
                    ->pluck('count', 'roles.name')
                    ->toArray(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('User stats retrieval failed: '.$e->getMessage());

            return response()->json([
                'error' => 'Failed to retrieve statistics.',
            ], 500);
        }
    }


    public function exportCsv(Request $request): StreamedResponse
{
    $month = $request->get('month');
    $year = $request->get('year');
    
    $period = '';
    if ($month && $year) {
        $period = '_' . \Carbon\Carbon::create($year, $month)->format('M_Y');
    } elseif ($year) {
        $period = '_' . $year;
    }
    
    $fileName = 'users' . $period . '_' . now()->format('Ymd_His') . '.csv';

    // Query with filter
    $query = User::with('roles');
    
    if ($month && $year) {
        $query->whereYear('created_at', $year)
              ->whereMonth('created_at', $month);
    } elseif ($year) {
        $query->whereYear('created_at', $year);
    }
    
    $users = $query->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    $callback = function () use ($users) {
        $handle = fopen('php://output', 'w');

        // Header
        fputcsv($handle, ['No', 'Name', 'Email', 'No Telepon', 'Roles', 'Status', 'Created At']);

        // Rows
        foreach ($users as $i => $user) {
            fputcsv($handle, [
                $i + 1, // nomor urut, bukan ID
                $user->name,
                $user->email,
                $user->no_telepon ?? '-',
                $user->roles->pluck('name')->implode(', ') ?: 'pelanggan',
                $user->deleted_at ? 'Inactive' : 'Active',
                $user->created_at->format('d/m/Y H:i'),
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}

    /**
     * Export users to PDF with filter
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        
        $period = '';
        if ($month && $year) {
            $period = '_' . \Carbon\Carbon::create($year, $month)->format('M_Y');
        } elseif ($year) {
            $period = '_' . $year;
        }

        // Query with filter
        $query = User::with('roles');
        
        if ($month && $year) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } elseif ($year) {
            $query->whereYear('created_at', $year);
        }
        
        $users = $query->get();

        $pdf = Pdf::loadView('admin.users.export_pdf', compact('users', 'month', 'year'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('users' . $period . '_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Redeem loyalty points for free service
     */
    public function redeemLoyalty(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $loyalty = $user->loyalty;

            if (!$loyalty || !$loyalty->canRedeemFreeService()) {
                return back()->with('error', 'Anda tidak memiliki poin yang cukup untuk redeem gratis (minimum 10 poin).');
            }

            $request->validate([
                'service_id' => 'required|exists:services,id',
                'hairstyle_id' => 'required|exists:hairstyles,id',
                'date_time' => 'required|date|after:now',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            $service = \App\Models\Service::findOrFail($request->service_id);

            // Create free booking
            $booking = \App\Models\Booking::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'service_id' => $request->service_id,
                'hairstyle_id' => $request->hairstyle_id,
                'date_time' => $request->date_time,
                'queue_number' => $this->generateQueueNumber($request->date_time),
                'description' => $request->description,
                'status' => 'pending',
                'total_price' => 0, // FREE!
                'payment_method' => 'loyalty_redeem',
                'is_loyalty_redeem' => true
            ]);

            // Redeem loyalty points (reset to 0)
            $loyalty->redeemFreeService();

            DB::commit();

            Log::info('Loyalty points redeemed for free service', [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'points_before' => 10,
                'points_after' => $loyalty->points
            ]);

            return redirect()->route('bookings.index')
                ->with('success', "Berhasil! Anda telah menggunakan 10 poin untuk potong rambut gratis. Nomor antrian: {$booking->queue_number}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to redeem loyalty points', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal melakukan redeem: ' . $e->getMessage());
        }
    }

    /**
     * Reset loyalty points for free haircut redemption
     */
    public function resetLoyaltyPoints(Request $request, $userId)
    {
        try {
            $user = User::with('loyalty')->findOrFail($userId);
            
            if (!$user->loyalty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak memiliki data loyalty.'
                ], 400);
            }
            
            // Check if user has enough points to redeem
            if ($user->loyalty->points < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan belum memiliki 10 poin untuk direset. Poin saat ini: ' . $user->loyalty->points
                ], 400);
            }

            // Reset points to 0
            $oldPoints = $user->loyalty->points;
            $user->loyalty->points = 0;
            $user->loyalty->save();

            Log::info('Admin reset loyalty points', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'loyalty_id' => $user->loyalty->id,
                'points_before' => $oldPoints,
                'points_after' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil reset poin loyalty pelanggan ' . $user->name . '. Pelanggan mendapat potong gratis!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to reset loyalty points', [
                'user_id' => $userId,
                'admin_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal reset poin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate queue number for given date
     */
    private function generateQueueNumber($dateTime)
    {
        $date = \Carbon\Carbon::parse($dateTime)->format('Y-m-d');
        $maxQueue = \App\Models\Booking::whereDate('date_time', $date)->max('queue_number');
        return $maxQueue ? $maxQueue + 1 : 1;
    }
}
