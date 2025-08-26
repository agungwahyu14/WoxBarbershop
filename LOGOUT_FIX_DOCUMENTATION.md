# Fix untuk Error ERR_CONNECTION_CLOSED saat Logout

## Masalah

Error `ERR_CONNECTION_CLOSED` terjadi saat logout dari halaman `http://127.0.0.1:8000/admin/roles` karena ada auto-refresh interval yang tetap berjalan setelah user logout, mencoba mengakses endpoint yang memerlukan autentikasi.

## Penyebab

Beberapa halaman admin memiliki `setInterval()` yang melakukan AJAX request secara berkala untuk me-refresh tabel:

-   `admin/roles/index.blade.php` - refresh setiap 30 detik
-   `admin/users/index.blade.php` - refresh setiap 30 detik
-   `admin/hairstyles/index.blade.php` - refresh setiap 30 detik
-   `admin/bookings/index.blade.php` - refresh setiap 30 detik
-   `admin/dashboard.blade.php` - refresh setiap 3 detik

## Solusi yang Diterapkan

### 1. Pengecekan Autentikasi pada Auto-Refresh

Mengubah semua `setInterval()` untuk melakukan pengecekan autentikasi sebelum AJAX request:

```javascript
setInterval(function() {
    // Check if user is still authenticated
    fetch('{{ route('route.name') }}', {
        method: 'HEAD',
        credentials: 'same-origin'
    }).then(response => {
        if (response.ok && !window.isLoggingOut) {
            // User is still authenticated, reload table
            table.ajax.reload(null, false);
        } else {
            // User is not authenticated, clear interval and redirect
            clearInterval(window.refreshInterval);
            if (response.status === 401 || response.status === 419) {
                window.location.href = '{{ route('login') }}';
            }
        }
    }).catch(error => {
        // Connection error, clear interval
        clearInterval(window.refreshInterval);
        console.log('Auto-refresh stopped due to connection error');
    });
}, 30000);
```

### 2. Global AJAX Error Handler

Ditambahkan handler error global di `admin/layouts/app.blade.php`:

```javascript
$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
    if (jqXHR.status === 401 || jqXHR.status === 419) {
        // Session expired or CSRF token mismatch
        console.log('Authentication error detected, redirecting to login...');
        window.location.href = '{{ route('login') }}';
    } else if (jqXHR.status === 0 && jqXHR.statusText === 'error') {
        // Connection closed/network error
        console.log('Network connection error detected');
    }
});
```

### 3. Cleanup saat Logout

Ditambahkan handler untuk form logout yang membersihkan semua interval aktif:

```javascript
$("#logout-form").on("submit", function () {
    // Clear all active intervals before logout
    if (window.refreshInterval) {
        clearInterval(window.refreshInterval);
    }
    if (window.dashboardRefreshInterval) {
        clearInterval(window.dashboardRefreshInterval);
    }

    // Set a flag to prevent new intervals from starting
    window.isLoggingOut = true;
});
```

### 4. Setup CSRF Token Global

Ditambahkan setup CSRF token untuk semua AJAX request:

```javascript
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        "X-Requested-With": "XMLHttpRequest",
    },
});
```

### 5. Cleanup saat Page Unload

Ditambahkan event listener untuk membersihkan interval saat halaman ditutup:

```javascript
window.addEventListener("beforeunload", function () {
    clearInterval(window.refreshInterval);
    if (window.dashboardRefreshInterval) {
        clearInterval(window.dashboardRefreshInterval);
    }
});
```

## File yang Dimodifikasi

1. `resources/views/admin/roles/index.blade.php`
2. `resources/views/admin/users/index.blade.php`
3. `resources/views/admin/hairstyles/index.blade.php`
4. `resources/views/admin/bookings/index.blade.php`
5. `resources/views/admin/dashboard.blade.php`
6. `resources/views/admin/layouts/app.blade.php`
7. `resources/views/admin/partials/navbar.blade.php`

## Hasil

-   Error `ERR_CONNECTION_CLOSED` setelah logout sudah tidak muncul lagi
-   Auto-refresh berhenti secara otomatis saat user logout
-   Jika session expire, user akan otomatis diredirect ke login
-   Network error ditangani dengan baik tanpa menyebabkan error di console
-   Semua interval dibersihkan dengan proper untuk mencegah memory leak

## Testing

1. Login ke admin panel
2. Masuk ke halaman `admin/roles`
3. Tunggu beberapa detik hingga auto-refresh berjalan
4. Klik logout
5. Verifikasi tidak ada error di Network tab browser
6. Verifikasi tidak ada request yang dilakukan setelah logout berhasil
