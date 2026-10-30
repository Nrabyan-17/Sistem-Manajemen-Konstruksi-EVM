<?php

/**
 * Loopback-only development router. No production route or shared layout changes.
 * From backend/: php -S 127.0.0.1:8091 tests/pelaksana-preview.php
 * Demo: http://127.0.0.1:8091/ ; empty boundary: /?empty=1
 * Styles: build Vite into %TEMP%/pelaksana-preview-build (never public/build).
 * No login simulation, database reads/writes, submissions, or approval operations.
 */
if (PHP_SAPI !== 'cli-server' || !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)) {
    http_response_code(404);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$build = sys_get_temp_dir().'/pelaksana-preview-build';
if ($path === '/pelaksana-preview.css') {
    $manifest = $build.'/.vite/manifest.json';
    if (!is_file($manifest)) {
        $manifest = $build.'/manifest.json';
    }
    $entries = is_file($manifest) ? json_decode(file_get_contents($manifest), true) : [];
    $css = $entries['resources/css/app.css']['file'] ?? '';
    if (!$css || !is_file($build.'/'.$css)) {
        http_response_code(503);
        exit('Build preview CSS first into the temporary pelaksana-preview-build directory.');
    }
    header('Content-Type: text/css; charset=UTF-8');
    readfile($build.'/'.$css);
    exit;
}
if ($path === '/images/logo-bintang-gandari.jpg') {
    header('Content-Type: image/jpeg');
    readfile(__DIR__.'/../public/images/logo-bintang-gandari.jpg');
    exit;
}
// Livewire 4 uses a randomized endpoint prefix; only framework assets/update allowed.
if ($path !== '/' && !preg_match('~^/livewire(?:-[a-zA-Z0-9]+)?/(?:livewire(?:\.min)?\.js|update|(?:js|css)/[a-zA-Z0-9._-]+)$~', $path)) {
    http_response_code(404);
    exit;
}

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();
$compiled = sys_get_temp_dir().'/pelaksana-preview-views';
$sessions = sys_get_temp_dir().'/pelaksana-preview-sessions';
foreach ([$compiled, $sessions] as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0700, true);
    }
}
config([
    'app.debug' => false,
    'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
    'session.driver' => 'file', 'session.files' => $sessions,
    'session.cookie' => 'pelaksana_preview_session', 'session.secure' => false,
    'cache.default' => 'array', 'queue.default' => 'sync',
    'logging.default' => 'null', 'view.compiled' => $compiled,
]);
// Explicit preview document; no authentication state or production navigation.
Illuminate\Support\Facades\Route::get('/', function () {
    return Illuminate\Support\Facades\Blade::render(
        '<x-layouts.pelaksana-preview><livewire:pelaksana-workspace :demo="$demo" /></x-layouts.pelaksana-preview>',
        ['demo' => !request()->boolean('empty')],
        deleteCachedView: true,
    );
})->middleware('web');
$app->handleRequest(Illuminate\Http\Request::capture());
