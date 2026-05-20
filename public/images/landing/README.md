# Landing Page Images

Letakkan file gambar landing page Archana App di folder ini agar tampilan
landing pada `/` sama dengan versi Next.js sebelumnya.

File yang diharapkan (sama seperti `public/` di Next.js):

| File                  | Dipakai di section          |
| --------------------- | --------------------------- |
| `hero.png`            | Hero (desktop background)   |
| `mobile-hero.png`     | Hero (mobile illustration)  |
| `about-hero.png`      | Tentang Archana App         |
| `detail-app.png`      | Detail Aplikasi             |
| `pricing-acrylic.png` | Pricing (QR Acrylic mockup) |

Jika salah satu file belum tersedia, tag `<img>` akan otomatis disembunyikan
oleh handler `onerror` di Blade, jadi landing tetap bisa diakses tanpa error
visual yang mengganggu.

Untuk file PDF brosur yang di-link tombol "Lihat Cara Kerja", taruh
`archana-smart-order.pdf` langsung di folder `public/`.
