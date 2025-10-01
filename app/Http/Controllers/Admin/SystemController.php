<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * Display system settings
     */
    public function index()
    {
        $systemInfo = [
            'app_name' => config('app.name'),
            'app_version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
        ];

        return view('admin.system.index', compact('systemInfo'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'maintenance_mode' => 'boolean',
        ]);

        // Update .env file or config
        // This is a simplified version, in production you might want to use a proper package
        
        return redirect()->back()
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    /**
     * Backup database
     */
    public function backup()
    {
        try {
            // Create backup
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Run backup command (this is simplified)
            Artisan::call('backup:run');
            
            return redirect()->back()
                ->with('success', 'Backup berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return redirect()->back()
                ->with('success', 'Cache berhasil dibersihkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $size = \DB::select("SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB' 
                FROM information_schema.tables 
                WHERE table_schema='" . env('DB_DATABASE') . "'")[0];
            
            return $size->{'DB Size in MB'} . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get storage usage
     */
    private function getStorageUsage()
    {
        try {
            $bytes = 0;
            $path = storage_path('app');
            
            if (is_dir($path)) {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
                    $bytes += $file->getSize();
                }
            }
            
            return round($bytes / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
