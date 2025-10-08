# Sistem Multi-Bahasa Laravel WOX Barbershop

## 📋 Overview

Sistem multi-bahasa (Indonesia-Inggris) yang diimplementasikan menggunakan Laravel Localization dengan session storage untuk menyimpan preferensi bahasa user.

## 🛠 Komponen Sistem

### 1. **File Bahasa (Language Files)**

```
resources/lang/
├── id/
│   ├── auth.php
│   └── general.php
└── en/
    ├── auth.php
    └── general.php
```

**Contoh penggunaan di Blade:**

```blade
<h1>{{ __('general.welcome_message') }}</h1>
<button>{{ __('auth.login') }}</button>
```

### 2. **Controller (LanguageController)**

-   **Location:** `app/Http/Controllers/LanguageController.php`
-   **Methods:**
    -   `switchLanguage($language)` - Switch bahasa via URL
    -   `switchLanguageAjax()` - Switch bahasa via AJAX
    -   `getLanguageData()` - Get current language info
    -   `getCurrentLanguage()` - Helper method
    -   `getAvailableLanguages()` - Helper method

### 3. **Middleware (SetLocale)**

-   **Location:** `app/Http/Middleware/SetLocale.php`
-   **Prioritas Bahasa:**
    1. URL parameter (?lang=en)
    2. User preference (database)
    3. Session storage
    4. Browser Accept-Language header
    5. Default (Indonesia)

### 4. **Routes**

```php
// Language switching routes
Route::prefix('language')->name('language.')->group(function () {
    Route::get('/switch/{language}', [LanguageController::class, 'switchLanguage'])
        ->name('switch')
        ->where('language', 'id|en');

    Route::post('/switch', [LanguageController::class, 'switchLanguageAjax'])
        ->name('switch.ajax');

    Route::get('/current', [LanguageController::class, 'getLanguageData'])
        ->name('current');
});
```

### 5. **Language Switcher Component**

-   **Location:** `resources/views/components/language-switcher.blade.php`
-   **Features:**
    -   Dropdown dengan Alpine.js
    -   Flag dan nama bahasa
    -   Loading indicator
    -   Responsive design

## 🚀 Cara Penggunaan

### Menambahkan Translation di Blade

```blade
<!-- Text biasa -->
<h1>{{ __('general.welcome_message') }}</h1>

<!-- Dengan parameter -->
<p>{{ __('auth.throttle', ['seconds' => 60]) }}</p>

<!-- Dengan fallback -->
<span>{{ translate_or_fallback('general.some_key') }}</span>
```

### Menambahkan Language Switcher di View

```blade
<!-- Sertakan component -->
@include('components.language-switcher')
```

### Switch Language via JavaScript

```javascript
// AJAX switch
fetch("/language/switch", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    body: JSON.stringify({ language: "en" }),
})
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            location.reload(); // Reload untuk apply perubahan
        }
    });
```

## 📝 Menambah Translation Baru

### 1. Tambahkan ke File Language

**resources/lang/id/general.php:**

```php
'new_key' => 'Teks dalam Bahasa Indonesia',
```

**resources/lang/en/general.php:**

```php
'new_key' => 'Text in English',
```

### 2. Gunakan di Blade

```blade
{{ __('general.new_key') }}
```

## 🔧 Helper Functions

### Available Helper Functions:

```php
// Get current language info
$current = current_language();
// Returns: ['code' => 'id', 'name' => 'Indonesia', 'flag' => '🇮🇩']

// Get all languages
$languages = available_languages();

// Generate language switch URL
$url = language_route('en');

// Translation with fallback
$text = translate_or_fallback('general.some_key');
```

## ⚙️ Konfigurasi

### 1. Default Locale (config/app.php)

```php
'locale' => 'id',
'fallback_locale' => 'id',
```

### 2. Middleware Registration (app/Http/Kernel.php)

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\SetLocale::class,
    ],
];
```

### 3. Helper Registration (composer.json)

```json
"autoload": {
    "files": [
        "app/Helpers/LanguageHelper.php"
    ]
}
```

## 🔄 Session Management

### Sistem menyimpan preferensi bahasa di:

1. **Session:** `Session::get('locale')`
2. **Database:** `auth()->user()->language_preference` (optional)

### Prioritas pemilihan bahasa:

1. URL parameter (?lang=en)
2. User database preference
3. Session storage
4. Browser language
5. Default (Indonesia)

## 🎨 UI Components

### Language Switcher Features:

-   🇮🇩 Flag icons
-   📱 Responsive design
-   ⚡ Alpine.js powered dropdown
-   🔄 Loading indicator
-   ✅ Current language highlight

## 📱 Mobile Compatibility

Component language switcher sudah responsive:

-   Desktop: Tampilkan flag + nama lengkap
-   Mobile: Tampilkan flag + kode bahasa (ID/EN)

## 🚨 Testing

### Test Manual:

1. Buka aplikasi
2. Klik dropdown language switcher
3. Pilih bahasa
4. Verifikasi teks berubah
5. Refresh halaman - bahasa tetap tersimpan

### Test Programmatic:

```php
// Test dalam controller atau middleware
App::setLocale('en');
Session::put('locale', 'en');
```

## 🔧 Maintenance

### Menambah Bahasa Baru:

1. Buat folder `resources/lang/[kode_bahasa]/`
2. Copy file dari `id/` atau `en/`
3. Translate semua key
4. Update `LanguageController` dan helper
5. Update component language-switcher

### Update Translation:

1. Edit file di `resources/lang/[bahasa]/[file].php`
2. Tidak perlu restart server
3. Perubahan langsung terdeteksi

---

**Catatan:** Sistem ini menggunakan session untuk menyimpan preferensi bahasa user, jadi pastikan session storage dikonfigurasi dengan benar di Laravel.
