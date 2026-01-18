{{-- Mobile Bottom Navigation Bar --}}
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg"
     style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex justify-around items-center h-16 px-2">
        {{-- Home / Tree --}}
        <button wire:click="$dispatch('navigate-to', { page: 'tree' })"
                class="flex flex-col items-center justify-center flex-1 py-2 rounded-lg transition-colors active:bg-gray-100"
                {{ request()->routeIs('family-tree') ? 'data-active="true"' : '' }}>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" 
                 :class="$el.closest('[data-active=true]') ? 'text-[#C41E3A]' : 'text-gray-400'"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs font-medium"
                  :class="$el.closest('[data-active=true]') ? 'text-[#C41E3A]' : 'text-gray-600'">
                Cây
            </span>
        </button>

        {{-- Search --}}
        <button wire:click="$dispatch('toggle-search')"
                class="flex flex-col items-center justify-center flex-1 py-2 rounded-lg transition-colors active:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-xs font-medium text-gray-600">Tìm</span>
        </button>

        {{-- Add (Prominent) --}}
        <button wire:click="$dispatch('open-add-modal')"
                class="flex items-center justify-center w-14 h-14 -mt-6 bg-gradient-to-br from-[#C41E3A] to-[#A01830] rounded-full shadow-lg active:scale-95 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 4v16m8-8H4" />
            </svg>
        </button>

        {{-- Stats --}}
        <button wire:click="$dispatch('navigate-to', { page: 'stats' })"
                class="flex flex-col items-center justify-center flex-1 py-2 rounded-lg transition-colors active:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-xs font-medium text-gray-600">Thống kê</span>
        </button>

        {{-- Menu --}}
        <button wire:click="$dispatch('toggle-sidebar')"
                class="flex flex-col items-center justify-center flex-1 py-2 rounded-lg transition-colors active:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-xs font-medium text-gray-600">Menu</span>
        </button>
    </div>
</div>

{{-- Spacer to prevent content from being hidden behind bottom nav on mobile --}}
<div class="lg:hidden h-16"></div>
