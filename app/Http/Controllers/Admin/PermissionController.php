<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::withCount('users', 'roles')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function($row) {
                    $icon = $this->getPermissionIcon($row->name);
                    $category = $this->getPermissionCategory($row->name);
                    $categoryColor = $this->getCategoryColor($category);
                    
                    return '<div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg ' . $categoryColor . ' flex items-center justify-center text-white">
                            <i class="' . $icon . ' text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">' . ucfirst(str_replace(['-', '_'], ' ', $row->name)) . '</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">' . $category . '</div>
                        </div>
                    </div>';
                })
                ->addColumn('usage', function($row) {
                    $userCount = $row->users_count;
                    $roleCount = $row->roles_count;
                    $total = $userCount + $roleCount;
                    
                    return '<div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-users text-xs text-blue-600"></i>
                            <span class="text-sm">' . $userCount . ' users</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-user-tag text-xs text-purple-600"></i>
                            <span class="text-sm">' . $roleCount . ' roles</span>
                        </div>
                    </div>';
                })
                ->editColumn('guard_name', function($row) {
                    return '<div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-sm text-green-600"></i>
                        <span class="text-sm">' . $row->guard_name . '</span>
                    </div>';
                })
                ->editColumn('created_at', function($row) {
                    return '<div class="text-sm">
                        <div class="text-gray-900 dark:text-white">' . $row->created_at->format('d M Y') . '</div>
                        <div class="text-gray-500 dark:text-gray-400">' . $row->created_at->format('H:i') . '</div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('permissions.edit', $row->id);
                    $isSystemPermission = $this->isSystemPermission($row->name);

                    $actions = '
                        <div class="flex justify-center items-center gap-2">
                            <a href="' . $editUrl . '" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                               title="Edit Permission">
                                <i class="fas fa-edit text-sm"></i>
                            </a>';
                    
                    if (!$isSystemPermission) {
                        $actions .= '<button type="button" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200 deleteBtn" 
                                    data-id="' . $row->id . '" 
                                    title="Delete Permission">
                                <i class="fas fa-trash text-sm"></i>
                            </button>';
                    } else {
                        $actions .= '<button type="button" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" 
                                    title="Cannot delete system permission" disabled>
                                <i class="fas fa-lock text-sm"></i>
                            </button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['name', 'usage', 'guard_name', 'created_at', 'action'])
                ->make(true);
        }

        return view('admin.permissions.index');
    }

    private function getPermissionIcon($permissionName)
    {
        $permissionName = strtolower($permissionName);
        
        $icons = [
            // CRUD operations
            'create' => 'fas fa-plus',
            'read' => 'fas fa-eye',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'view' => 'fas fa-eye',
            
            // User management
            'user' => 'fas fa-user',
            'users' => 'fas fa-users',
            'profile' => 'fas fa-user-circle',
            
            // Role & Permission management
            'role' => 'fas fa-user-tag',
            'permission' => 'fas fa-shield-alt',
            
            // Booking & Services
            'booking' => 'fas fa-calendar-check',
            'service' => 'fas fa-cut',
            'hairstyle' => 'fas fa-scissors',
            
            // Financial
            'transaction' => 'fas fa-receipt',
            'payment' => 'fas fa-credit-card',
            'loyalty' => 'fas fa-star',
            
            // System
            'admin' => 'fas fa-cog',
            'dashboard' => 'fas fa-tachometer-alt',
            'report' => 'fas fa-chart-bar',
            'analytics' => 'fas fa-chart-line',
        ];

        // Check for compound permissions (e.g., "create-users", "edit-booking")
        foreach ($icons as $keyword => $icon) {
            if (strpos($permissionName, $keyword) !== false) {
                return $icon;
            }
        }

        return 'fas fa-key'; // Default permission icon
    }

    private function getPermissionCategory($permissionName)
    {
        $permissionName = strtolower($permissionName);
        
        if (strpos($permissionName, 'user') !== false) return 'User Management';
        if (strpos($permissionName, 'role') !== false) return 'Role Management';
        if (strpos($permissionName, 'permission') !== false) return 'Permission Management';
        if (strpos($permissionName, 'booking') !== false) return 'Booking Management';
        if (strpos($permissionName, 'service') !== false) return 'Service Management';
        if (strpos($permissionName, 'hairstyle') !== false) return 'Hairstyle Management';
        if (strpos($permissionName, 'transaction') !== false) return 'Financial Management';
        if (strpos($permissionName, 'payment') !== false) return 'Payment Management';
        if (strpos($permissionName, 'loyalty') !== false) return 'Loyalty Management';
        if (strpos($permissionName, 'admin') !== false) return 'Administration';
        if (strpos($permissionName, 'dashboard') !== false) return 'Dashboard';
        if (strpos($permissionName, 'report') !== false) return 'Reporting';
        if (strpos($permissionName, 'analytics') !== false) return 'Analytics';
        
        return 'General';
    }

    private function getCategoryColor($category)
    {
        $colors = [
            'User Management' => 'bg-blue-500',
            'Role Management' => 'bg-purple-500',
            'Permission Management' => 'bg-red-500',
            'Booking Management' => 'bg-green-500',
            'Service Management' => 'bg-yellow-500',
            'Hairstyle Management' => 'bg-pink-500',
            'Financial Management' => 'bg-emerald-500',
            'Payment Management' => 'bg-orange-500',
            'Loyalty Management' => 'bg-indigo-500',
            'Administration' => 'bg-gray-600',
            'Dashboard' => 'bg-cyan-500',
            'Reporting' => 'bg-teal-500',
            'Analytics' => 'bg-rose-500',
            'General' => 'bg-gray-500'
        ];

        return $colors[$category] ?? 'bg-gray-500';
    }

    private function isSystemPermission($permissionName)
    {
        $systemPermissions = [
            'access-admin-panel',
            'view-dashboard',
            'super-admin',
            'manage-all'
        ];

        return in_array($permissionName, $systemPermissions);
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web', // default guard
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        
        // Prevent deletion of system permissions
        if ($this->isSystemPermission($permission->name)) {
            return response()->json(['error' => 'Cannot delete system permission.'], 403);
        }
        
        $permission->delete();

        return response()->json(['success' => true, 'message' => 'Permission deleted successfully.']);
    }
}