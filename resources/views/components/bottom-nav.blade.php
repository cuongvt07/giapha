{{-- Mobile Bottom Actions --}}
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 pointer-events-none"
    style="padding-bottom: env(safe-area-inset-bottom);">

    {{-- Center Button: "Hãy Bắt Đầu" --}}
    <div class="flex justify-center mb-6 pointer-events-auto" style="touch-action: auto;">
        <button @click="console.log('Reset clicked'); $wire.resetToRoot()"
            class="px-8 py-3 bg-gray-200 hover:bg-gray-300 active:bg-gray-400 text-gray-800 font-bold text-sm rounded-full shadow-lg transition-all">
            Hãy Bắt Đầu
        </button>
    </div>

    {{-- FABs (Right Side Stack) --}}
    <div class="absolute bottom-6 right-4 flex flex-col gap-3 pointer-events-auto" style="touch-action: auto;">
        {{-- Settings/Filter FAB --}}
        <button @click="console.log('Toggle sidebar clicked'); $dispatch('toggle-sidebar')"
            class="w-14 h-14 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
        </button>

        {{-- Home FAB --}}
        <button @click="console.log('Home clicked'); $wire.resetToRoot()"
            class="w-14 h-14 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </button>
    </div>
</div>

{{-- Spacer to prevent content from being hidden behind FABs on mobile --}}
<div class="lg:hidden h-24"></div>
