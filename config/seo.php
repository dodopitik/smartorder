<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Canonical URL
    |--------------------------------------------------------------------------
    |
    | This is the public URL the landing page should rank for in search
    | engines. It is used for canonical tags, Open Graph, Twitter Card,
    | sitemap.xml, robots.txt, and JSON-LD payloads. Keep this set to the
    | production domain even when running locally so previews and shares
    | always point to the live site.
    |
    */

    'canonical_url' => rtrim(env('SEO_CANONICAL_URL', 'https://smart-order.archana.co.id'), '/'),

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    'brand'        => env('SEO_BRAND', 'Archana App'),
    'tagline'      => env('SEO_TAGLINE', 'Smart Order untuk Hotel & Restoran'),
    'locale'       => env('SEO_LOCALE', 'id_ID'),
    'language'     => env('SEO_LANGUAGE', 'id-ID'),

    /*
    |--------------------------------------------------------------------------
    | Default landing meta
    |--------------------------------------------------------------------------
    */

    'title'        => env('SEO_TITLE', 'Archana App: Aplikasi Smart Order Hotel, Villa & Restoran via QR Code'),
    'description'  => env('SEO_DESCRIPTION', 'Archana App adalah aplikasi smart order untuk hotel, villa, dan restoran. Tamu memesan menu lewat QR Code dari kamar atau meja, pesanan otomatis terkirim ke kasir, kitchen, bar, dan operator. Setup mulai Rp1.500.000 sudah termasuk 10 QR Acrylic.'),
    'keywords'     => env('SEO_KEYWORDS', 'smart order hotel, aplikasi smart order, qr code menu hotel, sistem pemesanan hotel, room service digital, aplikasi pemesanan qr, smart order restoran, archana app, archana smart order, qr menu hotel, digital menu hotel indonesia, aplikasi pesan kamar hotel, sistem kasir hotel, smart ordering villa, qr ordering indonesia, smart order archana'),

    /*
    |--------------------------------------------------------------------------
    | Default share image (relative to canonical_url, must be absolute when
    | served).
    |--------------------------------------------------------------------------
    */

    'image'        => env('SEO_IMAGE', '/images/landing/hero.png'),
    'image_width'  => 1200,
    'image_height' => 630,

    /*
    |--------------------------------------------------------------------------
    | Verification tokens
    |--------------------------------------------------------------------------
    |
    | Paste each token from Search Console / Bing / Yandex / Facebook etc.
    | The meta tag is rendered only when a token is present.
    |
    */

    'verification' => [
        'google'   => env('SEO_GOOGLE_VERIFICATION'),
        'bing'     => env('SEO_BING_VERIFICATION'),
        'yandex'   => env('SEO_YANDEX_VERIFICATION'),
        'pinterest'=> env('SEO_PINTEREST_VERIFICATION'),
        'facebook' => env('SEO_FACEBOOK_VERIFICATION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social profiles & contact (used by Organization schema)
    |--------------------------------------------------------------------------
    */

    'social' => [
        'instagram' => env('SEO_INSTAGRAM_URL', 'https://www.instagram.com/archana.tech/'),
        'whatsapp'  => env('SEO_WHATSAPP_URL', 'https://wa.me/62895363076706'),
        'twitter'   => env('SEO_TWITTER_HANDLE'),
    ],

    'contact' => [
        'phone' => env('SEO_PHONE', '+62-895-3630-76706'),
        'email' => env('SEO_EMAIL', 'archanaaditama@gmail.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter card defaults
    |--------------------------------------------------------------------------
    */

    'twitter_card' => env('SEO_TWITTER_CARD', 'summary_large_image'),

];
