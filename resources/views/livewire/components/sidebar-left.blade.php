
<div>
    {{-- Main Sidebar (Desktop/Tablet/Off-canvas) --}}
    <div wire:ignore.self
        class="h-full flex flex-col bg-white shadow-xl border-r border-gray-200 transition-transform duration-300 ease-in-out z-[40]
               fixed inset-y-0 left-0 w-80"
        style="touch-action: auto;" x-data="{
            collapsed: $persist(false).as('sidebar-collapsed'),
            openFilters: true,
            openStats: true,
            toggle() {
                this.collapsed = !this.collapsed;
            }
        }" @toggle-sidebar.window="toggle()"
        :class="collapsed ? '-translate-x-full' : 'translate-x-0'">
    
        {{-- Toggle Button (Desktop only) --}}
        <button @click="toggle()"
            class="hidden lg:flex absolute top-3 -right-8 w-8 h-8 bg-white border border-gray-100 shadow-sm rounded-r-md items-center justify-center text-gray-400 hover:text-primary-600 focus:outline-none transition-all duration-300 z-50 group hover:w-10 overflow-visible"
            title="Ẩn/Hiện Menu">
            
            {{-- If NOT Collapsed (Open) -> Show Left Arrows (Collapse) --}}
            <template x-if="!collapsed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </template>
    
            {{-- If Collapsed (Closed) -> Show Right Arrows (Expand) --}}
            <template x-if="collapsed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </template>
        </button>
    
        {{-- Mobile overlay backdrop for Sidebar (Not Mobile Drawer) --}}
        <div class="lg:hidden fixed inset-0 bg-black/50 z-30 transition-opacity" x-show="!collapsed"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="collapsed = true">
        </div>
    
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-[#C41E3A] text-white">
            <div class="flex items-center gap-2 overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <h2 class="font-bold text-lg font-serif tracking-wide whitespace-nowrap">Gia Phả Việt</h2>
            </div>
            {{-- Internal toggle button kept for mobile or alternative usage --}}
            <button @click="toggle()" class="lg:hidden p-1.5 hover:bg-white/20 rounded-lg transition-colors flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>
    
        <!-- Navigation Menu (Compact) -->
        <div class="flex border-b border-gray-100 bg-gray-50">
            <button wire:click="setTab('tree')"
                class="flex-1 py-3 text-xs font-bold transition-colors {{ $activeTab === 'tree' ? 'text-[#C41E3A] border-b-2 border-[#C41E3A] bg-white shadow-sm' : 'text-gray-500 hover:bg-white hover:text-gray-700' }}">
                <span class="block mb-1 text-lg">🌳</span>
                Cây Gia Phả
            </button>
            <button wire:click="setTab('stats')"
                class="flex-1 py-3 text-xs font-bold transition-colors {{ $activeTab === 'stats' ? 'text-[#C41E3A] border-b-2 border-[#C41E3A] bg-white shadow-sm' : 'text-gray-500 hover:bg-white hover:text-gray-700' }}">
                <span class="block mb-1 text-lg">📊</span>
                Thống Kê
            </button>
            <button wire:click="setTab('list')"
                class="flex-1 py-3 text-xs font-bold transition-colors {{ $activeTab === 'list' ? 'text-[#C41E3A] border-b-2 border-[#C41E3A] bg-white shadow-sm' : 'text-gray-500 hover:bg-white hover:text-gray-700' }}">
                <span class="block mb-1 text-lg">📋</span>
                Danh Sách
            </button>
        </div>
    
        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-4 space-y-6 custom-scrollbar">
    
            {{-- TAB: TREE --}}
            @if ($activeTab === 'tree')
                <!-- FILTERS PANEL -->
                {{-- Temporary hidden as per user request --}}
                @if(false)
                <div class="space-y-3">
                    <div class="flex items-center justify-between w-full text-left">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-1 h-4 bg-[#8B4513] rounded-full"></span>
                            Bộ lọc hiển thị
                        </h3>
                    </div>
    
                    <div class="space-y-3 pl-3 border-l-2 border-gray-100 ml-1.5">
                        <!-- Status Filter -->
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Trạng thái</label>
                            <div class="flex flex-col gap-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="showAlive"
                                            class="peer sr-only">
                                        <div
                                            class="w-4 h-4 border-2 border-green-500 rounded bg-white peer-checked:bg-green-500 transition-colors">
                                        </div>
                                        <svg class="absolute top-0.5 left-0.5 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 group-hover:text-green-600 transition-colors">Còn sống</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="showDeceased"
                                            class="peer sr-only">
                                        <div
                                            class="w-4 h-4 border-2 border-gray-400 rounded bg-white peer-checked:bg-gray-400 transition-colors">
                                        </div>
                                        <svg class="absolute top-0.5 left-0.5 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 group-hover:text-gray-800 transition-colors">Đã mất</span>
                                </label>
                            </div>
                        </div>
    
                        <!-- Gender Filter -->
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Giới tính</label>
                            <div class="flex flex-col gap-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="showMale"
                                            class="peer sr-only">
                                        <div
                                            class="w-4 h-4 border-2 border-blue-500 rounded bg-white peer-checked:bg-blue-500 transition-colors">
                                        </div>
                                        <svg class="absolute top-0.5 left-0.5 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 group-hover:text-blue-600 transition-colors">Nam</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="showFemale"
                                            class="peer sr-only">
                                        <div
                                            class="w-4 h-4 border-2 border-pink-500 rounded bg-white peer-checked:bg-pink-500 transition-colors">
                                        </div>
                                        <svg class="absolute top-0.5 left-0.5 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 group-hover:text-pink-600 transition-colors">Nữ</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
    
                <!-- Advanced Display Settings -->
                <div class="pt-4 border-t border-gray-100">
                     <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <span class="w-1 h-4 bg-gray-400 rounded-full"></span>
                        Hiển thị
                    </h3>
                    <div class="space-y-2 pl-3">
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-medium text-gray-600">Hiển thị ngày tháng</span>
                            <div class="relative inline-block w-8 h-4 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="showDates" class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-4 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-medium text-gray-600">Hiển thị Chức vụ/Học vị</span>
                            <div class="relative inline-block w-8 h-4 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="showTitles" class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-4 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-medium text-gray-600">Hiển thị Vợ/Chồng</span>
                             <div class="relative inline-block w-8 h-4 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="showSpouses" class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-4 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Tiêu đề Gia Phả</label>
                            <input type="text" wire:model.live.debounce.500ms="treeTitle"
                                class="w-full text-sm border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring-[#C41E3A] placeholder-gray-300 transition-colors bg-gray-50 focus:bg-white"
                                placeholder="Nhập tiêu đề...">
                        </div>
                    </div>
                </div>
    
            {{-- TAB: STATS --}}
            @elseif ($activeTab === 'stats')
                <div class="space-y-4">
                     <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#C41E3A] rounded-full"></span>
                        Thống kê dòng họ
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-center">
                            <span class="block text-2xl font-bold text-gray-800">{{ $stats['total_members'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Thành viên</span>
                        </div>
                         <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                            <span class="block text-2xl font-bold text-green-600">{{ $stats['living_members'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Còn sống</span>
                        </div>
                         <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-center">
                            <span class="block text-2xl font-bold text-gray-400">{{ $stats['deceased_members'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Đã mất</span>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-center">
                            <span class="block text-2xl font-bold text-blue-600">{{ $stats['male_members'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Nam</span>
                        </div>
                        <div class="bg-pink-50 p-3 rounded-lg border border-pink-100 text-center">
                            <span class="block text-2xl font-bold text-pink-500">{{ $stats['female_members'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Nữ</span>
                        </div>
                        <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 text-center">
                            <span class="block text-2xl font-bold text-amber-600">{{ $stats['total_generations'] ?? 0 }}</span>
                            <span class="text-[10px] uppercase text-gray-500 font-bold">Thế hệ</span>
                        </div>
                    </div>
                </div>
            
            {{-- TAB: LIST --}}
            @elseif ($activeTab === 'list')
               <div class="space-y-4">
                     <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <span class="w-1 h-4 bg-purple-600 rounded-full"></span>
                        Danh sách thành viên
                    </h3>
                    
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="listSearch" placeholder="Tìm kiếm..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs focus:ring-primary-500 focus:border-primary-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
    
                    <div class="space-y-2">
                        @forelse($members as $member)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-100 group"
                                wire:click="selectPerson({{ $member->id }})">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center shrink-0 overflow-hidden border border-gray-300">
                                         @if($member->avatar_url)
                                            <img src="{{ $member->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs font-bold text-gray-500">{{ substr($member->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-800 group-hover:text-primary-600">{{ $member->name }}</div>
                                        @if($member->birth_year)
                                            <div class="text-[10px] text-gray-500">{{ $member->birth_year }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-[10px] px-2 py-0.5 rounded-full {{ $member->gender === 'male' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                    {{ $member->gender === 'male' ? 'Nam' : 'Nữ' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-xs italic">
                                Không tìm thấy thành viên nào.
                            </div>
                        @endforelse
    
                        @if(method_exists($members, 'links'))
                            <div class="pt-2">
                                {{ $members->links(data: ['scrollTo' => false]) }}
                            </div>
                        @endif
                    </div>
               </div>
            @endif
        </div>
    
        {{-- Footer Branding --}}
        <div class="hidden lg:block p-3 border-t border-gray-100 bg-gray-50 text-center">
            <p class="text-[10px] text-gray-400">© 2026 Hệ Thống Gia Phả Số</p>
        </div>
    </div>

    <!-- Mobile Menu Drawer removed -->
</div>
</div>
