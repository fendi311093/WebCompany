# DKD CMS v1.0 - Digital Kompak Developer

DKD CMS adalah sistem manajemen konten (CMS) berbasis Laravel yang dilengkapi dengan panel admin Filament. Sistem ini dirancang untuk mengelola konten website perusahaan dengan mudah dan efisien.

## Filosofi Nama Sistem

DKD CMS merupakan singkatan yang mencerminkan nilai-nilai utama kami:

### Digital

-   Transformasi digital yang modern dan inovatif
-   Solusi teknologi terkini untuk kebutuhan bisnis
-   Pengalaman digital yang optimal untuk pengguna
-   Integrasi sistem yang seamless

### Kompak

-   Sistem yang terintegrasi secara menyeluruh
-   Kemudahan penggunaan dan pengelolaan

### Developer

-   Pengembangan berkelanjutan dan inovatif
-   Kode yang bersih dan terstruktur
-   Fleksibilitas untuk customisasi

## Fitur Keamanan

DKD CMS dilengkapi dengan berbagai fitur keamanan untuk melindungi data dan sistem Anda:

1. **HashIds untuk ID dan URL**

    - Semua ID sensitif (seperti ID foto, halaman, konten) dienkripsi menggunakan HashIds sebelum ditampilkan di URL atau frontend.
    - Mencegah user menebak atau memanipulasi ID asli di URL.

2. **Autentikasi & Otorisasi**

    - Sistem login admin menggunakan Laravel Auth.
    - Hanya user yang terautentikasi yang dapat mengakses panel admin.
    - Otorisasi berbasis role/policy untuk membatasi akses fitur tertentu.

3. **Proteksi File Upload**

    - Validasi tipe file saat upload (hanya gambar yang diizinkan).
    - Nama file diubah menjadi nama yang aman dan unik.
    - File dioptimasi dan dicek ukurannya sebelum disimpan.

4. **CSRF Protection**

    - Semua form dan request menggunakan CSRF token bawaan Laravel untuk mencegah serangan Cross-Site Request Forgery.

5. **Sanitasi Input & Output**

    - Semua input dari user divalidasi dan disanitasi.
    - Output konten (seperti markdown) disanitasi sebelum ditampilkan untuk mencegah XSS (Cross-Site Scripting).

6. **Proteksi Akses Storage**

    - File yang diupload disimpan di folder storage yang tidak bisa diakses langsung dari public.
    - Akses file hanya melalui route yang sudah diatur.

7. **Notifikasi & Logging**

    - Notifikasi untuk aksi penting (seperti hapus data) agar admin selalu aware.
    - Logging aktivitas penting untuk audit dan troubleshooting.

8. **Session Management**

    - Session disimpan secara aman, dan dapat diatur timeout-nya.
    - Logout otomatis jika session habis.

9. **Proteksi Query & SQL Injection**
    - Semua query database menggunakan Eloquent/Query Builder yang aman dari SQL Injection.

## Versi Saat Ini: v1.0

-   Sistem manajemen konten lengkap
-   Panel admin Filament yang modern
-   Optimasi gambar otomatis
-   Sistem keamanan dengan HashIds
-   Manajemen halaman dinamis
-   Galeri foto responsif
-   Sistem navigasi fleksibel

## Versi Mendatang: v2.0 (Coming Soon)

Fitur baru yang akan hadir:

-   Customer Care Chat System:
    -   Live chat dengan pelanggan
    -   Sistem tiket support
    -   Riwayat percakapan
    -   Notifikasi real-time
    -   Dashboard admin chat
    -   Integrasi dengan sistem pelanggan
    -   Mobile-friendly chat interface
    -   Status online/offline agent
    -   Auto-response system
    -   File sharing dalam chat

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
-   Sistem hashids untuk keamanan URL

### 2. Manajemen Konten

-   Pengelolaan artikel dan konten website
-   Editor markdown yang user-friendly
-   Pengaturan SEO untuk setiap konten
-   Sistem kategorisasi konten
-   Slug otomatis untuk URL yang SEO-friendly
-   Sistem hashids untuk keamanan URL

### 3. Galeri Foto

-   Upload multiple foto dengan drag & drop
-   Optimasi otomatis untuk performa (resize jika > 1MB)
-   Pengelolaan album dan kategori foto
-   Preview foto sebelum upload
-   Sistem hashids untuk keamanan URL dan ID foto
-   Background job untuk optimasi gambar
-   Tampilan grid responsif dengan hover effect
-   Konfirmasi hapus foto dengan modal dialog
-   Fitur edit foto dengan preview langsung
-   Sistem paginasi kustom dengan fitur:
    -   Pemilihan jumlah item per halaman (10, 25, 50)
    -   Navigasi halaman yang responsif
    -   Indikator loading yang smooth
    -   Tampilan yang konsisten dengan tema Filament
    -   Optimasi untuk dark mode
    -   Smooth scroll saat pergantian halaman
    -   Transisi hover yang halus pada setiap foto
    -   Aksi cepat (quick actions) untuk edit dan hapus
    -   Tampilan mobile-friendly dengan dropdown menu
    -   Keamanan URL dengan sistem hashids

### 4. Manajemen Slider

-   Pengaturan slider halaman utama
-   Kustomisasi judul dan deskripsi
-   Pengaturan urutan tampilan
-   Integrasi dengan galeri foto
-   Sistem hashids untuk keamanan URL

### 5. Manajemen Halaman (Pages)

-   Sistem halaman dinamis yang fleksibel
-   Mendukung multiple source types (Profil, Customer, Content)
-   Pengaturan style view untuk setiap halaman
-   Status aktif/nonaktif per halaman
-   Validasi untuk mencegah duplikasi source
-   Cache system untuk performa optimal

### 6. Manajemen Navigasi Website

-   Pengaturan menu website yang dinamis
-   Struktur menu yang fleksibel
-   Integrasi dengan sistem Pages
-   Pengaturan link internal/eksternal
-   Slug otomatis untuk URL yang SEO-friendly
-   Pengaturan urutan menu

### 7. Manajemen Pelanggan

-   Database pelanggan terintegrasi
-   Riwayat interaksi pelanggan
-   Pengategorian pelanggan
-   Status aktif/nonaktif pelanggan
-   Integrasi dengan sistem Pages

## Panduan Penggunaan

### Panel Admin

1. Akses panel admin di `/admin`
2. Login menggunakan kredensial administrator
3. Navigasi menggunakan menu di sidebar kiri
4. Gunakan cluster "Website Settings" untuk pengaturan website

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
4. Foto akan dioptimasi otomatis jika ukuran > 1MB
5. Gunakan fitur "Upload Photos" untuk multiple upload
6. Manfaatkan quick actions untuk edit dan hapus
7. Konfirmasi sebelum menghapus foto
8. Gunakan fitur paginasi untuk navigasi yang mudah
9. Preview foto dengan hover effect
10. Akses cepat ke menu edit dan hapus di mobile

### Pengaturan Slider

1. Buka menu "Slider"
2. Upload gambar slider atau pilih dari galeri foto
3. Atur judul dan deskripsi
4. Atur urutan tampilan

### Pengaturan Halaman (Pages)

1. Buka menu "Pages" di cluster Website Settings
2. Pilih "Data Type" (Profil, Customer, atau Content)
3. Pilih data yang akan dijadikan halaman
4. Atur "Style Page" sesuai kebutuhan
5. Aktifkan/nonaktifkan halaman
6. Sistem akan mencegah duplikasi source

### Pengaturan Navigasi Website

1. Buka menu "Navigation Web" di cluster Website Settings
2. Tambah item menu baru
3. Pilih tipe menu (Page, URL, atau Dropdown)
4. Jika memilih Page, pilih halaman yang sudah dibuat
5. Atur hierarki menu dengan parent-child
6. Tentukan slug untuk URL

## Struktur Halaman Dinamis

### Source Types yang Didukung

1. **Profil**: Menampilkan halaman profil perusahaan
2. **Customer**: Menampilkan halaman pelanggan
3. **Content**: Menampilkan halaman konten/artikel

### Style Views

-   **Style 1**: Layout standar untuk halaman dinamis
-   **Style 2**: Layout alternatif (dapat dikembangkan lebih lanjut)

### Sistem Cache

-   Cache untuk opsi dropdown di form Pages
-   Cache untuk menu navigasi
-   Cache untuk data source IDs
-   Cache otomatis ter-reset saat ada perubahan

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

### Livewire Components

-   **HomePage**: Halaman utama website
-   **AboutUs**: Halaman tentang kami
-   **ViewDinamis**: Halaman dinamis berdasarkan slug
-   **Header/Footer**: Komponen partial untuk layout

## Troubleshooting

### Masalah Umum

1. **Gambar tidak muncul**

    - Periksa permission folder storage
    - Jalankan `php artisan storage:link`

2. **Optimasi gambar gagal**

    - Pastikan ekstensi GD/Imagick terinstall
    - Periksa permission folder temp
    - Periksa queue worker berjalan

3. **Frontend tidak ter-update**

    - Jalankan `npm run build`
    - Clear cache browser

4. **Cache tidak ter-update**

    - Clear cache aplikasi: `php artisan cache:clear`
    - Restart queue worker jika menggunakan background jobs

5. **Halaman dinamis tidak muncul**

    - Periksa status aktif halaman di Pages
    - Periksa navigasi sudah terhubung dengan benar
    - Periksa slug di Navigation Web

6. **Masalah dengan Galeri Foto**
    - Pastikan folder storage/app/public memiliki permission yang benar
    - Periksa konfigurasi hashids di config/hashids.php
    - Pastikan queue worker berjalan untuk optimasi foto
    - Clear cache jika perubahan tidak terlihat
    - Periksa koneksi internet untuk loading gambar

## Lisensi

Sistem ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
