# 🏗️ **WOX Barbershop - Struktur Direktori Laravel**

> **Dokumentasi lengkap struktur direktori dan arsitektur aplikasi WOX Barbershop Management System**

---

## 📋 **Daftar Isi**

1. [Overview Struktur](#overview-struktur)
2. [Direktori Utama](#direktori-utama)
3. [App Directory](#app-directory)
4. [Resources & Views](#resources--views)
5. [Database Structure](#database-structure)
6. [Public Assets](#public-assets)
7. [Configuration Files](#configuration-files)
8. [Array Structure](#array-structure)

---

## 🎯 **Overview Struktur**

```
tugas-akhir/
├── 📁 app/                     # Core aplikasi (Models, Controllers, Services)
├── 📁 bootstrap/               # Bootstrap framework Laravel
├── 📁 config/                  # File konfigurasi aplikasi
├── 📁 database/                # Migrasi, seeder, factory
├── 📁 public/                  # Asset publik (CSS, JS, Images)
├── 📁 resources/               # Views, CSS, JS sumber
├── 📁 routes/                  # Route definitions
├── 📁 storage/                 # File storage dan logs
├── 📁 tests/                   # Unit & Feature tests
├── 📁 vendor/                  # Dependencies Composer
├── 📄 artisan                  # Laravel CLI
├── 📄 composer.json            # Dependencies PHP
├── 📄 package.json             # Dependencies Node.js
└── 📄 README.md                # Dokumentasi utama
```

---

## 📦 **Array Structure - Complete Project Structure**

```php
<?php

$projectStructure = [
    'root' => [
        'files' => [
            'artisan',                          // Laravel command-line interface
            'composer.json',                    // PHP package dependencies
            'composer.lock',                    // Lock file untuk composer
            'package.json',                     // Node.js dependencies
            'postcss.config.js',               // PostCSS configuration
            'tailwind.config.js',              // Tailwind CSS configuration
            'vite.config.js',                  // Vite build tool configuration
            'phpunit.xml',                     // PHPUnit testing configuration
            'forge.yaml',                      // Laravel Forge deployment
            'fix_admin_routes.sh',             // Script perbaikan admin routes
            'README.md',                       // Dokumentasi utama
            'PROJECT_STRUCTURE.md',            // Dokumentasi struktur (file ini)
        ],
        'documentation' => [
            'CLASS_DIAGRAM.md',                // Class diagram sistem
            'ERD_DIAGRAM.md',                  // Entity Relationship Diagram
            'SYSTEM_FLOWCHART.md',             // System flowchart lama
            'SYSTEM_FLOWCHART_NEW.md',         // System flowchart terbaru
            'EXPORT_DOCUMENTATION.md',         // Dokumentasi export features
            'LAPORAN_PERBAIKAN.md',            // Laporan bug fixes
            'LOGOUT_AJAX_FIX.md',              // Dokumentasi fix logout
            'LOGOUT_FIX_DOCUMENTATION.md',     // Detail logout fixes
            'LOYALTY_CONTROLLER_FIX.md',       // Fix loyalty controller
            'NETWORK_ERROR_DOCUMENTATION.md',  // Network error handling
        ],
    ],

    'app' => [
        'Console' => [
            'Kernel.php',                      // Console kernel untuk commands
        ],
        'DataTables' => [
            // Empty - DataTables processors dapat ditambahkan di sini
        ],
        'Enums' => [
            'PaymentMethod.php',               // Payment method enums
            'TransactionStatus.php',           // Transaction status enums
        ],
        'Exceptions' => [
            'Handler.php',                     // Global exception handler
        ],
        'Exports' => [
            'BookingsExport.php',              // Export data booking
            'HairstylesExport.php',            // Export data hairstyles
            'LoyaltyExport.php',               // Export data loyalty
            'ServicesExport.php',              // Export data services
            'TransactionsExport.php',          // Export data transaksi
            'UsersExport.php',                 // Export data users
        ],
        'Http' => [
            'Kernel.php',                      // HTTP kernel middleware
            'Controllers' => [
                'Controller.php',              // Base controller
                'BookingController.php',       // Booking management
                'PaymentController.php',       // Payment processing dengan name & email
                'MidtransCallbackController.php', // Midtrans webhook handler
                'ProfileController.php',       // User profile dengan photo upload
                'DashboardController.php',     // Dashboard controller
                'RecommendationController.php', // Recommendation system
                'Admin' => [
                    'HairstyleController.php',     // Admin hairstyle management
                    'LoyaltyController.php',       // Admin loyalty management
                    'PermissionController.php',    // Admin permission management
                    'RoleController.php',          // Admin role management
                    'ServiceController.php',       // Admin service management
                    'TransactionController.php',   // Admin transaction management
                    'UserController.php',          // Admin user management
                ],
                'Auth' => [
                    'AuthenticatedSessionController.php', // Login/logout dengan enhancements
                    'ConfirmablePasswordController.php',  // Password confirmation
                    'EmailVerificationNotificationController.php', // Email verification
                    'EmailVerificationPromptController.php', // Email prompt
                    'NewPasswordController.php',          // New password reset
                    'PasswordController.php',             // Password management
                    'PasswordResetLinkController.php',    // Password reset link
                    'RegisteredUserController.php',       // User registration
                    'VerifyEmailController.php',          // Email verification
                ],
            ],
            'Middleware' => [
                'AdminMiddleware.php',         // Admin access middleware
                'CheckUserActive.php',         // User is_active validation
                'LogUserActivity.php',         // User activity logging
            ],
            'Requests' => [
                'BookingRequest.php',          // Booking validation dengan business hours
                'TransactionRequest.php',      // Transaction validation
                'ProfileUpdateRequest.php',    // Profile update validation
                'ServiceRequest.php',          // Service validation
                'Auth' => [
                    'LoginRequest.php',            // Login request validation
                    'RegisterRequest.php',         // Register request validation
                ],
            ],
        ],
        'Mail' => [
            'MyEmail.php',                     // Email templates
        ],
        'Models' => [
            'User.php',                        // User model dengan is_active, last_login_at, profile_photo
            'Booking.php',                     // Booking model dengan relationship
            'Service.php',                     // Service model
            'Hairstyle.php',                   // Hairstyle model
            'Transaction.php',                 // Transaction model dengan name & email
            'Loyalty.php',                     // Loyalty system model
            'Criteria.php',                    // Criteria model
            'Dashboard.php',                   // Dashboard model
        ],
        'Policies' => [
            'BookingPolicy.php',               // Booking authorization
            'UserPolicy.php',                  // User authorization
            'TransactionPolicy.php',           // Transaction authorization
        ],
        'Providers' => [
            'AppServiceProvider.php',          // App service provider
            'AuthServiceProvider.php',         // Auth service provider
            'EventServiceProvider.php',        // Event service provider
            'RouteServiceProvider.php',        // Route service provider
        ],
        'Rules' => [
            'BusinessHoursRule.php',           // Business hours validation rule
            'UniqueBookingSlotRule.php',       // Unique booking slot rule
        ],
        'Services' => [
            'BookingService.php',              // Enhanced booking business logic
            'MidtransService.php',             // Payment processing service
            'NotificationService.php',         // Notification system
            'CacheService.php',                // Cache management service
            'ExportService.php',               // Data export logic
        ],
        'Traits' => [
            'HasLoyaltyPoints.php',            // Loyalty points trait
            'Loggable.php',                    // Logging trait
            'Cacheable.php',                   // Cache trait
        ],
        'View' => [
            'Composers' => [
                // View composer classes
            ],
        ],
    ],

    'bootstrap' => [
        'app.php',                         // Bootstrap application
        'cache' => [
            // Bootstrap cache files
        ],
    ],

    'config' => [
        'app.php',                         // Main application config
        'auth.php',                        // Authentication config
        'broadcasting.php',                // Broadcasting config
        'cache.php',                       // Cache configuration
        'cors.php',                        // CORS configuration
        'database.php',                    // Database connections
        'datatables-buttons.php',          // DataTables config
        'excel.php',                       // Excel export config
        'filesystems.php',                 // File storage config
        'hashing.php',                     // Password hashing
        'logging.php',                     // Logging configuration
        'mail.php',                        // Mail configuration
        'midtrans.php',                    // Midtrans payment config
        'permission.php',                  // User permissions
        'queue.php',                       // Queue configuration
        'sanctum.php',                     // API authentication
        'services.php',                    // Third-party services
        'session.php',                     // Session configuration
        'view.php',                        // View configuration
    ],

    'database' => [
        'factories' => [
            'UserFactory.php',                 // User factory untuk testing
            'BookingFactory.php',              // Booking factory
            'ServiceFactory.php',              // Service factory
            'TransactionFactory.php',          // Transaction factory
            'HairstyleFactory.php',            // Hairstyle factory
        ],
        'migrations' => [
            '2014_10_12_000000_create_users_table.php',           // Users table
            '2025_06_28_060903_create_transactions_table.php',    // Transactions table
            '2025_07_22_033016_add_payment_fields_to_transactions_table.php', // Payment fields
            '2025_07_29_060324_add_midtrans_columns_to_bookings_table.php',   // Midtrans columns
            'create_bookings_table.php',       // Bookings table
            'create_services_table.php',       // Services table
            'create_hairstyles_table.php',     // Hairstyles table
            'create_loyalty_table.php',        // Loyalty table
            'add_user_enhancements.php',       // User enhancements (is_active, last_login_at, profile_photo)
        ],
        'seeders' => [
            'DatabaseSeeder.php',              // Main seeder
            'UserSeeder.php',                  // User data seeder
            'BookingSeeder.php',               // Booking data seeder
            'ServiceSeeder.php',               // Service data seeder
            'HairstyleSeeder.php',             // Hairstyle data seeder
            'TransactionSeeder.php',           // Transaction data seeder
            'AdminSeeder.php',                 // Admin user seeder
        ],
    ],

    'public' => [
        'build' => [
            'assets' => [],                    // Compiled assets
            'manifest.json',                   // Asset manifest
        ],
        'css' => [
            'app.css',                         // Compiled CSS
            'admin.css',                       // Admin CSS
        ],
        'js' => [
            'app.js',                          // Main JavaScript
            'booking-form-validator.js',       // Enhanced validation dengan SweetAlert
            'admin-dashboard.js',              // Admin dashboard scripts
            'sweetalert-integration.js',       // SweetAlert integration
        ],
        'images' => [
            'logos' => [],                     // Logo images
            'services' => [],                  // Service images
            'hairstyles' => [],                // Hairstyle images
            'profile-photos' => [],            // User profile photos
        ],
        'img' => [
            'hero-bg.jpg',                     // Hero background
            'about-us.jpg',                    // About us image
            'barbershop-interior.jpg',         // Interior image
        ],
        'storage' => [
            'app' => [
                'public' => [],                // Public storage symlink
            ],
        ],
        'vendor' => [
            'sweetalert2' => [],               // SweetAlert2 library
            'datatables' => [],                // DataTables assets
            'fontawesome' => [],               // FontAwesome icons
        ],
        'index.php',                           // Laravel entry point
        'favicon.ico',                         // Site favicon
        'robots.txt',                          // SEO robots file
    ],

    'resources' => [
        'css' => [
            'app.css',                         // Main CSS dengan Tailwind
        ],
        'js' => [
            'app.js',                          // Main JavaScript entry
            'booking-form-validator.js',       // Enhanced form validation
            'admin-dashboard.js',              // Admin dashboard scripts
            'sweetalert-config.js',            // SweetAlert configurations
        ],
        'views' => [
            // Layout Files
            'app.blade.php',                   // Main app layout dengan SweetAlert (layouts/), Admin layout (admin/layouts/)
            'guest.blade.php',                 // Guest layout (layouts/)
            'footer.blade.php',                // Footer component (layouts/)
            'head.blade.php',                  // Head component (layouts/)
            'navigation.blade.php',            // Navigation component (layouts/)
            'scripts.blade.php',               // Scripts component (layouts/)
            'sidebar.blade.php',               // Admin sidebar (admin/layouts/)

            // Authentication Views
            'login.blade.php',                 // Login page (auth/)
            'register.blade.php',              // Registration page (auth/)
            'forgot-password.blade.php',       // Password reset (auth/)
            'confirm-password.blade.php',      // Password confirmation (auth/)
            'reset-password.blade.php',        // Reset password form (auth/)
            'verify-email.blade.php',          // Email verification (auth/)

            // Dashboard Views
            'dashboard.blade.php',             // Main dashboard (root/), Admin dashboard (admin/)
            'welcome.blade.php',               // Welcome page (root/)
            'rekomendasi.blade.php',           // Recommendation page (root/)

            // CRUD Views (Combined from multiple modules)
            'index.blade.php',                 // List views: bookings/, admin/bookings/, admin/users/, admin/services/, admin/hairstyles/, admin/transactions/, transactions/
            'create.blade.php',                // Create forms: admin/users/, admin/services/, admin/hairstyles/
            'edit.blade.php',                  // Edit forms: bookings/, admin/bookings/, admin/users/, admin/services/, admin/hairstyles/, profile/
            'show.blade.php',                  // Detail views: bookings/, admin/bookings/, admin/users/, transactions/, admin/transactions/

            // Transaction & Receipt
            'transaction.blade.php',           // Receipt template (receipt/)

            // Reusable Components
            'application-logo.blade.php',      // App logo component (components/)
            'auth-session-status.blade.php',   // Auth status component (components/)
            'danger-button.blade.php',         // Danger button component (components/)
            'dropdown-link.blade.php',         // Dropdown link component (components/)
            'dropdown.blade.php',              // Dropdown component (components/)
            'input-error.blade.php',           // Input error component (components/)
            'input-label.blade.php',           // Input label component (components/)
            'modal.blade.php',                 // Modal component (components/)
            'nav-link.blade.php',              // Navigation link component (components/)
            'primary-button.blade.php',        // Primary button component (components/)
            'responsive-nav-link.blade.php',   // Responsive nav link component (components/)
            'secondary-button.blade.php',      // Secondary button component (components/)
            'text-input.blade.php',            // Text input component (components/)

            // Additional Folders (non-file content)
            'folders' => [
                'admin/analytics/',             // Admin analytics views
                'admin/components/',            // Admin-specific components
                'admin/exports/',               // Admin export views
                'admin/loyalty/',               // Admin loyalty management
                'admin/partials/',              // Admin partial views
                'admin/permissions/',           // Admin permission management
                'admin/profile/',               // Admin profile views
                'admin/recommendations/',       // Admin recommendation views
                'admin/reports/',               // Admin report views
                'admin/roles/',                 // Admin role management
                'admin/settings/',              // Admin settings views
                'profile/partials/',            // Profile partial views
                'vendor/',                      // Vendor views (Laravel packages)
            ],
        ],
    ],

    'routes' => [
        'web.php',                         // Web routes (frontend)
        'api.php',                         // API routes dengan validation
        'auth.php',                        // Authentication routes
        'channels.php',                    // Broadcasting channels
        'console.php',                     // Console commands
    ],

    'storage' => [
        'app' => [
            'private' => [],                   // Private file storage
            'public' => [
                'profile-photos' => [],        // User profile photos
                'receipts' => [],              // Payment receipts
                'exports' => [],               // Generated exports
            ],
        ],
        'framework' => [
            'cache' => [],                     // Framework cache
            'sessions' => [],                  // Session files
            'views' => [],                     // Compiled views
        ],
        'logs' => [
            'laravel.log',                     // Main log file
            'booking.log',                     // Booking-specific logs
            'payment.log',                     // Payment transaction logs
        ],
    ],

    'tests' => [
        'CreatesApplication.php',              // Testing application setup
        'TestCase.php',                        // Base test case
        'Feature' => [
            'AuthTest.php',                    // Authentication testing
            'BookingTest.php',                 // Booking functionality
            'PaymentTest.php',                 // Payment processing tests
            'AdminTest.php',                   // Admin panel tests
            'UserManagementTest.php',          // User management tests
        ],
        'Unit' => [
            'UserTest.php',                    // User model tests
            'BookingServiceTest.php',          // Booking service tests
            'ValidationTest.php',              // Validation rule tests
            'MidtransServiceTest.php',         // Payment service tests
        ],
    ],

    'vendor' => [
        'autoload.php',                        // Composer autoloader
        'laravel' => [],                       // Laravel framework
        'midtrans' => [],                      // Midtrans payment
        'barryvdh' => [],                      // PDF & Excel packages
        'maatwebsite' => [],                   // Excel package
        'yajra' => [],                         // DataTables package
        'spatie' => [],                        // Permission package
        // ... other vendor packages
    ],
];

// Display structure as formatted array
echo '<pre>';
print_r($projectStructure);
echo '</pre>';
?>
```

---

## 📂 **Direktori Utama**

### **🔧 Root Files**

```
├── artisan                     # Laravel command-line interface
├── composer.json               # PHP package dependencies
├── composer.lock               # Lock file untuk composer
├── package.json                # Node.js dependencies
├── postcss.config.js           # PostCSS configuration
├── tailwind.config.js          # Tailwind CSS configuration
├── vite.config.js              # Vite build tool configuration
├── phpunit.xml                 # PHPUnit testing configuration
├── forge.yaml                  # Laravel Forge deployment
└── fix_admin_routes.sh         # Script perbaikan admin routes
```

### **📚 Documentation Files**

```
├── CLASS_DIAGRAM.md            # Class diagram sistem
├── ERD_DIAGRAM.md             # Entity Relationship Diagram
├── SYSTEM_FLOWCHART.md        # System flowchart lama
├── SYSTEM_FLOWCHART_NEW.md    # System flowchart terbaru
├── EXPORT_DOCUMENTATION.md    # Dokumentasi export features
├── LAPORAN_PERBAIKAN.md       # Laporan bug fixes
├── LOGOUT_AJAX_FIX.md         # Dokumentasi fix logout
├── LOGOUT_FIX_DOCUMENTATION.md # Detail logout fixes
├── LOYALTY_CONTROLLER_FIX.md   # Fix loyalty controller
└── NETWORK_ERROR_DOCUMENTATION.md # Network error handling
```

---

## 🏗️ **App Directory**

```
app/
├── 📁 Console/
│   └── Kernel.php              # Console kernel untuk commands
│
├── 📁 DataTables/              # DataTables processors
│
├── 📁 Enums/                   # Enum definitions
│   ├── PaymentMethod.php       # Payment method enums
│   └── TransactionStatus.php   # Transaction status enums
│
├── 📁 Exceptions/
│   └── Handler.php             # Global exception handler
│
├── 📁 Exports/                 # Excel export classes
│   ├── BookingsExport.php      # Export data booking
│   ├── HairstylesExport.php    # Export data hairstyles
│   ├── LoyaltyExport.php       # Export data loyalty
│   ├── ServicesExport.php      # Export data services
│   ├── TransactionsExport.php  # Export data transaksi
│   └── UsersExport.php         # Export data users
│
├── 📁 Http/
│   ├── Kernel.php              # HTTP kernel middleware
│   │
│   ├── 📁 Controllers/         # Application controllers
│   │   ├── Auth/               # Authentication controllers
│   │   ├── Admin/              # Admin panel controllers
│   │   ├── Api/                # API controllers
│   │   ├── BookingController.php
│   │   ├── PaymentController.php
│   │   ├── MidtransCallbackController.php
│   │   ├── ProfileController.php
│   │   ├── DashboardController.php
│   │   └── HomeController.php
│   │
│   ├── 📁 Middleware/          # Custom middleware
│   │   ├── AdminMiddleware.php
│   │   ├── CheckUserActive.php
│   │   └── LogUserActivity.php
│   │
│   └── 📁 Requests/            # Form request validation
│       ├── BookingRequest.php
│       ├── TransactionRequest.php
│       ├── ProfileUpdateRequest.php
│       └── Auth/
│
├── 📁 Mail/
│   └── MyEmail.php             # Email templates
│
├── 📁 Models/                  # Eloquent models
│   ├── User.php                # User model dengan enhancements
│   ├── Booking.php             # Booking model
│   ├── Service.php             # Service model
│   ├── Hairstyle.php           # Hairstyle model
│   ├── Transaction.php         # Transaction model
│   ├── Loyalty.php             # Loyalty system model
│   ├── Criteria.php            # Criteria model
│   └── Dashboard.php           # Dashboard model
│
├── 📁 Policies/                # Authorization policies
│   ├── BookingPolicy.php
│   ├── UserPolicy.php
│   └── TransactionPolicy.php
│
├── 📁 Providers/               # Service providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RouteServiceProvider.php
│
├── 📁 Rules/                   # Custom validation rules
│   ├── BusinessHoursRule.php
│   └── UniqueBookingSlotRule.php
│
├── 📁 Services/                # Business logic services
│   ├── BookingService.php      # Enhanced booking logic
│   ├── MidtransService.php     # Payment processing
│   ├── NotificationService.php # Notification system
│   ├── CacheService.php        # Cache management
│   └── ExportService.php       # Data export logic
│
├── 📁 Traits/                  # Reusable traits
│   ├── HasLoyaltyPoints.php
│   ├── Loggable.php
│   └── Cacheable.php
│
└── 📁 View/                    # View composers
    └── Composers/
```

---

## 🎨 **Resources & Views**

```
resources/
├── 📁 css/                     # Source CSS files
│   └── app.css                 # Main CSS dengan Tailwind
│
├── 📁 js/                      # Source JavaScript files
│   ├── app.js                  # Main JavaScript entry
│   ├── booking-form-validator.js # Enhanced form validation
│   ├── admin-dashboard.js      # Admin dashboard scripts
│   └── sweetalert-config.js    # SweetAlert configurations
│
└── 📁 views/                   # Blade templates
    ├── 📁 layouts/             # Layout templates
    │   ├── app.blade.php       # Main app layout dengan SweetAlert
    │   ├── admin.blade.php     # Admin layout
    │   └── guest.blade.php     # Guest layout
    │
    ├── 📁 auth/                # Authentication views
    │   ├── login.blade.php
    │   ├── register.blade.php
    │   └── forgot-password.blade.php
    │
    ├── 📁 admin/               # Admin panel views
    │   ├── 📁 layouts/
    │   ├── 📁 bookings/
    │   ├── 📁 users/
    │   ├── 📁 services/
    │   ├── 📁 transactions/
    │   └── dashboard.blade.php
    │
    ├── 📁 bookings/            # Booking management views
    │   ├── index.blade.php     # Booking list dengan SweetAlert
    │   ├── create.blade.php    # Create booking form
    │   ├── show.blade.php      # Booking detail
    │   └── edit.blade.php      # Edit booking
    │
    ├── 📁 transactions/        # Transaction views
    │   ├── index.blade.php     # Transaction list dengan name & email
    │   ├── show.blade.php      # Transaction detail enhanced
    │   └── receipt.blade.php   # Receipt template
    │
    ├── 📁 profile/             # User profile views
    │   ├── edit.blade.php      # Profile edit dengan photo upload
    │   └── show.blade.php      # Profile display
    │
    ├── 📁 components/          # Reusable components
    │   ├── alert.blade.php
    │   ├── modal.blade.php
    │   └── sweetalert.blade.php
    │
    ├── 📁 emails/              # Email templates
    │   ├── booking-confirmation.blade.php
    │   └── payment-success.blade.php
    │
    └── 📁 errors/              # Error pages
        ├── 404.blade.php
        ├── 419.blade.php
        └── 500.blade.php
```

---

## 🗄️ **Database Structure**

```
database/
├── 📁 factories/               # Model factories untuk testing
│   ├── UserFactory.php
│   ├── BookingFactory.php
│   ├── ServiceFactory.php
│   ├── TransactionFactory.php
│   └── HairstyleFactory.php
│
├── 📁 migrations/              # Database migrations
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2025_06_28_060903_create_transactions_table.php
│   ├── 2025_07_22_033016_add_payment_fields_to_transactions_table.php
│   ├── 2025_07_29_060324_add_midtrans_columns_to_bookings_table.php
│   ├── create_bookings_table.php
│   ├── create_services_table.php
│   ├── create_hairstyles_table.php
│   ├── create_loyalty_table.php
│   └── add_user_enhancements.php  # is_active, last_login_at, profile_photo
│
└── 📁 seeders/                 # Database seeders
    ├── DatabaseSeeder.php      # Main seeder
    ├── UserSeeder.php          # User data seeder
    ├── BookingSeeder.php       # Booking data seeder
    ├── ServiceSeeder.php       # Service data seeder
    ├── HairstyleSeeder.php     # Hairstyle data seeder
    ├── TransactionSeeder.php   # Transaction data seeder
    └── AdminSeeder.php         # Admin user seeder
```

---

## 🌐 **Public Assets**

```
public/
├── 📁 build/                   # Vite compiled assets
│   ├── 📁 assets/
│   └── manifest.json
│
├── 📁 css/                     # Compiled CSS
│   ├── app.css
│   └── admin.css
│
├── 📁 js/                      # Public JavaScript
│   ├── app.js
│   ├── booking-form-validator.js # Enhanced validation dengan SweetAlert
│   ├── admin-dashboard.js
│   └── sweetalert-integration.js
│
├── 📁 images/                  # Application images
│   ├── 📁 logos/
│   ├── 📁 services/
│   ├── 📁 hairstyles/
│   └── 📁 profile-photos/      # User profile photos
│
├── 📁 img/                     # Static images
│   ├── hero-bg.jpg
│   ├── about-us.jpg
│   └── barbershop-interior.jpg
│
├── 📁 storage/                 # Symlinked storage
│   └── app/
│       └── public/
│
├── 📁 vendor/                  # Third-party assets
│   ├── sweetalert2/           # SweetAlert2 library
│   ├── datatables/            # DataTables assets
│   └── fontawesome/           # FontAwesome icons
│
├── index.php                   # Laravel entry point
├── favicon.ico                 # Site favicon
└── robots.txt                  # SEO robots file
```

---

## ⚙️ **Configuration Files**

```
config/
├── app.php                     # Main application config
├── auth.php                    # Authentication config
├── broadcasting.php            # Broadcasting config
├── cache.php                   # Cache configuration
├── cors.php                    # CORS configuration
├── database.php                # Database connections
├── datatables-buttons.php      # DataTables config
├── excel.php                   # Excel export config
├── filesystems.php             # File storage config
├── hashing.php                 # Password hashing
├── logging.php                 # Logging configuration
├── mail.php                    # Mail configuration
├── midtrans.php                # Midtrans payment config
├── permission.php              # User permissions
├── queue.php                   # Queue configuration
├── sanctum.php                 # API authentication
├── services.php                # Third-party services
├── session.php                 # Session configuration
└── view.php                    # View configuration
```

---

## 🛤️ **Routes Structure**

```
routes/
├── web.php                     # Web routes (frontend)
├── api.php                     # API routes dengan validation
├── auth.php                    # Authentication routes
├── channels.php                # Broadcasting channels
└── console.php                 # Console commands
```

---

## 💾 **Storage Structure**

```
storage/
├── 📁 app/
│   ├── 📁 private/             # Private file storage
│   └── 📁 public/              # Public file storage
│       ├── 📁 profile-photos/  # User profile photos
│       ├── 📁 receipts/        # Payment receipts
│       └── 📁 exports/         # Generated exports
│
├── 📁 framework/
│   ├── 📁 cache/               # Framework cache
│   ├── 📁 sessions/            # Session files
│   └── 📁 views/               # Compiled views
│
└── 📁 logs/                    # Application logs
    ├── laravel.log             # Main log file
    ├── booking.log             # Booking-specific logs
    └── payment.log             # Payment transaction logs
```

---

## 🧪 **Tests Structure**

```
tests/
├── CreatesApplication.php      # Testing application setup
├── TestCase.php                # Base test case
│
├── 📁 Feature/                 # Feature tests
│   ├── AuthTest.php            # Authentication testing
│   ├── BookingTest.php         # Booking functionality
│   ├── PaymentTest.php         # Payment processing tests
│   ├── AdminTest.php           # Admin panel tests
│   └── UserManagementTest.php  # User management tests
│
└── 📁 Unit/                    # Unit tests
    ├── UserTest.php            # User model tests
    ├── BookingServiceTest.php  # Booking service tests
    ├── ValidationTest.php      # Validation rule tests
    └── MidtransServiceTest.php # Payment service tests
```

---

## 📦 **Vendor Dependencies**

### **Backend (PHP - Composer)**

```json
{
    "laravel/framework": "^10.0",
    "laravel/sanctum": "^3.2",
    "laravel/tinker": "^2.8",
    "barryvdh/laravel-dompdf": "^2.0",
    "maatwebsite/excel": "^3.1",
    "yajra/laravel-datatables": "^10.0",
    "midtrans/midtrans-php": "^2.5",
    "spatie/laravel-permission": "^5.10"
}
```

### **Frontend (Node.js - NPM)**

```json
{
    "@tailwindcss/forms": "^0.5.2",
    "alpinejs": "^3.4.2",
    "autoprefixer": "^10.4.2",
    "axios": "^1.1.2",
    "laravel-vite-plugin": "^0.7.2",
    "postcss": "^8.4.6",
    "sweetalert2": "^11.7.0",
    "tailwindcss": "^3.2.0",
    "vite": "^4.0.0"
}
```

---

## 🔧 **Key Features & Enhancements**

### **✨ Recent Enhancements**

-   **User Management**: `is_active`, `last_login_at`, `profile_photo` fields
-   **Business Hours Validation**: Smart booking validation with SweetAlert
-   **Enhanced Transaction System**: Name & email tracking in transactions
-   **SweetAlert Integration**: Beautiful error handling and notifications
-   **CSRF Error Resolution**: Fixed validation conflicts
-   **Comprehensive Logging**: Enhanced logging throughout the system

### **🎯 Core Functionality**

-   **Booking System**: Complete booking management with queue numbers
-   **Payment Integration**: Midtrans payment gateway with multiple methods
-   **Admin Panel**: Full admin dashboard with DataTables
-   **User Authentication**: Enhanced with activity tracking
-   **Export Features**: Excel exports for all major entities
-   **Responsive Design**: Mobile-first Tailwind CSS design

---

## 📝 **Development Guidelines**

### **🏗️ Architecture Patterns**

-   **MVC Pattern**: Model-View-Controller architecture
-   **Service Layer**: Business logic in dedicated service classes
-   **Repository Pattern**: Data access abstraction
-   **Event-Driven**: Laravel events and listeners
-   **Middleware Pipeline**: Request filtering and transformation

### **💡 Best Practices**

-   **Form Validation**: Comprehensive validation with custom rules
-   **Error Handling**: Graceful error handling with logging
-   **Caching Strategy**: Smart caching for performance
-   **Security**: CSRF protection, input sanitization
-   **Testing**: Feature and unit test coverage

---

## 📚 **Additional Resources**

-   **Laravel Documentation**: https://laravel.com/docs/10.x
-   **Tailwind CSS**: https://tailwindcss.com/docs
-   **SweetAlert2**: https://sweetalert2.github.io
-   **Midtrans Documentation**: https://docs.midtrans.com

---

_Dibuat dengan ❤️ untuk WOX Barbershop Management System_  
_Terakhir diperbarui: 3 September 2025_
