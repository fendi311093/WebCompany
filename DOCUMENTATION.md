# Dokumentasi Teknis Web Company

## Struktur Project

### 1. Panel Admin (Filament)

Lokasi: `app/Filament/`

#### 1.1 Cluster Website Settings

-   **NavigationWeb**

    -   Mengatur menu navigasi website
    -   Mendukung struktur menu multi-level
    -   Validasi untuk mencegah circular reference
    -   Menggunakan cache untuk optimasi performa

-   **Pages**

    -   Mengelola halaman dinamis
    -   Tipe sumber data: Profil, Customer, Content
    -   Validasi untuk mencegah duplikasi source
    -   Cache system untuk dropdown options

-   **Slider**
    -   Manajemen slider homepage
    -   Integrasi dengan galeri foto
    -   Pengaturan urutan dengan drag & drop
    -   Optimasi gambar otomatis

#### 1.2 Resources

-   **Content**

    -   Editor markdown dengan preview
    -   SEO metadata management
    -   Slug generator otomatis
    -   Sistem kategorisasi

-   **Customer**

    -   Database pelanggan
    -   Status management
    -   Integrasi dengan Pages

-   **Photos**

    -   Multiple upload dengan drag & drop
    -   Background job untuk optimasi
    -   Preview sebelum upload
    -   Sistem paginasi kustom

-   **Profil**
    -   Single instance management
    -   Markdown support
    -   Logo optimization
    -   Validasi data perusahaan

## 2. Frontend Components

### 2.1 Livewire Components

Lokasi: `app/Livewire/`

#### Components Utama:

-   **HomePage.php**

    ```php
    // Menampilkan slider dan konten dinamis homepage
    use App\Models\Slider;
    use App\Models\Content;
    ```

-   **AboutUs.php**

    ```php
    // Menampilkan profil perusahaan
    use App\Models\Profil;
    ```

-   **ViewDinamis.php**
    ```php
    // Handler untuk semua halaman dinamis
    use App\Models\Page;
    use App\Models\NavigationWeb;
    ```

#### Partial Components:

-   **Header.php & Footer.php**
    ```php
    // Komponen navigasi dan footer
    use App\Models\NavigationWeb;
    use App\Models\Profil;
    ```

### 2.2 Blade Components

Lokasi: `resources/views/components/`

#### Custom Components:

-   **pagination-custom.blade.php**
    ```php
    // Paginasi kustom dengan fitur:
    - Per page selector (10, 25, 50)
    - Smooth loading states
    - Dark mode support
    - Responsive design
    - Smooth scroll
    ```

## 3. Model Relationships

### 3.1 Core Models

```php
// Page.php
belongsTo('source') // Polymorphic relation
belongsTo('navigation')

// NavigationWeb.php
hasMany('children')
belongsTo('parent')
belongsTo('page')

// Content.php
hasMany('pages')
belongsTo('category')

// Photo.php
belongsTo('album')
hasMany('sliders')
```

## 4. Background Jobs

### 4.1 Image Optimization

Lokasi: `app/Jobs/ResizePhotoJob.php`

```php
// Handles:
- Resize large images (>1MB)
- Format optimization
- Quality compression
- Thumbnail generation
```

## 5. Cache System

### 5.1 Key Cache Areas

```php
// NavigationWeb
Cache::tags(['navigation'])->remember()

// Pages Options
Cache::tags(['pages', 'options'])->remember()

// Content Lists
Cache::tags(['content'])->remember()
```

### 5.2 Cache Invalidation

```php
// Auto-invalidation pada:
- Create/Update/Delete Pages
- Perubahan Navigation
- Content Updates
```

## 6. Sistem Paginasi Kustom

### 6.1 Komponen Utama

Lokasi: `resources/views/components/pagination-custom.blade.php`

#### Fitur:

```php
// 1. Per Page Selection
wire:model.live="perPage"
options: [10, 25, 50]

// 2. Loading States
wire:loading.attr="disabled"
wire:loading.class="opacity-70"

// 3. Smooth Scroll
$scrollIntoViewJsSnippet = <<<JS
    var element = document.getElementById('pagination-section');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
JS;

// 4. Dark Mode Support
class="bg-white dark:bg-gray-800"
class="text-gray-700 dark:text-gray-200"

// 5. Responsive Design
class="flex flex-col md:flex-row"
class="hidden sm:flex"
```

## 7. Security Measures

### 7.1 URL Security

```php
// Hashids Implementation
use Vinkla\Hashids\Facades\Hashids;

// Usage in routes
Route::get('photo/{hash}', function($hash) {
    $id = Hashids::decode($hash)[0];
});
```

### 7.2 Form Validation

```php
// Required validation rules di setiap form
- XSS Protection
- SQL Injection Prevention
- File Upload Validation
```

## 8. Performance Optimizations

### 8.1 Database Queries

```php
// Eager Loading
with(['relation1', 'relation2'])

// Query Caching
Cache::remember()

// Index Optimization
- Composite indexes untuk queries umum
- Foreign key indexes
```

### 8.2 Asset Management

```bash
# Production Build
npm run build

# Development
npm run dev
```

## 9. Maintenance Tasks

### 9.1 Regular Tasks

```bash
# Cache Clearing
php artisan cache:clear
php artisan view:clear

# Queue Monitoring
php artisan queue:monitor

# Storage Cleanup
php artisan storage:cleanup
```

### 9.2 Backup Strategy

```bash
# Database Backup
php artisan backup:run

# Media Backup
- /storage/app/public/photos
- /storage/app/public/logos
```

## 10. Troubleshooting Guide

### 10.1 Common Issues

1. **Gambar Tidak Muncul**

    ```bash
    php artisan storage:link
    chmod -R 775 storage
    ```

2. **Cache Issues**

    ```bash
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    ```

3. **Queue Issues**
    ```bash
    php artisan queue:restart
    php artisan queue:clear
    php artisan queue:work
    ```

### 10.2 Performance Issues

1. **Slow Queries**

    - Check `storage/logs/laravel.log`
    - Enable query logging in development
    - Review indexes

2. **Memory Issues**
    - Check PHP memory_limit
    - Monitor queue worker memory
    - Clear old cache files

## 11. Development Guidelines

### 11.1 Coding Standards

```php
// PSR-12 Compliance
- Proper namespacing
- Class naming conventions
- Method naming conventions
```

### 11.2 Git Workflow

```bash
# Branch Naming
feature/feature-name
bugfix/issue-description
hotfix/urgent-fix

# Commit Messages
feat: Add new feature
fix: Fix bug
docs: Update documentation
style: Format code
refactor: Refactor code
```

## 12. Testing

### 12.1 Unit Tests

Lokasi: `tests/Unit/`

```php
// Key Areas to Test
- Model relationships
- Service classes
- Helper functions
```

### 12.2 Feature Tests

Lokasi: `tests/Feature/`

```php
// Test Coverage
- Form submissions
- Page rendering
- API endpoints
- Authentication
```
