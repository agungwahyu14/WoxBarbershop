<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class SystemController extends Controller
{
    /**
     * Display backup & restore page
     */
    public function index()
    {
        return view('admin.system.index');
    }

    /**
     * Create database backup
     */
    public function backup(Request $request)
    {
        try {
            $type = $request->input('type', 'full'); // full or partial
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$type}_{$timestamp}.sql";
            
            // Get database configuration
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', 'localhost');
            
            // Create backup directory if not exists
            $backupPath = storage_path('app/backups');
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filePath = $backupPath . '/' . $filename;
            
            // Create mysqldump command
            $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database}";
            
            if ($type === 'partial') {
                // Only backup data, not structure
                $command .= " --no-create-info";
            }
            
            $command .= " > {$filePath}";
            
            // Execute backup command
            exec($command, $output, $return_code);
            
            if ($return_code === 0 && file_exists($filePath)) {
                // Return file for download
                return response()->download($filePath)->deleteFileAfterSend(true);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup database'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore database from backup
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip|max:102400' // max 100MB
        ]);

        try {
            $file = $request->file('backup_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store uploaded file temporarily
            $path = $file->storeAs('temp', $filename);
            $fullPath = storage_path('app/' . $path);
            
            // Basic validation for SQL file content
            if ($file->getClientOriginalExtension() === 'sql') {
                $content = file_get_contents($fullPath);
                if (strpos($content, 'CREATE TABLE') === false && strpos($content, 'INSERT INTO') === false) {
                    unlink($fullPath);
                    return response()->json([
                        'success' => false,
                        'message' => __('admin.invalid_sql_file')
                    ], 400);
                }
            }
            
            // Get database configuration
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', 'localhost');
            
            // Handle different file types
            if ($file->getClientOriginalExtension() === 'zip') {
                // Extract ZIP file (simplified - you might want to use ZipArchive)
                return response()->json([
                    'success' => false,
                    'message' => __('admin.zip_files_not_supported')
                ], 400);
            }
            
            // Check if mysql client is available
            exec('which mysql', $mysql_check, $mysql_return);
            if ($mysql_return !== 0) {
                // Clean up temporary file
                unlink($fullPath);
                return response()->json([
                    'success' => false,
                    'message' => __('admin.mysql_client_not_found')
                ], 500);
            }
            
            // Restore from SQL file
            $command = "mysql --user={$username} --password='{$password}' --host={$host} {$database} < {$fullPath} 2>&1";
            
            exec($command, $output, $return_code);
            
            // Clean up temporary file
            unlink($fullPath);
            
            if ($return_code === 0) {
                \Log::info('Database restore successful', [
                    'filename' => $filename,
                    'user_id' => auth()->id()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => __('admin.database_restored_successfully')
                ]);
            } else {
                \Log::error('Database restore failed', [
                    'filename' => $filename,
                    'return_code' => $return_code,
                    'output' => implode('\n', $output),
                    'user_id' => auth()->id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => __('admin.failed_to_restore_database') . 
                                 (app()->environment('local') ? ': ' . implode('\n', $output) : '')
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.restore_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup history
     */
    public function getBackupHistory()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                return response()->json([
                    'success' => true,
                    'backups' => []
                ]);
            }
            
            $files = scandir($backupPath);
            $backups = [];
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupPath . '/' . $file;
                    $backups[] = [
                        'filename' => $file,
                        'size' => $this->formatBytes(filesize($filePath)),
                        'created_at' => date('Y-m-d H:i:s', filemtime($filePath))
                    ];
                }
            }
            
            // Sort by creation date (newest first)
            usort($backups, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            
            return response()->json([
                'success' => true,
                'backups' => array_slice($backups, 0, 10) // Last 10 backups
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
