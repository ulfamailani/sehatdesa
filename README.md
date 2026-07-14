# SehatDesa — AI Kesehatan Komunitas

Aplikasi web informasi kesehatan dasar untuk masyarakat desa: jadwal
posyandu, gizi keluarga, penyakit umum, dan asisten AI tanya-jawab
kesehatan. Dibangun dengan PHP native + MySQL (kompatibel phpMyAdmin),
tanpa framework, agar mudah di-deploy di hosting shared biasa
(XAMPP/Laragon lokal atau cPanel).

## Struktur folder

```
sehatdesa/
├─ config/config.php        # kredensial database & konstanta aplikasi
├─ includes/                # koneksi DB, autentikasi, helper, header/footer
├─ assets/css/style.css     # desain sistem lengkap
├─ assets/js/main.js        # menu mobile + chat AJAX
├─ api/chat.php             # endpoint yang memanggil API AI
├─ database.sql             # skema + data contoh, impor lewat phpMyAdmin
├─ create_admin.php         # skrip sekali-jalan membuat akun admin
├─ index.php, login.php, register.php, logout.php
├─ posyandu.php, gizi.php, penyakit.php, chat.php, settings.php
```

## Instalasi

1. **Siapkan database lewat phpMyAdmin**
   - Buka phpMyAdmin → tab *Import* → pilih file `database.sql` → jalankan.
   - Ini akan membuat database `sehatdesa` beserta seluruh tabel dan
     beberapa data contoh (jadwal posyandu & artikel gizi).

2. **Atur kredensial koneksi**
   - Buka `config/config.php`, sesuaikan `DB_HOST`, `DB_NAME`, `DB_USER`,
     `DB_PASS` dengan akun MySQL/phpMyAdmin Anda.
   - Ganti `APP_SECRET` dengan string acak yang panjang.

3. **Buat akun admin pertama**
   - Jalankan `create_admin.php` sekali lewat browser atau CLI
     (`php create_admin.php`). Skrip ini menghasilkan hash kata sandi
     yang valid dan menyimpannya ke tabel `users`.
   - Setelah berhasil, **hapus file `create_admin.php`** dari server.
   - Login memakai username `admin` dan kata sandi default di dalam
     skrip tersebut, lalu segera ganti kata sandi.

4. **Atur API key Asisten AI**
   - Masuk sebagai admin → klik **Pengaturan** di header.
   - Masukkan API key layanan AI (mis. dari Anthropic Console) dan
     nama model, lalu simpan. Key disimpan di tabel `api_settings`,
     bukan di source code, sehingga mudah dirotasi.

5. **Selesai** — akses `index.php` di browser Anda. Warga dapat
   mendaftar sendiri lewat `register.php` (peran default `warga`).
   Untuk memberi peran `kader` (dapat menambah jadwal/artikel) atau
   `admin`, ubah kolom `role` pada tabel `users` lewat phpMyAdmin.

## Peran pengguna

| Peran   | Akses                                                        |
|---------|---------------------------------------------------------------|
| warga   | Melihat semua halaman publik, menggunakan Asisten AI          |
| kader   | Semua akses warga + menambah jadwal posyandu & artikel gizi/penyakit |
| admin   | Semua akses kader + mengatur API key di halaman Pengaturan    |

## Keamanan yang sudah diterapkan

- Kata sandi disimpan dengan `password_hash()` (bcrypt), tidak pernah
  disimpan sebagai teks biasa.
- Seluruh query database memakai *prepared statements* (PDO) untuk
  mencegah SQL injection.
- Output halaman melewati `htmlspecialchars()` untuk mencegah XSS.
- Sesi login memakai cookie `HttpOnly` + `SameSite=Lax`.
- API key AI disimpan di database (bukan hard-coded di source),
  hanya dapat diubah oleh peran `admin`.

## Yang perlu Anda sesuaikan sebelum produksi

- Ganti seluruh kredensial contoh (`DB_PASS`, `APP_SECRET`, API key).
- Aktifkan HTTPS di server produksi.
- Pertimbangkan menambah *rate limiting* pada `api/chat.php` bila
  trafik tinggi, agar kuota API tidak cepat habis.
- Konten kesehatan di aplikasi ini bersifat edukatif, bukan pengganti
  diagnosis tenaga medis — pastikan disclaimer ini tetap tampil.
