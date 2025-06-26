# Web Company - Panduan Pengoperasian

Web Company adalah sistem manajemen konten (CMS) berbasis Laravel yang dilengkapi dengan panel admin Filament. Sistem ini dirancang untuk mengelola konten website perusahaan dengan mudah dan efisien.

## Persyaratan Sistem

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL/MariaDB
-   Web Server (Apache/Nginx)

## Instalasi

1. Clone repositori ini
2. Jalankan `composer install`
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi database
4. Jalankan `php artisan key:generate`
5. Jalankan `php artisan migrate`
6. Jalankan `npm install`
7. Jalankan `npm run dev` untuk development atau `npm run build` untuk production

## Fitur Utama

### 1. Manajemen Profil Perusahaan

-   Pengelolaan informasi profil perusahaan
-   Hanya dapat membuat satu profil perusahaan
-   Mendukung format markdown untuk deskripsi
-   Optimasi gambar otomatis (resize jika > 1MB)

### 2. Manajemen Konten

-   Pengelolaan artikel dan konten website
-   Editor markdown yang user-friendly
-   Pengaturan SEO untuk setiap konten
-   Sistem kategorisasi konten

### 3. Galeri Foto

-   Upload multiple foto
-   Optimasi otomatis untuk performa
-   Pengelolaan album dan kategori foto
-   Preview foto sebelum upload

### 4. Manajemen Slider

-   Pengaturan slider halaman utama
-   Kustomisasi judul dan deskripsi
-   Pengaturan urutan tampilan

### 5. Manajemen Navigasi

-   Pengaturan menu website
-   Struktur menu yang fleksibel
-   Pengaturan link internal/eksternal

### 6. Manajemen Pelanggan

-   Database pelanggan terintegrasi
-   Riwayat interaksi pelanggan
-   Pengategorian pelanggan

## Panduan Penggunaan

### Panel Admin

1. Akses panel admin di `/admin`
2. Login menggunakan kredensial administrator
3. Navigasi menggunakan menu di sidebar kiri

### Pengaturan Profil Perusahaan

-   Buka menu "Profil" di panel admin
-   Isi informasi perusahaan (hanya bisa satu profil)
-   Gunakan format markdown untuk deskripsi
-   Upload logo perusahaan (akan dioptimasi otomatis)

### Pengelolaan Konten

1. Buka menu "Konten"
2. Pilih "Tambah Konten" untuk konten baru
3. Isi judul, konten, dan SEO metadata
4. Gunakan editor markdown untuk formatting
5. Simpan dan preview sebelum publish

### Upload dan Manajemen Foto

1. Buka menu "Foto"
2. Gunakan fitur drag & drop untuk upload
3. Atur kategori dan deskripsi
4. Foto akan dioptimasi otomatis

### Pengaturan Slider

1. Buka menu "Slider"
2. Upload gambar slider
3. Atur judul dan deskripsi
4. Atur urutan tampilan

### Pengaturan Navigasi

1. Buka menu "Navigasi"
2. Tambah item menu baru
3. Atur hierarki menu
4. Tentukan link tujuan

## Optimasi Frontend

### Markdown Styling

Untuk menampilkan konten markdown dengan baik:

1. Pastikan @tailwindcss/typography terinstall
2. Gunakan class `prose` pada container

```html
<div class="prose prose-invert text-gray-200 max-w-none">
    {!! str($content->description)->markdown()->sanitizeHtml() !!}
</div>
```

## Troubleshooting

### Masalah Umum

1. **Gambar tidak muncul**

    - Periksa permission folder storage
    - Jalankan `php artisan storage:link`

2. **Optimasi gambar gagal**

    - Pastikan ekstensi GD/Imagick terinstall
    - Periksa permission folder temp

3. **Frontend tidak ter-update**
    - Jalankan `npm run build`
    - Clear cache browser

## Keamanan

-   Selalu update dependencies secara berkala
-   Gunakan strong password
-   Aktifkan HTTPS
-   Backup database secara rutin

## Dukungan

Untuk bantuan teknis, silakan:

1. Buka issue di repositori
2. Dokumentasi lengkap tersedia di `/docs`
3. Kontak tim support

## Lisensi

Sistem ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
