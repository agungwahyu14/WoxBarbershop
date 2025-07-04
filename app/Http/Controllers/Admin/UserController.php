<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles', 'permissions')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('no_telepon', function ($row) {
                    return '<div class="text-left">' . ($row->no_telepon ?? '-') . '</div>';
                })
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('permissions', function ($row) {
                    return  $row->permissions->pluck('name')->implode(', ');
                })
                
                ->addColumn('action', function ($row) {
                    $editUrl = route('users.edit', $row->id);
                    $deleteUrl = route('users.destroy', $row->id);

                    return '
<div class="flex justify-center items-center gap-2">
    <a href="' . $editUrl . '" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="Edit">
        <i class="fas fa-pen"></i>
    </a>
    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition deleteBtn" data-id="' . $row->id . '" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>
';

                })
                ->rawColumns(['no_telepon','roles', 'permissions', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'name'        => 'required|string|max:255',
    'email'       => 'required|email|unique:users,email',
    'password'    => 'required|string|min:6',
    'no_telepon'  => 'required|string|max:20',
    'roles'       => 'array',
    'permissions' => 'array'
]);


        $user = User::create([
    'name'        => $request->name,
    'email'       => $request->email,
    'password'    => bcrypt($request->password),
    'no_telepon'  => $request->no_telepon,
    ]);


        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        if ($request->permissions) {
            $user->givePermissionTo($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRoleIds', 'userPermissionIds'));
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email,' . $user->id,
        'no_telepon'  => 'required|string|max:20',
        'roles'       => 'array',
        'permissions' => 'array'
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->no_telepon = $request->no_telepon;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    // Sync roles and permissions
    $user->syncRoles($request->roles ?? []);
    $user->syncPermissions($request->permissions ?? []);

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}
