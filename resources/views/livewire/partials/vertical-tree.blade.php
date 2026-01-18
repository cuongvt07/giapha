{{-- Vertical Tree Layout for Mobile --}}
<div class="lg:hidden w-full h-full overflow-y-auto bg-gradient-to-b from-slate-50 to-white">
    
    {{-- Mobile Header --}}
    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-4 py-3">
        <div class="flex items-center justify-between">
            <button wire:click="$dispatch('toggle-sidebar')" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <h1 class="text-base font-bold text-[#C41E3A] font-serif flex-1 text-center">
                {{ $filters['treeTitle'] ?? 'Gia Phả' }}
            </h1>
            
            <button wire:click="$dispatch('toggle-search')"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Breadcrumb (when focused) --}}
    @if (!empty($breadcrumbPath))
        <div class="bg-blue-50 border-b border-blue-100 px-4 py-2">
            <div class="flex items-center gap-1 overflow-x-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 flex-shrink-0"
                     viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                @foreach ($breadcrumbPath as $index => $ancestor)
                    @if ($index > 0)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 flex-shrink-0"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                    @endif
                    <button wire:click="focusOnPerson({{ $ancestor['id'] }})"
                            class="text-xs font-medium whitespace-nowrap {{ $loop->last ? 'text-blue-700 font-bold' : 'text-blue-600' }}">
                        {{ $ancestor['name'] }}
                    </button>
                @endforeach
            </div>
        </div>
        
        <div class="px-4 py-2 bg-white border-b border-gray-200">
            <button wire:click="resetToRoot"
                    class="text-sm text-blue-600 font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                Quay về cây gốc
            </button>
        </div>
    @endif

    {{-- Tree Content --}}
    <div class="px-4 py-4 space-y-3 pb-24">
        @if ($rootPerson)
            @include('livewire.partials.vertical-tree-node', [
                'person' => $rootPerson,
                'depth' => 0,
                'filters' => $filters,
            ])
        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Chưa có dữ liệu gia phả</h3>
                <p class="text-sm text-gray-500 mb-6">Bắt đầu xây dựng cây gia phả của bạn</p>
                <button wire:click="$dispatch('open-add-modal')"
                        class="px-6 py-3 bg-[#C41E3A] text-white rounded-lg font-medium shadow-lg active:scale-95 transition-transform">
                    Thêm người đầu tiên
                </button>
            </div>
        @endif
    </div>
</div>
