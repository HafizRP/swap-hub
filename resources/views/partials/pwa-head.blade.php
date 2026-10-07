<!-- PWA Primary Meta Tags -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#0d9488" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="{{ config('app.name', 'Swap Hub') }}">

<!-- Apple / iOS Safari PWA Meta Tags -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Swap Hub') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-180x180.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">

<!-- Microsoft Windows Tiles -->
<meta name="msapplication-TileColor" content="#0f172a">
<meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
