# Fix untuk AJAX Logout Error

## Masalah

Error **"Network connection issue detected"** dengan URL `http://127.0.0.1:8000/logout` status 0 terjadi karena ada handler JavaScript yang mengubah form logout menjadi AJAX request, padahal logout seharusnya menggunakan form submission biasa.

## Penyebab

1. **Handler global di `app.blade.php`**: `$('form').on('submit')` menangkap SEMUA form termasuk logout
2. **Handler di `roles/index.blade.php`**: `$('form').on('submit')` mengubah semua form menjadi AJAX
3. **Handler di `permissions/index.blade.php`**: `$('form').on('submit')` mengubah semua form menjadi AJAX

## Error Flow

1. User klik logout button
2. Form logout disubmit
3. JavaScript handler menangkap event (`e.preventDefault()`)
4. Handler mengubah form submission menjadi AJAX request
5. AJAX request ke `/logout` gagal karena logout harus menggunakan form submission + redirect
6. Browser menampilkan status 0 error

## Solusi yang Diterapkan

### 1. Exclude Logout Form dari Handler Global

**File**: `resources/views/admin/layouts/app.blade.php`

```javascript
// SEBELUM (menangkap semua form)
$("form").on("submit", function () {
    // ...
});

// SESUDAH (exclude logout form)
$("form:not(#logout-form)").on("submit", function () {
    // ...
});
```

### 2. Exclude Logout Form dari Handler Roles

**File**: `resources/views/admin/roles/index.blade.php`

```javascript
// SEBELUM
$("form").on("submit", function (e) {
    e.preventDefault(); // Ini mengblok logout!
    // AJAX conversion
});

// SESUDAH
$("form:not(#logout-form)").on("submit", function (e) {
    e.preventDefault(); // Hanya untuk form non-logout
    // AJAX conversion
});
```

### 3. Exclude Logout Form dari Handler Permissions

**File**: `resources/views/admin/permissions/index.blade.php`

```javascript
// SEBELUM
$("form").on("submit", function (e) {
    e.preventDefault(); // Mengblok logout
    // AJAX conversion
});

// SESUDAH
$("form:not(#logout-form)").on("submit", function (e) {
    e.preventDefault(); // Hanya untuk form non-logout
    // AJAX conversion
});
```

## Hasil

✅ **Logout form tidak lagi diintercept** oleh AJAX handler  
✅ **Form logout berjalan normal** dengan POST submission  
✅ **Tidak ada lagi error "Network connection issue"** untuk logout  
✅ **Form lainnya tetap menggunakan AJAX** sesuai desain

## Testing

1. Masuk ke halaman admin (roles, permissions, dll)
2. Klik logout button
3. Verifikasi:
    - Tidak ada error di console browser
    - Logout berhasil dan redirect ke login page
    - Tidak ada network error di Network tab DevTools

## File yang Dimodifikasi

-   ✅ `resources/views/admin/layouts/app.blade.php`
-   ✅ `resources/views/admin/roles/index.blade.php`
-   ✅ `resources/views/admin/permissions/index.blade.php`

Logout sekarang bekerja dengan normal tanpa error AJAX!
