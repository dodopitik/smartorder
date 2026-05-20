<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/robots.txt', function () {
    $base = rtrim(config('seo.canonical_url'), '/');

    $body = "User-agent: *\n"
        . "Allow: /\n"
        . "Disallow: /platform/\n"
        . "Disallow: /hotel/\n"
        . "Disallow: /admin/\n"
        . "Disallow: /storage/\n"
        . "Disallow: /login\n"
        . "Disallow: /register\n"
        . "Disallow: /password/\n\n"
        . 'Sitemap: ' . $base . "/sitemap.xml\n";

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', function () {
    $now  = now()->toAtomString();
    $base = rtrim(config('seo.canonical_url'), '/');

    $urls = [
        ['loc' => $base . '/',                              'priority' => '1.0', 'changefreq' => 'weekly'],
        ['loc' => $base . '/#about',                        'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => $base . '/#detail',                       'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => $base . '/#pricing',                      'priority' => '0.9', 'changefreq' => 'monthly'],
        ['loc' => $base . '/platform/terms-and-conditions', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ];

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
    foreach ($urls as $u) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
        $xml .= '    <xhtml:link rel="alternate" hreflang="id" href="' . htmlspecialchars($u['loc'], ENT_XML1) . '" />' . "\n";
        $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . htmlspecialchars($u['loc'], ENT_XML1) . '" />' . "\n";
        $xml .= '    <lastmod>' . $now . "</lastmod>\n";
        $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
        $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>' . "\n";

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::get('/manifest.webmanifest', function () {
    $base = rtrim(config('seo.canonical_url'), '/');
    $logo = $base . '/images/landing/archana-logo.png';

    $manifest = [
        'name'             => config('seo.brand'),
        'short_name'       => 'Archana',
        'description'      => config('seo.description'),
        'start_url'        => '/',
        'scope'            => '/',
        'display'          => 'standalone',
        'orientation'      => 'portrait',
        'background_color' => '#fff7eb',
        'theme_color'      => '#ff7900',
        'lang'             => config('seo.language'),
        'icons'            => [
            ['src' => $logo, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $logo, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $logo, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
        ],
    ];

    return response()->json($manifest, 200, [
        'Content-Type' => 'application/manifest+json; charset=UTF-8',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
})->name('manifest');

Route::get('/platform', [LandingController::class, 'portal'])->name('platform.portal');
Route::get('/platform/terms-and-conditions', function () {
    return view('platform.tnc');
})->name('platform.tnc');

Route::middleware(['auth', 'tenant.context'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
