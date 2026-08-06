# RR Jaya Fishing

Website monitoring penjualan dan stok berbasis Laravel untuk tiga role: admin, karyawan, dan owner.

## Menjalankan aplikasi

```bash
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`. Akun demo:

- `admin` / `password` — akses penuh dan manajemen pengguna
- `karyawan` / `password` — produk, stok masuk, dan transaksi
- `owner` / `password` — dashboard analitik dan laporan

Foto produk dapat diganti melalui kolom URL foto pada form produk. Untuk produksi, ubah semua password demo dan sesuaikan konfigurasi database pada `.env`.
