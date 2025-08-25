<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManagementPermissionsSeeder extends Seeder
{
    public function run()
    {
        // User Management Permissions
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',
            'manage user permissions',
            'reset user passwords',
            'verify user emails',
            'activate user accounts',
            'deactivate user accounts',
            'export users',
            'import users',
            'view user profiles',
            'edit user profiles',
            'view user activity logs',
        ];

        // Role & Permission Management
        $rolePermissions = [
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
            'revoke roles',
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'assign permissions',
            'revoke permissions',
        ];

        // Booking Management Permissions
        $bookingPermissions = [
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            'manage all bookings',
            'confirm bookings',
            'cancel bookings',
            'complete bookings',
            'view booking history',
            'export bookings',
        ];

        // Service Management Permissions
        $servicePermissions = [
            'view services',
            'create services',
            'edit services',
            'delete services',
            'activate services',
            'deactivate services',
            'manage service pricing',
            'view service analytics',
        ];

        // Transaction & Payment Permissions
        $transactionPermissions = [
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',
            'process payments',
            'refund payments',
            'view payment history',
            'export transactions',
            'manage payment methods',
        ];

        // Dashboard & Analytics Permissions
        $dashboardPermissions = [
            'view dashboard',
            'view analytics',
            'view reports',
            'export reports',
            'view admin dashboard',
            'view staff dashboard',
            'view customer dashboard',
        ];

        // System Management Permissions
        $systemPermissions = [
            'manage system settings',
            'backup system',
            'view system logs',
            'system maintenance',
            'clear cache',
            'manage notifications',
            'manage email templates',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $bookingPermissions,
            $servicePermissions,
            $transactionPermissions,
            $dashboardPermissions,
            $systemPermissions
        );

        // Create all permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Ensure all roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $pegawaiRole = Role::firstOrCreate(['name' => 'pegawai']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $pelangganRole = Role::firstOrCreate(['name' => 'pelanggan']);

        // Assign comprehensive permissions to admin role
        $adminRole->givePermissionTo($allPermissions);

        // Assign operational permissions to pegawai role
        $pegawaiRole->givePermissionTo([
            // User permissions (limited)
            'view users',
            'edit users',
            'view user profiles',
            'verify user emails',

            // Booking permissions (full operational)
            'view bookings',
            'create bookings',
            'edit bookings',
            'confirm bookings',
            'cancel bookings',
            'complete bookings',
            'view booking history',

            // Service permissions (read-only)
            'view services',
            'view service analytics',

            // Transaction permissions (operational)
            'view transactions',
            'create transactions',
            'process payments',
            'view payment history',

            // Dashboard permissions (staff level)
            'view dashboard',
            'view analytics',
            'view staff dashboard',

            // Limited system access
            'view system logs',
        ]);

        // Assign customer permissions to customer role
        $customerRole->givePermissionTo([
            'view user profiles',
            'edit user profiles',
            'create bookings',
            'view bookings',
            'edit bookings',
            'cancel bookings',
            'view services',
            'view transactions',
            'view dashboard',
            'view customer dashboard',
        ]);

        // Assign same permissions to pelanggan role as customer
        $pelangganRole->givePermissionTo([
            'view user profiles',
            'edit user profiles',
            'create bookings',
            'view bookings',
            'edit bookings',
            'cancel bookings',
            'view services',
            'view transactions',
            'view dashboard',
            'view customer dashboard',
        ]);

        $this->command->info('Comprehensive user management permissions seeded successfully!');
        $this->command->info('Total permissions created: '.count($allPermissions));
        $this->command->info('Admin role has full access to all permissions');
        $this->command->info('Pegawai role has operational access');
        $this->command->info('Customer/Pelanggan roles have limited customer access');
    }
}
