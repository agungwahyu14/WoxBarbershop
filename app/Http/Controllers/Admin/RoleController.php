<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::withCount('users')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $icon = $this->getRoleIcon($row->name);
                    $color = $this->getRoleColor($row->name);

                    return '<div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg '.$color.' flex items-center justify-center text-white">
                            <i class="'.$icon.' text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">'.ucfirst($row->name).'</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Role</div>
                        </div>
                    </div>';
                })
                ->addColumn('users_count', function ($row) {
                    return '<div class="text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-users mr-1"></i>
                            '.$row->users_count.' '.($row->users_count == 1 ? 'User' : 'Users').'
                        </span>
                    </div>';
                })
                ->editColumn('guard_name', function ($row) {
                    return '<div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-sm text-green-600"></i>
                        <span class="text-sm">'.$row->guard_name.'</span>
                    </div>';
                })
                ->editColumn('created_at', function ($row) {
                    return '<div class="text-sm">
                        <div class="text-gray-900 dark:text-white">'.$row->created_at->format('d M Y').'</div>
                        <div class="text-gray-500 dark:text-gray-400">'.$row->created_at->format('H:i').'</div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('roles.edit', $row->id);
                    $canDelete = ! in_array($row->name, ['admin', 'super-admin']); // Protect critical roles

                    $actions = '
                        <div class="flex justify-center items-center gap-2">
                            <a href="'.$editUrl.'" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors duration-200" 
                               title="Edit Role">
                                <i class="fas fa-edit text-sm"></i>
                            </a>';

                    if ($canDelete) {
                        $actions .= '<button type="button" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors duration-200 deleteBtn" 
                                    data-id="'.$row->id.'" 
                                    title="Delete Role">
                                <i class="fas fa-trash text-sm"></i>
                            </button>';
                    } else {
                        $actions .= '<button type="button" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" 
                                    title="Cannot delete system role" disabled>
                                <i class="fas fa-lock text-sm"></i>
                            </button>';
                    }

                    $actions .= '</div>';

                    return $actions;
                })
                ->rawColumns(['name', 'users_count', 'guard_name', 'created_at', 'action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    private function getRoleIcon($roleName)
    {
        $icons = [
            'admin' => 'fas fa-crown',
            'pegawai' => 'fas fa-user-tie',
            'pelanggan' => 'fas fa-user',
            'staff' => 'fas fa-user-cog',
            'manager' => 'fas fa-user-shield',
            'super-admin' => 'fas fa-user-secret',
        ];

        return $icons[strtolower($roleName)] ?? 'fas fa-user-tag';
    }

    private function getRoleColor($roleName)
    {
        $colors = [
            'admin' => 'bg-red-500',
            'pegawai' => 'bg-blue-500',
            'pelanggan' => 'bg-green-500',
            'staff' => 'bg-yellow-500',
            'manager' => 'bg-purple-500',
            'super-admin' => 'bg-gray-800',
        ];

        return $colors[strtolower($roleName)] ?? 'bg-gray-500';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permissions' => 'array',
        ]);

        $role->name = $request->name;
        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of critical system roles
        if (in_array($role->name, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Cannot delete system role.'], 403);
        }

        $role->delete();

        return response()->json(['success' => 'Role deleted successfully.']);
    }
}
