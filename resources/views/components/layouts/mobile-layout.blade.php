<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Gia Phả Việt' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            overscroll-behavior: none;
        }

        /* Bottom sheet styles */
        .bottom-sheet {
            transition: transform 0.3s ease-out;
        }

        /* Drawer styles */
        .drawer-overlay {
            transition: opacity 0.3s ease-out;
        }

        .drawer-content {
            transition: transform 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-900 antialiased h-full w-full overflow-hidden">
    {{ $slot }}
    @livewireScripts
</body>

</html>
