# Export Functionality Documentation

## Overview

Sistem export telah ditambahkan ke semua halaman admin untuk memungkinkan export data dalam format PDF, Excel, CSV, dan Print dengan filter per bulan.

## Fitur Yang Ditambahkan

### 1. **Export Formats**

-   **PDF**: Export ke file PDF dengan layout professional
-   **Excel**: Export ke file Excel (.xlsx) dengan formatting
-   **CSV**: Export ke file CSV untuk penggunaan dengan aplikasi spreadsheet
-   **Print**: View yang dapat dicetak langsung dari browser

### 2. **Filter Bulan/Tahun**

-   Filter data berdasarkan bulan (1-12)
-   Filter data berdasarkan tahun (5 tahun terakhir)
-   Opsi "Semua Data" untuk export keseluruhan

### 3. **Halaman Yang Sudah Dilengkapi Export**

-   ✅ **Users** (`/admin/users`)
-   ✅ **Services** (`/admin/services`)
-   ✅ **Hairstyles** (`/admin/hairstyles`)
-   ✅ **Bookings** (`/admin/bookings`)
-   ✅ **Transactions** (`/admin/transactions`)
-   ✅ **Loyalty** (`/admin/loyalty`)

## Struktur File

### Controllers

```
app/Http/Controllers/Admin/
├── UserController.php          # Enhanced dengan export methods
├── ServiceController.php       # Enhanced dengan export methods
├── HairstyleController.php     # Enhanced dengan export methods
├── TransactionController.php   # Enhanced dengan export methods
├── BookingController.php       # New admin controller
└── LoyaltyController.php       # New admin controller
```

### Exports Classes

```
app/Exports/
├── UsersExport.php
├── ServicesExport.php
├── HairstylesExport.php
├── BookingsExport.php
├── TransactionsExport.php
└── LoyaltyExport.php
```

### Views

```
resources/views/admin/exports/
├── layout.blade.php           # Shared export layout
├── users_pdf.blade.php        # PDF template for users
├── users_print.blade.php      # Print template for users
├── services_pdf.blade.php     # PDF template for services
├── services_print.blade.php   # Print template for services
├── hairstyles_pdf.blade.php   # PDF template for hairstyles
├── hairstyles_print.blade.php # Print template for hairstyles
├── bookings_pdf.blade.php     # PDF template for bookings
├── bookings_print.blade.php   # Print template for bookings
├── transactions_pdf.blade.php # PDF template for transactions
├── transactions_print.blade.php # Print template for transactions
├── loyalty_pdf.blade.php      # PDF template for loyalty
└── loyalty_print.blade.php    # Print template for loyalty
```

### Components

```
resources/views/admin/components/
└── export-toolbar.blade.php   # Reusable export toolbar component
```

## Cara Menggunakan

### 1. **Menambahkan Export Toolbar ke View**

```blade
@include('admin.components.export-toolbar', [
    'title' => 'Nama Data',
    'baseExportUrl' => route('admin.controller.index') . '/export/:type'
])
```

### 2. **Export Routes Pattern**

```php
// PDF Export
GET /admin/{resource}/export/pdf

// Excel Export
GET /admin/{resource}/export/excel

// CSV Export
GET /admin/{resource}/export/csv

// Print View
GET /admin/{resource}/print
```

### 3. **Query Parameters**

```
?month=1-12    # Filter bulan (opsional)
&year=2023     # Filter tahun (default: tahun ini)
```

## Contoh Penggunaan

### Export Users dengan Filter

```
GET /admin/users/export/pdf?month=3&year=2024
```

Akan export data users untuk bulan Maret 2024 dalam format PDF.

### Export All Services

```
GET /admin/services/export/excel
```

Akan export semua data services dalam format Excel.

## Technical Details

### Export Trait

File `app/Traits/ExportTrait.php` berisi method umum yang digunakan semua controller:

-   `exportPDF()` - Generate PDF export
-   `exportExcel()` - Generate Excel export
-   `exportCSV()` - Generate CSV export
-   `printView()` - Generate print view
-   `filterByMonth()` - Filter data per bulan

### Excel Export Classes

Menggunakan package `maatwebsite/excel` dengan fitur:

-   Auto sizing columns
-   Header styling
-   Data mapping/formatting
-   Month/year filtering

### PDF Generation

Menggunakan package `barryvdh/laravel-dompdf` dengan:

-   Landscape orientation
-   Professional styling
-   Header/footer information
-   Table formatting

## Customization

### Menambah Export ke Controller Baru

1. Use `ExportTrait` di controller
2. Implement method abstract: `getModelName()` dan `getExportTitle()`
3. Tambahkan export routes
4. Buat Export class di `app/Exports/`
5. Buat PDF/Print views di `resources/views/admin/exports/`

### Contoh Implementation

```php
use App\Traits\ExportTrait;

class NewController extends Controller
{
    use ExportTrait;

    protected function getModelName(): string
    {
        return 'new_model';
    }

    protected function getExportTitle(): string
    {
        return 'Data New Model';
    }
}
```

## Testing

Untuk test export functionality:

1. Login sebagai admin
2. Akses halaman admin (users/services/etc)
3. Gunakan dropdown Export
4. Pilih format dan filter bulan/tahun
5. Verify download/print bekerja dengan benar

## Dependencies

Pastikan package ini terinstall:

```bash
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
```

## Routes Summary

Total 24 export routes telah ditambahkan:

-   6 untuk Users (PDF, Excel, CSV, Print)
-   6 untuk Services (PDF, Excel, CSV, Print)
-   6 untuk Hairstyles (PDF, Excel, CSV, Print)
-   6 untuk Bookings (PDF, Excel, CSV, Print)
-   6 untuk Transactions (PDF, Excel, CSV, Print)
-   6 untuk Loyalty (PDF, Excel, CSV, Print)

Semua route menggunakan middleware `auth` dan ada dalam group admin.
