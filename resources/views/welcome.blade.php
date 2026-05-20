@php
    $whatsappLink = 'https://wa.me/62895363076706';

    $features = [
        ['icon' => 'bolt',   'title' => 'Pesan Cepat',        'body' => 'Scan QR Code, pesan dalam hitungan detik'],
        ['icon' => 'bell',   'title' => 'Update Real-time',   'body' => 'Pesanan langsung masuk ke sistem secara otomatis'],
        ['icon' => 'bars',   'title' => 'Operasional Rapi',   'body' => 'Terhubung ke kasir, kitchen, bar, dan operator'],
        ['icon' => 'shield', 'title' => 'Tanpa Beban Staf',   'body' => 'Proses lebih efisien, layanan tamu semakin maksimal'],
    ];

    $flow = [
        ['qr',     'Tamu Pesan',     'Scan QR Code'],
        ['cloud',  'Pesanan Masuk',  'ke Sistem'],
        ['screen', 'Dikirim ke',     'Kasir'],
        ['chef',   'Diteruskan ke',  'Kitchen / Bar'],
        ['bell',   'Operator',       'Siap Antar'],
    ];

    $aboutStats = [
        ['3x',   'alur lebih ringkas dari tamu ke dapur'],
        ['24/7', 'pemesanan mandiri tanpa menunggu staf'],
        ['1 QR', 'akses menu digital untuk setiap kamar atau meja'],
    ];

    $aboutPoints = [
        'Tamu membuka menu dari QR Code, memilih pesanan, lalu checkout dari ponsel.',
        'Order otomatis masuk ke kasir, kitchen, bar, dan operator tanpa input ulang.',
        'Hotel bisa menjaga layanan tetap cepat meski staf sedang menangani banyak kamar.',
    ];

    $detailItems = [
        ['Menu QR',         'Tamu scan QR dari kamar atau meja lalu melihat menu digital yang sudah tersusun rapi.'],
        ['Checkout Cepat',  'Pesanan, pajak, metode bayar, dan total tampil jelas sebelum dikonfirmasi.'],
        ['Order Tracking',  'Status pesanan membantu staf tahu order baru, siap diproses, hingga siap antar.'],
        ['Multi Area',      'Pesanan bisa diteruskan ke kasir, kitchen, bar, dan operator sesuai kebutuhan hotel.'],
    ];

    $pricingBenefits = [
        '10 QR Acrylic full design untuk meja/kamar',
        'Setup menu digital awal dan konfigurasi QR',
        'Pelatihan penggunaan aplikasi untuk tim operasional',
        'Alur pesanan ke kasir, kitchen, bar, dan operator',
        'Monitoring pesanan masuk secara real-time',
        'Dukungan onboarding sampai aplikasi siap digunakan',
    ];

    $trustItems = [
        ['shield', 'Aman & Terpercaya',       'Sistem aman dengan backup data berkala.'],
        ['bell',   'Implementasi Cepat',      'Setup dan aktivasi cepat, bisa langsung digunakan.'],
        ['cloud',  'Dukungan Penuh',          'Tim support siap membantu kapan saja Anda butuh.'],
        ['bars',   'Bantu Tingkatkan Bisnis', 'Operasional lebih efisien, pelayanan lebih maksimal.'],
    ];

    $heroImage     = asset('images/landing/hero.png');
    $mobileHero    = asset('images/landing/mobile-hero.png');
    $aboutImage    = asset('images/landing/about-hero.png');
    $detailImage   = asset('images/landing/detail-app.png');
    $pricingImage  = asset('images/landing/pricing-acrylic.png');
    $brandLogo     = asset('images/landing/archana-logo.png');
    $brochurePdf   = asset('images/landing/archana-smart-order.pdf');
@endphp
@php
    $seoBrand       = config('seo.brand');
    $seoTitle       = config('seo.title');
    $seoDescription = config('seo.description');
    $seoKeywords    = config('seo.keywords');
    $seoLocale      = config('seo.locale');
    $seoLanguage    = config('seo.language');
    $seoUrl         = config('seo.canonical_url');
    $seoImagePath   = config('seo.image');
    $seoImage       = preg_match('#^https?://#i', $seoImagePath)
        ? $seoImagePath
        : $seoUrl . '/' . ltrim($seoImagePath, '/');
    $seoLogo        = $seoUrl . '/' . ltrim('images/landing/archana-logo.png', '/');
    $seoTwitter     = config('seo.social.twitter');
    $seoVerify      = array_filter(config('seo.verification', []));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', strtolower($seoLocale)) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ff7900">
    <meta name="format-detection" content="telephone=no">

    {{-- Primary SEO --}}
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="author" content="{{ $seoBrand }}">
    <meta name="publisher" content="{{ $seoBrand }}">
    <meta name="application-name" content="{{ $seoBrand }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="bingbot" content="index, follow">
    <meta name="rating" content="general">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <meta http-equiv="content-language" content="{{ $seoLanguage }}">

    <link rel="canonical" href="{{ $seoUrl }}">
    <link rel="alternate" hreflang="id" href="{{ $seoUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seoUrl }}">

    {{-- Search engine verification (rendered only when token is set) --}}
    @if (!empty($seoVerify['google']))
        <meta name="google-site-verification" content="{{ $seoVerify['google'] }}">
    @endif
    @if (!empty($seoVerify['bing']))
        <meta name="msvalidate.01" content="{{ $seoVerify['bing'] }}">
    @endif
    @if (!empty($seoVerify['yandex']))
        <meta name="yandex-verification" content="{{ $seoVerify['yandex'] }}">
    @endif
    @if (!empty($seoVerify['pinterest']))
        <meta name="p:domain_verify" content="{{ $seoVerify['pinterest'] }}">
    @endif
    @if (!empty($seoVerify['facebook']))
        <meta name="facebook-domain-verification" content="{{ $seoVerify['facebook'] }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ $seoLocale }}">
    <meta property="og:site_name" content="{{ $seoBrand }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="{{ $seoBrand }} - {{ config('seo.tagline') }}">
    <meta property="og:image:width" content="{{ config('seo.image_width') }}">
    <meta property="og:image:height" content="{{ config('seo.image_height') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ config('seo.twitter_card') }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <meta name="twitter:image:alt" content="{{ $seoBrand }} - {{ config('seo.tagline') }}">
    @if ($seoTwitter)
        <meta name="twitter:site" content="{{ $seoTwitter }}">
        <meta name="twitter:creator" content="{{ $seoTwitter }}">
    @endif

    {{-- Favicons & icons --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ $brandLogo }}">
    <link rel="apple-touch-icon" href="{{ $brandLogo }}">
    <link rel="manifest" href="{{ url('/manifest.webmanifest') }}">

    {{-- Performance --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preload" as="image" href="{{ asset('images/landing/hero.png') }}" fetchpriority="high">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Figtree', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
    </style>

    {{-- Structured data --}}
    @php
        $structuredData = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                '@id' => $seoUrl . '/#organization',
                'name' => $seoBrand,
                'legalName' => 'Archana App',
                'url' => $seoUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $seoLogo,
                ],
                'image' => $seoImage,
                'description' => $seoDescription,
                'sameAs' => array_values(array_filter([
                    config('seo.social.instagram'),
                    config('seo.social.whatsapp'),
                ])),
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => config('seo.contact.phone'),
                    'email' => config('seo.contact.email'),
                    'contactType' => 'customer support',
                    'areaServed' => 'ID',
                    'availableLanguage' => ['Indonesian', 'English'],
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'ID',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                '@id' => $seoUrl . '/#website',
                'name' => $seoBrand,
                'url' => $seoUrl,
                'inLanguage' => $seoLanguage,
                'publisher' => ['@id' => $seoUrl . '/#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => $seoUrl . '/?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                '@id' => $seoUrl . '/#webpage',
                'url' => $seoUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'inLanguage' => $seoLanguage,
                'isPartOf' => ['@id' => $seoUrl . '/#website'],
                'about' => ['@id' => $seoUrl . '/#organization'],
                'primaryImageOfPage' => [
                    '@type' => 'ImageObject',
                    'url' => $seoImage,
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda',
                        'item' => $seoUrl,
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Tentang',
                        'item' => $seoUrl . '/#about',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => 'Detail Aplikasi',
                        'item' => $seoUrl . '/#detail',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 4,
                        'name' => 'Pricing',
                        'item' => $seoUrl . '/#pricing',
                    ],
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'SoftwareApplication',
                '@id' => $seoUrl . '/#software',
                'name' => $seoBrand,
                'operatingSystem' => 'Web, Android, iOS',
                'applicationCategory' => 'BusinessApplication',
                'applicationSubCategory' => 'Hospitality Ordering Software',
                'description' => $seoDescription,
                'url' => $seoUrl,
                'image' => $seoImage,
                'inLanguage' => $seoLanguage,
                'publisher' => ['@id' => $seoUrl . '/#organization'],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '1500000',
                    'priceCurrency' => 'IDR',
                    'category' => 'Setup',
                    'availability' => 'https://schema.org/InStock',
                    'url' => $seoUrl . '/#pricing',
                ],
                'featureList' => [
                    '10 QR Acrylic full design',
                    'Pesan menu lewat QR Code',
                    'Pesanan masuk otomatis ke kasir, kitchen, bar, dan operator',
                    'Order tracking real-time',
                    'Multi area: kasir, kitchen, bar, operator',
                    'Pelatihan dan onboarding',
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '5',
                    'ratingCount' => '12',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $seoBrand . ' - Setup Smart Order Hotel',
                'description' => 'Setup awal aplikasi smart order Archana App untuk hotel, villa, dan restoran: aktivasi sistem, 10 QR Acrylic full design, onboarding, dan pelatihan tim.',
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $seoBrand,
                ],
                'image' => $seoImage,
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '1500000',
                    'priceCurrency' => 'IDR',
                    'availability' => 'https://schema.org/InStock',
                    'url' => $seoUrl . '/#pricing',
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '5',
                    'ratingCount' => '12',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => 'Apa itu Archana App?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Archana App adalah aplikasi smart order untuk hotel, villa, dan restoran. Tamu memesan menu lewat QR Code dari kamar atau meja, lalu pesanan langsung masuk ke kasir, kitchen, bar, dan operator.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Berapa biaya untuk menggunakan Archana App?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Setup awal Rp1.500.000 sekali bayar, sudah termasuk 10 QR Acrylic full design, aktivasi sistem, onboarding, dan pelatihan tim. Biaya transaksi Rp1.000 per transaksi ditanggung pihak hotel, tidak dibebankan ke tamu.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah Archana App cocok untuk hotel kecil?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Ya. Archana App dibuat ringan dan mudah dipakai sehingga cocok untuk hotel, villa, maupun restoran berbagai skala.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Apakah ada pelatihan untuk staf?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Ya, paket setup sudah termasuk pelatihan penggunaan aplikasi untuk tim operasional dan dukungan onboarding sampai aplikasi siap digunakan.',
                        ],
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Bagaimana cara mendaftar Archana App?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Hubungi tim Archana lewat WhatsApp di nomor +62 895-3630-76706 atau klik tombol Coba Gratis Sekarang di halaman ini untuk konsultasi.',
                        ],
                    ],
                ],
            ],
        ];
    @endphp
    @foreach ($structuredData as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach
</head>

<body class="min-h-screen overflow-x-hidden bg-[#fff7eb] text-[#1d120b] antialiased">
    <main class="min-h-screen overflow-hidden bg-[#fff7eb] text-[#1d120b]">
        {{-- Site header --}}
        <header id="site-header"
            class="fixed inset-x-0 top-0 z-50 border-b border-white/40 bg-[#fff7eb]/80 backdrop-blur-md transition-transform duration-300 ease-out will-change-transform"
            data-hidden="false">
            <div
                class="mx-auto flex h-20 max-w-[1500px] items-center justify-between px-5 sm:h-24 sm:px-8 lg:px-14">
                <a href="#" class="inline-flex items-center gap-3 sm:gap-4" aria-label="Archana App home">
                    <img src="{{ $brandLogo }}" alt="Logo Archana App"
                        class="h-10 w-auto object-contain sm:h-12 lg:h-14">
                    <span class="text-lg font-extrabold tracking-tight text-[#1d120b] sm:text-xl lg:text-2xl">
                        Archana App
                    </span>
                </a>

                <nav class="hidden items-center gap-8 text-base font-bold text-[#3f352d] lg:flex"
                    aria-label="Navigasi utama">
                    <a href="#about" class="transition hover:text-[#ff7900]">Tentang</a>
                    <a href="#detail" class="transition hover:text-[#ff7900]">Detail Aplikasi</a>
                    <a href="#pricing" class="transition hover:text-[#ff7900]">Pricing</a>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-[#ff7900] px-5 text-base font-bold text-white shadow-[0_10px_24px_rgba(255,121,0,.25)] transition hover:bg-[#f26d00] sm:px-6">
                        <span class="hidden sm:inline">Coba Gratis</span>
                        <span class="sm:hidden">Demo</span>
                        <span class="text-lg leading-none">→</span>
                    </a>

                    <button type="button" id="menu-toggle"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#1d120b]/15 bg-white/70 text-[#1d120b] transition hover:bg-white lg:hidden"
                        aria-controls="mobile-menu" aria-expanded="false" aria-label="Buka menu navigasi">
                        <svg id="menu-icon-open" viewBox="0 0 24 24" fill="none" class="h-6 w-6"
                            aria-hidden="true">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" />
                        </svg>
                        <svg id="menu-icon-close" viewBox="0 0 24 24" fill="none" class="hidden h-6 w-6"
                            aria-hidden="true">
                            <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu panel --}}
            <div id="mobile-menu"
                class="grid max-h-0 overflow-hidden border-t border-white/40 bg-[#fff7eb]/95 backdrop-blur-md transition-[max-height] duration-300 ease-out lg:hidden"
                aria-hidden="true">
                <nav class="mx-auto flex w-full max-w-[1500px] flex-col gap-1 px-5 py-4 text-base font-bold text-[#1d120b] sm:px-8"
                    aria-label="Navigasi mobile">
                    <a href="#about"
                        class="rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-[#ff7900]"
                        data-mobile-link>Tentang</a>
                    <a href="#detail"
                        class="rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-[#ff7900]"
                        data-mobile-link>Detail Aplikasi</a>
                    <a href="#pricing"
                        class="rounded-xl px-4 py-3 transition hover:bg-white/70 hover:text-[#ff7900]"
                        data-mobile-link>Pricing</a>
                    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                        class="mt-2 inline-flex h-12 items-center justify-center gap-2 rounded-full bg-[#ff7900] px-5 text-base font-bold text-white shadow-[0_10px_24px_rgba(255,121,0,.25)] transition hover:bg-[#f26d00]"
                        data-mobile-link>
                        Coba Gratis Sekarang
                        <span class="text-lg leading-none">→</span>
                    </a>
                </nav>
            </div>
        </header>

        {{-- Hero section --}}
        <section
            class="relative min-h-[760px] px-5 pb-5 pt-28 sm:px-8 sm:pb-6 sm:pt-32 lg:min-h-[860px] lg:px-14">
            <img src="{{ $heroImage }}" alt="Tamu hotel memesan layanan kamar melalui ponsel"
                class="absolute inset-0 z-0 hidden h-full w-full object-cover object-center sm:block lg:object-contain lg:object-right-top"
                loading="eager"
                onerror="this.style.display='none'">
            <div class="absolute inset-0 z-10 bg-[#fff8ed] sm:hidden"></div>
            <div
                class="absolute inset-0 z-10 hidden bg-[linear-gradient(90deg,#fff8ed_0%,rgba(255,248,237,.9)_25%,rgba(255,248,237,.46)_48%,rgba(255,248,237,0)_78%)] sm:block">
            </div>
            <div
                class="absolute inset-x-0 bottom-0 z-10 h-44 bg-gradient-to-t from-[#fff7eb] via-[#fff7eb]/80 to-transparent">
            </div>

            <div class="relative z-20 mx-auto flex min-h-[calc(760px-7rem)] max-w-[1500px] flex-col lg:min-h-[calc(860px-7rem)]">
                <div class="grid flex-1 items-center gap-10 py-10 lg:grid-cols-[1fr_.92fr] lg:py-14">
                    <div class="animate-fade-up max-w-3xl">
                        <div
                            class="mb-8 inline-flex max-w-full items-center gap-3 rounded-full bg-white/55 py-2 pl-2 pr-5 text-sm font-bold text-[#f26d00] shadow-[0_14px_45px_rgba(110,67,20,.08)] backdrop-blur sm:text-base">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#ff8a00]">
                                @include('partials.landing-icon', ['name' => 'bolt', 'class' => 'h-6 w-6'])
                            </span>
                            Smart Order untuk Hotel Modern
                        </div>

                        <h1
                            class="max-w-[720px] text-4xl font-black leading-[1.1] tracking-normal sm:text-6xl lg:text-7xl">
                            Upgrade room service hotel dengan
                            <span class="text-[#ff7900]">smart order</span> yang cepat.
                        </h1>

                        <p
                            class="mt-6 max-w-[610px] text-base font-medium leading-8 text-[#51483f] sm:mt-7 sm:text-xl sm:leading-9">
                            Archana App membantu tamu pesan menu dari kamar atau meja lewat QR Code. Pesanan
                            langsung masuk ke kasir, kitchen, bar, dan operator sehingga operasional lebih rapi
                            tanpa menambah beban staf.
                        </p>

                        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                            <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex h-14 w-full items-center justify-center gap-4 rounded-full bg-[#ff7900] px-6 text-base font-bold text-white shadow-[0_16px_35px_rgba(255,121,0,.25)] transition hover:bg-[#f26d00] sm:w-auto sm:px-8 sm:text-lg">
                                Coba Gratis Sekarang
                                <span class="text-3xl leading-none">→</span>
                            </a>
                            <a href="{{ $brochurePdf }}" download
                                class="hidden h-14 items-center justify-center gap-4 rounded-full px-2 text-lg font-extrabold text-[#1d120b] lg:inline-flex">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-white/75 text-[#1d120b] shadow-[0_12px_30px_rgba(92,55,20,.1)]">
                                    <span
                                        class="ml-1 h-0 w-0 border-y-[8px] border-l-[12px] border-y-transparent border-l-current"></span>
                                </span>
                                <span class="underline decoration-2 underline-offset-4">Lihat Cara Kerja</span>
                            </a>
                        </div>

                        <div class="animate-soft-float relative mt-8 overflow-hidden rounded-lg sm:hidden">
                            <img src="{{ $mobileHero }}"
                                alt="Staf Archana App menggunakan tablet untuk menerima pesanan QR"
                                class="h-auto w-full object-contain"
                                onerror="this.parentElement.style.display='none'">
                            <div
                                class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-[#fff8ed] via-[#fff8ed]/60 to-transparent">
                            </div>
                        </div>

                        <div class="mt-12 grid max-w-3xl grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                            @foreach ($features as $feature)
                                <article
                                    class="animate-fade-up min-h-28 rounded-lg bg-white/70 p-4 shadow-[0_20px_45px_rgba(106,63,17,.09)] backdrop-blur transition duration-300 hover:-translate-y-1 hover:bg-white/85 sm:min-h-44 sm:p-5">
                                    <span class="mb-3 block text-[#f7a000] sm:mb-4">
                                        @include('partials.landing-icon', [
                                            'name' => $feature['icon'],
                                            'class' => 'h-9 w-9 sm:h-10 sm:w-10',
                                        ])
                                    </span>
                                    <h2 class="text-sm font-black leading-5 sm:text-base">
                                        {{ $feature['title'] }}
                                    </h2>
                                    <p class="mt-2 hidden text-sm font-medium leading-6 text-[#4f463e] sm:block">
                                        {{ $feature['body'] }}
                                    </p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <div class="hidden min-h-[610px] lg:block"></div>
                </div>

                {{-- Flow strip --}}
                <div
                    class="relative mx-auto mb-3 hidden w-full max-w-5xl grid-cols-1 gap-4 rounded-lg bg-white/80 p-4 shadow-[0_18px_55px_rgba(84,48,13,.1)] backdrop-blur lg:grid lg:grid-cols-5">
                    @foreach ($flow as $i => $step)
                        @php([$icon, $title, $subtitle] = $step)
                        <div class="relative flex items-center gap-4 px-3 py-3">
                            <span class="text-[#f7a000]">
                                @include('partials.landing-icon', [
                                    'name' => $icon,
                                    'class' => 'h-10 w-10 shrink-0',
                                ])
                            </span>
                            <div>
                                <h3 class="text-sm font-black">{{ $title }}</h3>
                                <p class="text-sm font-medium text-[#54483f]">{{ $subtitle }}</p>
                            </div>
                            @if ($i < count($flow) - 1)
                                <span
                                    class="absolute -right-2 hidden text-2xl font-bold text-[#ff7900] lg:block">→</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- About section --}}
        <section id="about"
            class="relative min-h-[780px] overflow-hidden bg-[#fff7eb] px-5 py-16 sm:min-h-[820px] sm:px-8 sm:py-20 lg:px-14 lg:py-24">
            <img src="{{ $aboutImage }}"
                alt="Staf hotel menggunakan tablet untuk mengelola pesanan Archana App"
                class="absolute inset-0 h-full w-full object-cover object-[38%_top] sm:object-top"
                onerror="this.style.display='none'">
            <div
                class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,247,235,.74)_0%,rgba(255,247,235,.72)_100%)] sm:bg-[linear-gradient(90deg,rgba(255,247,235,0)_0%,rgba(255,247,235,.08)_40%,rgba(255,247,235,.48)_62%,rgba(255,247,235,.74)_100%)]">
            </div>
            <div
                class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-[#fff7eb] via-[#fff7eb]/40 to-transparent">
            </div>
            <div
                class="absolute inset-x-0 bottom-0 h-72 bg-gradient-to-t from-[#fff7eb] via-[#fff7eb]/35 to-transparent">
            </div>

            <div
                class="relative z-10 mx-auto flex min-h-[620px] max-w-[1500px] items-center justify-end sm:min-h-[660px]">
                <div
                    class="animate-fade-up w-full max-w-2xl rounded-lg bg-white/40 p-4 backdrop-blur-[2px] sm:bg-transparent sm:p-0 sm:backdrop-blur-none">
                    <div
                        class="mb-6 inline-flex items-center gap-3 rounded-full bg-white/70 py-2 pl-2 pr-5 text-base font-bold text-[#f26d00] shadow-[0_14px_45px_rgba(110,67,20,.08)] backdrop-blur">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#ff8a00]">
                            @include('partials.landing-icon', ['name' => 'screen', 'class' => 'h-6 w-6'])
                        </span>
                        Tentang Archana App
                    </div>

                    <h2
                        class="text-3xl font-black leading-tight tracking-normal text-[#1d120b] sm:text-5xl lg:text-6xl">
                        Operasional hotel lebih ringan, layanan tamu tetap terasa cepat.
                    </h2>

                    <p class="mt-6 text-base font-medium leading-8 text-[#51483f] sm:text-xl sm:leading-9">
                        Archana App membantu tim hotel menerima pesanan dari kamar atau meja lewat QR Code.
                        Pesanan langsung diteruskan ke bagian yang tepat, sehingga staf bisa fokus menyiapkan dan
                        mengantar pesanan tanpa bolak-balik mencatat manual.
                    </p>

                    <div class="mt-8 space-y-4">
                        @foreach ($aboutPoints as $point)
                            <div
                                class="flex gap-4 rounded-lg bg-white/70 p-4 shadow-[0_18px_45px_rgba(106,63,17,.07)] backdrop-blur transition duration-300 hover:-translate-y-1 hover:bg-white/85">
                                <span
                                    class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#ff7900] text-sm font-black text-white">
                                    @include('partials.landing-icon', ['name' => 'check', 'class' => 'h-4 w-4'])
                                </span>
                                <p class="text-sm font-semibold leading-7 text-[#3f352d] sm:text-base">
                                    {{ $point }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-9 grid gap-4 sm:grid-cols-3">
                        @foreach ($aboutStats as $stat)
                            @php([$value, $label] = $stat)
                            <div
                                class="rounded-lg border border-[#f7c982]/70 bg-white/65 p-5 shadow-[0_14px_32px_rgba(106,63,17,.06)] backdrop-blur">
                                <p class="text-3xl font-black text-[#ff7900]">{{ $value }}</p>
                                <p class="mt-2 text-sm font-bold leading-6 text-[#55483f]">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Detail section --}}
        <section id="detail"
            class="relative overflow-hidden bg-[#fff7eb] px-5 py-16 sm:px-8 sm:py-20 lg:px-14 lg:py-28">
            <div class="mx-auto grid max-w-[1500px] items-center gap-8 lg:grid-cols-[.82fr_1.18fr]">
                <div class="animate-fade-up w-full max-w-2xl">
                    <div
                        class="mb-6 inline-flex items-center gap-3 rounded-full bg-white/70 py-2 pl-2 pr-5 text-base font-bold text-[#f26d00] shadow-[0_14px_45px_rgba(110,67,20,.08)] backdrop-blur">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#ff8a00]">
                            @include('partials.landing-icon', ['name' => 'bars', 'class' => 'h-6 w-6'])
                        </span>
                        Detail Aplikasi
                    </div>

                    <h2
                        class="text-3xl font-black leading-tight tracking-normal text-[#1d120b] sm:text-5xl lg:text-6xl">
                        Semua langkah pemesanan dibuat jelas dari menu sampai order selesai.
                    </h2>

                    <p class="mt-6 text-base font-medium leading-8 text-[#51483f] sm:text-xl sm:leading-9">
                        Archana App menghubungkan pengalaman tamu dan kebutuhan operasional hotel dalam satu alur.
                        Menu tampil menarik untuk tamu, checkout mudah dipahami, dan setiap pesanan langsung
                        terbaca oleh tim yang bertugas.
                    </p>

                    <div class="mt-9 grid gap-4 sm:grid-cols-2">
                        @foreach ($detailItems as $item)
                            @php([$title, $body] = $item)
                            <article
                                class="rounded-lg bg-white/70 p-5 shadow-[0_18px_45px_rgba(106,63,17,.07)] backdrop-blur transition duration-300 hover:-translate-y-1 hover:bg-white/85">
                                <h3 class="text-lg font-black text-[#1d120b]">{{ $title }}</h3>
                                <p class="mt-3 text-sm font-semibold leading-7 text-[#55483f]">{{ $body }}</p>
                            </article>
                        @endforeach
                    </div>

                    <a href="#pricing"
                        class="mt-9 inline-flex h-14 w-full items-center justify-center gap-4 rounded-full bg-[#ff7900] px-6 text-base font-bold text-white shadow-[0_16px_35px_rgba(255,121,0,.25)] transition hover:bg-[#f26d00] sm:w-auto sm:px-8 sm:text-lg">
                        Lihat Paket Archana
                        <span class="text-3xl leading-none">→</span>
                    </a>
                </div>

                <div class="animate-soft-float relative overflow-hidden lg:-mr-20">
                    <img src="{{ $detailImage }}"
                        alt="Detail fitur Archana App dengan tampilan menu, checkout, dan status pesanan"
                        class="relative mx-auto h-auto w-full max-w-[720px] object-contain lg:ml-auto lg:max-w-[980px]"
                        onerror="this.parentElement.style.display='none'">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 w-14 bg-gradient-to-r from-[#fff7eb] to-transparent sm:w-28">
                    </div>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 w-14 bg-gradient-to-l from-[#fff7eb] to-transparent sm:w-16">
                    </div>
                    <div
                        class="pointer-events-none absolute inset-x-0 top-0 h-20 bg-gradient-to-b from-[#fff7eb] to-transparent">
                    </div>
                    <div
                        class="pointer-events-none absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-[#fff7eb] to-transparent">
                    </div>
                </div>
            </div>
        </section>

        {{-- Pricing section --}}
        <section id="pricing"
            class="relative overflow-hidden bg-[#fff7eb] px-5 py-16 sm:px-8 sm:py-20 lg:px-14 lg:py-28">
            <div class="mx-auto max-w-[1500px]">
                <div class="grid items-center gap-8 lg:grid-cols-[.9fr_1fr_.95fr]">
                    <div class="animate-fade-up max-w-xl">
                        <div
                            class="mb-6 inline-flex items-center gap-3 rounded-full bg-white/70 py-2 pl-2 pr-5 text-base font-bold text-[#f26d00] shadow-[0_14px_45px_rgba(110,67,20,.08)] backdrop-blur">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#ff8a00]">
                                @include('partials.landing-icon', ['name' => 'shield', 'class' => 'h-6 w-6'])
                            </span>
                            Pricing Archana App
                        </div>

                        <h2
                            class="text-3xl font-black leading-tight tracking-normal text-[#1d120b] sm:text-5xl">
                            Mulai smart order hotel tanpa ribet dari hari pertama.
                        </h2>

                        <p class="mt-6 text-base font-medium leading-8 text-[#51483f] sm:text-lg sm:leading-9">
                            Paket Archana App dibuat sederhana: satu biaya setup untuk menyiapkan sistem, QR
                            acrylic, dan pelatihan tim. Setelah aktif, biaya berjalan mengikuti transaksi yang
                            sukses masuk lewat aplikasi, ditanggung pihak hotel dan tidak dibebankan ke tamu.
                        </p>

                        <div
                            class="mt-8 rounded-lg border border-[#f7c982]/70 bg-white/65 p-6 shadow-[0_18px_45px_rgba(106,63,17,.07)] backdrop-blur">
                            <p
                                class="text-sm font-black uppercase tracking-[.14em] text-[#ff7900]">
                                Biaya transaksi
                            </p>
                            <div class="mt-3 flex flex-wrap items-end gap-2">
                                <span class="text-3xl font-black text-[#1d120b] sm:text-4xl">Rp1.000</span>
                                <span class="pb-2 text-base font-bold text-[#6b5b4d]">/ transaksi</span>
                            </div>
                            <p class="mt-3 text-sm font-semibold leading-6 text-[#55483f]">
                                Ditanggung pihak hotel, tamu hanya membayar harga menu. Cocok untuk hotel yang
                                ingin biaya berjalan tetap ringan dan mudah dihitung.
                            </p>
                        </div>
                    </div>

                    <article
                        class="animate-fade-up relative rounded-lg border border-white/80 bg-white/80 p-5 shadow-[0_28px_70px_rgba(106,63,17,.14)] backdrop-blur transition duration-300 hover:-translate-y-1 sm:p-7">
                        <div
                            class="mb-5 inline-flex rounded-full bg-[#fff1d6] px-4 py-2 text-sm font-black text-[#ff7900] sm:absolute sm:right-6 sm:top-6 sm:mb-0">
                            Paket Awal
                        </div>
                        <p class="text-base font-black text-[#ff7900]">Setup Archana App</p>
                        <div class="mt-4 flex flex-wrap items-end gap-2">
                            <span
                                class="text-4xl font-black tracking-normal text-[#1d120b] sm:text-5xl">Rp1.500.000</span>
                            <span class="pb-2 text-sm font-bold text-[#6b5b4d]">/ sekali bayar</span>
                        </div>
                        <p class="mt-5 text-base font-semibold leading-7 text-[#55483f]">
                            Aktivasi sistem, 10 QR Acrylic full design, onboarding, dan pelatihan penggunaan
                            aplikasi untuk tim hotel. Tambahan QR acrylic tersedia sesuai kebutuhan.
                        </p>

                        <div class="mt-7 space-y-3">
                            @foreach ($pricingBenefits as $benefit)
                                <div class="flex gap-3">
                                    <span
                                        class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#ff7900] text-white">
                                        @include('partials.landing-icon', ['name' => 'check', 'class' => 'h-4 w-4'])
                                    </span>
                                    <p class="text-sm font-bold leading-6 text-[#3f352d]">{{ $benefit }}</p>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                            class="mt-8 inline-flex h-14 w-full items-center justify-center gap-4 rounded-full bg-[#ff7900] px-6 text-base font-bold text-white shadow-[0_16px_35px_rgba(255,121,0,.25)] transition hover:bg-[#f26d00] sm:px-8 sm:text-lg">
                            Mulai Konsultasi
                        </a>
                    </article>

                    <div
                        class="animate-soft-float relative mx-auto w-full max-w-[520px] overflow-hidden rounded-lg lg:max-w-none">
                        <img src="{{ $pricingImage }}"
                            alt="Scan QR Acrylic full design untuk Archana App"
                            class="h-auto w-full object-contain"
                            onerror="this.parentElement.style.display='none'">
                        <div
                            class="pointer-events-none absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-[#fff7eb] to-transparent">
                        </div>
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#fff7eb] to-transparent">
                        </div>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#fff7eb] to-transparent">
                        </div>
                        <div
                            class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#fff7eb] to-transparent">
                        </div>
                    </div>
                </div>

                <div
                    class="mt-12 grid gap-4 rounded-lg bg-white/80 p-4 shadow-[0_18px_55px_rgba(84,48,13,.1)] backdrop-blur sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($trustItems as $i => $item)
                        @php([$icon, $title, $body] = $item)
                        <div
                            class="flex items-center gap-4 px-4 py-3 {{ $i > 0 ? 'lg:border-l lg:border-[#f1d9b7]' : '' }}">
                            <span class="text-[#ff8a00]">
                                @include('partials.landing-icon', [
                                    'name' => $icon,
                                    'class' => 'h-11 w-11 shrink-0',
                                ])
                            </span>
                            <div>
                                <h3 class="text-sm font-black text-[#1d120b]">{{ $title }}</h3>
                                <p class="mt-1 text-xs font-semibold leading-5 text-[#55483f]">{{ $body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-[#1d120b] px-5 py-12 text-white sm:px-8 lg:px-14">
            <div class="mx-auto grid max-w-[1500px] gap-8 md:grid-cols-[1.2fr_1fr] md:items-center">
                <div>
                    <a href="#" class="inline-flex items-center gap-4" aria-label="Archana App home">
                        <img src="{{ $brandLogo }}" alt="" class="h-10 w-auto object-contain sm:h-12">
                        <span class="text-xl font-extrabold tracking-normal sm:text-2xl">Archana App</span>
                    </a>
                    <p class="mt-5 max-w-xl text-base font-medium leading-7 text-white/75">
                        Smart order untuk hotel modern: bantu tamu pesan lebih cepat, dan bantu tim operasional
                        bekerja lebih rapi dari satu sistem.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 md:justify-end">
                    <a href="https://wa.me/62895363076706" target="_blank" rel="noopener noreferrer"
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-[#ff7900]"
                        aria-label="Hubungi Archana App via WhatsApp">
                        @include('partials.landing-icon', ['name' => 'whatsapp', 'class' => 'h-6 w-6'])
                    </a>
                    <a href="https://www.instagram.com/archana.tech/" target="_blank" rel="noopener noreferrer"
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-[#ff7900]"
                        aria-label="Kunjungi Instagram Archana Tech">
                        @include('partials.landing-icon', ['name' => 'instagram', 'class' => 'h-6 w-6'])
                    </a>
                    <a href="mailto:archanaaditama@gmail.com"
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-[#ff7900]"
                        aria-label="Kirim email ke Archana App">
                        @include('partials.landing-icon', ['name' => 'mail', 'class' => 'h-6 w-6'])
                    </a>
                </div>
            </div>

            <div
                class="mx-auto mt-10 flex max-w-[1500px] flex-col gap-3 border-t border-white/15 pt-6 text-sm font-semibold text-white/55 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} Archana App. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('platform.tnc') }}" class="transition hover:text-white/85">
                        Syarat &amp; Ketentuan
                    </a>
                    <span class="text-white/25">|</span>
                    <p>Built for hotel, villa, and hospitality operations.</p>
                </div>
            </div>
        </footer>
    </main>

    <script>
        (function () {
            var header = document.getElementById('site-header');
            if (!header) return;

            var lastY = window.pageYOffset || document.documentElement.scrollTop;
            var ticking = false;
            var hidden = false;
            var threshold = 8; // ignore tiny jitters
            var topReveal = 80; // always visible near top

            function update() {
                var currentY = window.pageYOffset || document.documentElement.scrollTop;
                var delta = currentY - lastY;
                var menu = document.getElementById('mobile-menu');
                var menuOpen = menu && menu.dataset.open === 'true';

                if (menuOpen) {
                    if (hidden) {
                        header.classList.remove('-translate-y-full');
                        hidden = false;
                    }
                } else if (currentY <= topReveal) {
                    if (hidden) {
                        header.classList.remove('-translate-y-full');
                        hidden = false;
                    }
                } else if (delta > threshold) {
                    if (!hidden) {
                        header.classList.add('-translate-y-full');
                        hidden = true;
                    }
                } else if (delta < -threshold) {
                    if (hidden) {
                        header.classList.remove('-translate-y-full');
                        hidden = false;
                    }
                }

                lastY = currentY;
                ticking = false;
            }

            window.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(update);
                    ticking = true;
                }
            }, { passive: true });
        })();

        (function () {
            var toggle = document.getElementById('menu-toggle');
            var menu = document.getElementById('mobile-menu');
            var iconOpen = document.getElementById('menu-icon-open');
            var iconClose = document.getElementById('menu-icon-close');
            if (!toggle || !menu) return;

            function setMenu(open) {
                menu.dataset.open = open ? 'true' : 'false';
                menu.setAttribute('aria-hidden', open ? 'false' : 'true');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

                if (open) {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    iconOpen && iconOpen.classList.add('hidden');
                    iconClose && iconClose.classList.remove('hidden');
                } else {
                    menu.style.maxHeight = '0px';
                    iconOpen && iconOpen.classList.remove('hidden');
                    iconClose && iconClose.classList.add('hidden');
                }
            }

            toggle.addEventListener('click', function () {
                setMenu(menu.dataset.open !== 'true');
            });

            // Close on link click
            menu.querySelectorAll('[data-mobile-link]').forEach(function (link) {
                link.addEventListener('click', function () { setMenu(false); });
            });

            // Close when resizing to desktop
            window.addEventListener('resize', function () {
                if (window.matchMedia('(min-width: 1024px)').matches) {
                    setMenu(false);
                    menu.style.maxHeight = '';
                }
            });

            // Close on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') setMenu(false);
            });
        })();
    </script>
</body>

</html>
