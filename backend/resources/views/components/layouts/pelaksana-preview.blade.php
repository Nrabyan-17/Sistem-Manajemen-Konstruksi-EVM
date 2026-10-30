<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DEMO UI Pelaksana · PT Bintang Gandari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/pelaksana-preview.css">
    {{-- Preview-only helpers; the production shell remains layouts/app. --}}
    <style>[x-cloak] { display: none !important; }</style>
    @livewireStyles
</head>
<body class="h-full overflow-hidden bg-slate-50 font-sans text-slate-800 antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
