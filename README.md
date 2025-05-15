<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## LIBRARY

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Intervention Image v3](https://intervention.io/).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## MENU PROFIL COMPANY
   -[ProfilResource.php]
        ->[CreateResource.php], ada beberap Fitur :
            - mencegah pembuatan lebih dari satu data profil di database
                Penjelasan Kode
                - [mutateFormDataBeforeCreate(array $data): array] → Fungsi ini dipanggil sebelum data disimpan ke database dalam proses create.
                - [Profil::count() > 0] → Mengecek apakah sudah ada data profil di database. Jika ada (count() > 0), maka proses pembuatan data baru akan dicegah.
                - [Notification::make()] → Membuat dan menampilkan notifikasi ke pengguna, memberitahu bahwa hanya boleh ada satu profil.
                - [$this->halt();] → Menghentikan eksekusi sebelum data benar-benar disimpan ke database.
        ->Model [Profil.php], Fitur :
            - Library resize image (https://image.intervention.io/v3/getting-started/installation)
            - Resize photo jika [> 1Mb]
            - Delete photo di storage ketika proses Edit & Delete data
            - Custom validasi rule

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
