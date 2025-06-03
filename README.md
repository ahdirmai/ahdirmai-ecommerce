# 🛍️ ahdirmai-ecommerce

**ahdirmai-ecommerce** adalah aplikasi e-commerce berbasis web yang dikembangkan menggunakan Laravel. Proyek ini dirancang untuk menyediakan platform belanja online dengan fitur-fitur dasar seperti katalog produk, manajemen keranjang belanja, dan sistem checkout.

## 🚀 Fitur Utama

- **Katalog Produk**: Menampilkan daftar produk dengan informasi detail.
- **Keranjang Belanja**: Menambahkan, menghapus, dan mengelola item dalam keranjang.
- **Proses Checkout**: Sistem checkout sederhana untuk menyelesaikan pembelian.
- **Manajemen Produk**: Antarmuka untuk menambahkan, mengedit, dan menghapus produk (fitur admin).
- **Responsif**: Desain antarmuka yang responsif untuk berbagai ukuran layar.

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel](https://laravel.com/) - Framework PHP untuk pengembangan web.
- **Frontend**: [Tailwind CSS](https://tailwindcss.com/) - Framework CSS untuk desain antarmuka.
- **Build Tool**: [Vite](https://vitejs.dev/) - Alat build frontend modern.
- **Database**: MySQL - Sistem manajemen basis data relasional.
- **Authentication**: Laravel Breeze - Starter kit untuk autentikasi sederhana.

## 📦 Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek secara lokal:

1. **Kloning repositori**

   ```bash
   git clone https://github.com/ahdirmai/ahdirmai-ecommerce.git
   cd ahdirmai-ecommerce
   ```

2. **Instal dependensi PHP**

   ```bash
   composer install
   ```

3. **Instal dependensi JavaScript**

   ```bash
   npm install
   ```

4. **Salin file konfigurasi lingkungan**

   ```bash
   cp .env.example .env
   ```

5. **Generate application key**

   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi database**

   Edit file `.env` dan sesuaikan pengaturan database:

   ```
   DB_DATABASE=nama_database
   DB_USERNAME=username_database
   DB_PASSWORD=password_database
   ```

7. **Migrasi dan seeding database**

   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan server pengembangan**

   ```bash
   php artisan serve
   ```

9. **Jalankan build frontend**

   ```bash
   npm run dev
   ```

Akses aplikasi melalui `http://localhost:8000`.


## 🤝 Kontribusi

Kontribusi sangat dihargai! Jika Anda ingin berkontribusi:

1. Fork repositori ini.
2. Buat branch fitur baru: `git checkout -b fitur-baru`.
3. Commit perubahan Anda: `git commit -m 'Menambahkan fitur baru'`.
4. Push ke branch Anda: `git push origin fitur-baru`.
5. Buat pull request.

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
