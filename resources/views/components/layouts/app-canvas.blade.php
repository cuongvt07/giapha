<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Gia Phả Việt' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/js/jsplumb.min.js"></script>
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            /* Critical for the infinite canvas feel */
            /* touch-action: none; -- REMOVED: This was blocking all touch events on mobile */
        }

        /* Apply touch-action: none only to the tree canvas */
        .tree-canvas-touch {
            touch-action: none;
        }

        /* Pulse glow animation for focused person */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(168, 85, 247, 0.4), 0 0 40px rgba(168, 85, 247, 0.2);
            }

            50% {
                box-shadow: 0 0 30px rgba(168, 85, 247, 0.6), 0 0 60px rgba(168, 85, 247, 0.3);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900 antialiased h-full w-full relative overflow-hidden">

    <!-- Top Navigation Bar -->
    <header
        class="absolute top-0 left-0 right-0 h-14 bg-white/90 backdrop-blur-sm border-b border-gray-200 z-40 hidden lg:flex items-center justify-between px-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-lg font-serif">
                G
            </div>
            <h1 class="font-serif font-bold text-lg text-primary-900 hidden sm:block">Gia Phả Việt</h1>
        </div>

        <div class="flex items-center gap-2">
            <!-- Global Actions -->
            <button class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-full transition-colors"
                title="Export" @click="window.dispatchEvent(new CustomEvent('export-tree-triggered'))">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
            <div class="w-px h-6 bg-gray-200 mx-1"></div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700 hidden md:block">Admin User</span>
                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden border border-gray-300">
                    <!-- User Avatar Placeholder -->
                    <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="absolute inset-0 top-0 lg:top-14 z-0">
        {{ $slot }}
    </main>

    <!-- Sidebars Container (Removed - moved to family-tree component) -->

    @livewireScripts
</body>

</html>
