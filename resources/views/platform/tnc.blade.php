<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - Archana Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --muted: #475569;
            --soft: #f8fafc;
            --line: rgba(15, 23, 42, 0.08);
            --panel: rgba(255, 255, 255, 0.88);
            --accent: #f97316;
            --accent-deep: #c2410c;
            --sea: #0f766e;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Outfit", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.12), transparent 30%),
                radial-gradient(circle at 90% 10%, rgba(15, 118, 110, 0.10), transparent 24%),
                linear-gradient(135deg, #fff7ed 0%, #f8fafc 50%, #eef6ff 100%);
            min-height: 100vh;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .shell {
            width: min(860px, calc(100% - 32px));
            margin: 0 auto;
            padding: 24px 0 56px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            padding: 14px 18px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand strong {
            font-size: 1.05rem;
            letter-spacing: -0.02em;
        }

        .brand span {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease;
            font-size: 0.92rem;
        }

        .button:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .button-secondary {
            background: rgba(15, 23, 42, 0.06);
            color: var(--ink);
        }

        .tnc-panel {
            border: 1px solid var(--line);
            border-radius: 30px;
            background: var(--panel);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
            padding: 40px;
        }

        .tnc-panel h1 {
            font-size: 2rem;
            letter-spacing: -0.03em;
            margin: 0 0 8px;
        }

        .tnc-panel .subtitle {
            color: var(--muted);
            font-size: 1rem;
            margin: 0 0 32px;
            line-height: 1.7;
        }

        .tnc-panel h2 {
            font-size: 1.15rem;
            margin: 28px 0 10px;
            padding-top: 18px;
            border-top: 1px solid var(--line);
        }

        .tnc-panel h2:first-of-type {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        .tnc-panel p {
            color: var(--muted);
            line-height: 1.85;
            margin: 0 0 14px;
            font-size: 0.96rem;
        }

        .tnc-panel ul {
            color: var(--muted);
            line-height: 1.85;
            margin: 0 0 14px;
            padding-left: 20px;
            font-size: 0.96rem;
        }

        .tnc-panel ul li {
            margin-bottom: 6px;
        }

        .tnc-panel .legal-notice {
            margin-top: 32px;
            padding: 18px;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        .tnc-panel .legal-notice p {
            margin: 0;
            font-size: 0.9rem;
        }

        .footer-note {
            margin-top: 22px;
            text-align: center;
            color: var(--muted);
            font-size: 0.92rem;
        }

        @media (max-width: 640px) {
            .shell {
                width: min(100% - 20px, 860px);
                padding-top: 16px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px;
            }

            .tnc-panel {
                padding: 24px;
                border-radius: 24px;
            }

            .tnc-panel h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <main class="shell">
        <header class="topbar">
            <div class="brand">
                <strong>Archana Order</strong>
                <span>Syarat & Ketentuan Layanan</span>
            </div>
            <a class="button button-secondary" href="{{ route('landing') }}">← Kembali ke Beranda</a>
        </header>

        <section class="tnc-panel">
            <h1>Syarat & Ketentuan</h1>
            <p class="subtitle">
                Dokumen ini mengatur syarat dan ketentuan penggunaan platform Archana Order sebagai layanan pemesanan digital.
                Dengan menggunakan layanan ini, Anda menyetujui seluruh ketentuan yang berlaku di bawah ini.
            </p>

            <h2>1. Ketentuan Penggunaan</h2>
            <p>
                Archana Order ditawarkan kepada Anda, pengguna, dengan syarat penerimaan Anda terhadap ketentuan,
                syarat, dan pemberitahuan yang terkandung atau dirujuk di sini serta ketentuan tambahan, perjanjian,
                dan pemberitahuan yang mungkin berlaku untuk setiap halaman atau bagian dari layanan ini.
            </p>

            <h2>2. Gambaran Umum</h2>
            <p>
                Penggunaan Anda atas layanan ini merupakan persetujuan Anda terhadap semua syarat, ketentuan, dan pemberitahuan.
                Harap baca dengan seksama. Dengan menggunakan layanan ini, Anda menyetujui Syarat dan Ketentuan ini,
                serta aturan atau pedoman lain yang berlaku untuk setiap bagian dari layanan ini, tanpa batasan atau pengecualian.
                Jika Anda tidak menyetujui Syarat dan Ketentuan ini, Anda harus segera berhenti menggunakan layanan ini
                dan menghentikan segala penggunaan informasi atau produk dari layanan ini.
            </p>

            <h2>3. Modifikasi Layanan dan Syarat & Ketentuan</h2>
            <p>
                Archana Order berhak untuk mengubah, memodifikasi, memperbarui, atau menghentikan syarat, ketentuan,
                dan pemberitahuan yang mengatur layanan ini termasuk tautan, konten, informasi, harga, dan materi lainnya
                yang ditawarkan melalui layanan ini kapan saja dan dari waktu ke waktu tanpa pemberitahuan atau kewajiban
                lebih lanjut kepada Anda kecuali sebagaimana yang ditentukan di dalamnya. Kami berhak menyesuaikan harga
                dari waktu ke waktu. Jika karena suatu alasan terjadi kesalahan harga, Archana Order berhak menolak pesanan.
                Dengan terus menggunakan layanan ini setelah modifikasi, perubahan, atau pembaruan tersebut, Anda setuju
                untuk terikat oleh modifikasi, perubahan, atau pembaruan tersebut.
            </p>

            <h2>4. Hak Cipta</h2>
            <p>
                Layanan ini dimiliki dan dioperasikan oleh Archana Order. Kecuali ditentukan lain, semua materi pada
                layanan ini, termasuk merek dagang, merek layanan, dan logo adalah milik Archana Order dan dilindungi
                oleh undang-undang hak cipta Indonesia dan di seluruh dunia oleh undang-undang hak cipta yang berlaku.
                Tidak ada materi yang dipublikasikan oleh Archana Order pada layanan ini, baik secara keseluruhan maupun
                sebagian, yang boleh disalin, direproduksi, dimodifikasi, diterbitkan ulang, diunggah, diposting,
                ditransmisikan, atau didistribusikan dalam bentuk apa pun atau dengan cara apa pun tanpa izin tertulis
                sebelumnya dari Archana Order.
            </p>

            <h2>5. Pendaftaran Akun</h2>
            <p>
                Anda perlu mendaftar pada layanan ini untuk memesan layanan dengan memasukkan username dan password Anda.
                Anda akan mendapatkan manfaat seperti notifikasi, pembaruan, dan penawaran khusus dengan mendaftar.
                Anda akan diminta untuk memberikan informasi yang akurat dan terkini pada semua formulir pendaftaran
                di layanan ini. Anda bertanggung jawab sepenuhnya untuk menjaga kerahasiaan username dan password yang
                Anda pilih atau yang dipilih oleh administrator web Anda atas nama Anda, untuk mengakses layanan ini
                serta segala aktivitas yang terjadi di bawah username/password Anda. Anda tidak akan menyalahgunakan
                atau membagikan username atau password Anda, memalsukan identitas Anda atau afiliasi Anda dengan suatu
                entitas, meniru orang atau entitas mana pun, atau salah menyatakan asal materi apa pun yang Anda akses
                melalui layanan ini.
            </p>

            <h2>6. Komunikasi Elektronik</h2>
            <p>
                Anda setuju bahwa Archana Order dapat mengirimkan email elektronik kepada Anda untuk tujuan memberitahu
                Anda tentang perubahan atau penambahan pada layanan ini, tentang produk atau layanan Archana Order,
                atau untuk tujuan lain yang kami anggap sesuai. Jika Anda ingin berhenti berlangganan dari newsletter kami,
                silakan klik "Berhenti Berlangganan" di halaman akun Anda.
            </p>

            <h2>7. Deskripsi Layanan</h2>
            <p>
                Kami selalu berusaha menampilkan informasi dan tampilan produk yang muncul di layanan ini seakurat mungkin.
                Namun, kami tidak dapat menjamin bahwa tampilan monitor Anda terhadap warna apa pun akan akurat karena
                warna aktual yang Anda lihat bergantung pada kualitas monitor Anda.
            </p>

            <h2>8. Kebijakan Privasi</h2>
            <p>
                Informasi Anda aman bersama kami. Archana Order memahami bahwa masalah privasi sangat penting bagi
                pelanggan kami. Anda dapat yakin bahwa informasi apa pun yang Anda kirimkan kepada kami tidak akan
                disalahgunakan, diselewengkan, atau dijual kepada pihak ketiga mana pun. Kami hanya menggunakan
                informasi pribadi Anda untuk menyelesaikan pesanan Anda.
            </p>

            <h2>9. Pembayaran dan Transaksi</h2>
            <p>
                Layanan pembayaran pada platform ini diproses melalui payment gateway pihak ketiga yang telah memiliki
                lisensi resmi dari Bank Indonesia. Seluruh transaksi pembayaran digital dilindungi dengan enkripsi
                dan standar keamanan yang berlaku. Dengan melakukan transaksi melalui platform ini, Anda menyetujui
                ketentuan pembayaran yang berlaku termasuk biaya layanan yang mungkin dikenakan.
            </p>

            <h2>10. Ganti Rugi</h2>
            <p>
                Anda setuju untuk mengganti rugi, membela, dan membebaskan Archana Order dari dan terhadap setiap dan
                semua klaim pihak ketiga, kewajiban, kerusakan, kerugian, atau biaya (termasuk biaya pengacara yang wajar
                dan biaya lainnya) yang timbul dari, berdasarkan, atau sehubungan dengan akses dan/atau penggunaan Anda
                atas layanan ini.
            </p>

            <h2>11. Penafian (Disclaimer)</h2>
            <p>
                Archana Order tidak bertanggung jawab atas keakuratan, ketepatan waktu, atau konten materi yang disediakan
                pada layanan ini. Anda tidak boleh berasumsi bahwa materi pada layanan ini terus diperbarui atau berisi
                informasi terkini. Archana Order tidak bertanggung jawab untuk menyediakan konten atau materi dari layanan
                yang telah kedaluwarsa atau telah dihapus.
            </p>

            <h2>12. Hukum yang Berlaku</h2>
            <p>
                Syarat dan Ketentuan ini diatur oleh dan tunduk pada hukum yang berlaku di Republik Indonesia.
            </p>

            <h2>13. Pertanyaan dan Umpan Balik</h2>
            <p>
                Kami menyambut pertanyaan, komentar, dan kekhawatiran Anda tentang privasi atau informasi apa pun yang
                dikumpulkan dari Anda atau tentang Anda. Silakan kirimkan kepada kami umpan balik terkait privasi,
                atau masalah lainnya melalui kontak yang tersedia di platform.
            </p>

            <div class="legal-notice">
                <p><strong>Pemberitahuan Hukum</strong></p>
                <p>Archana Order adalah platform layanan pemesanan digital.</p>
                <p>Dokumen ini terakhir diperbarui pada {{ now()->translatedFormat('d F Y') }}.</p>
                <p>Copyright &copy; {{ date('Y') }} Archana Order. All Rights Reserved.</p>
            </div>
        </section>

        <div class="footer-note">
            &copy; {{ date('Y') }} Archana Order &mdash; Platform Pemesanan Digital Multi-Tenant
        </div>
    </main>
</body>

</html>
