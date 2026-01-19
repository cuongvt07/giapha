<div wire:ignore.self
    class="h-full flex flex-col bg-white shadow-xl border-r border-gray-200 transition-all duration-300 ease-in-out z-[100]
           fixed inset-y-0 left-0"
    style="touch-action: auto;" x-data="{
        collapsed: $persist(true).as('sidebar-collapsed'),
        openFilters: true,
        openStats: true,
        toggle() {
            console.log('Sidebar toggled, collapsed was:', this.collapsed);
            this.collapsed = !this.collapsed;
            console.log('Sidebar collapsed is now:', this.collapsed);
            console.log('Element classes will be:', this.collapsed ? '-translate-x-full' : 'translate-x-0 w-80');
        }
    }" @toggle-sidebar.window="toggle()"
    :style="collapsed ? 'transform: translateX(-100%); width: 0;' : 'transform: translateX(0); width: 20rem;'">

    {{-- Mobile overlay backdrop (now inside x-data scope) --}}
    <div class="lg:hidden fixed inset-0 bg-black/50 z-30 transition-opacity -ml-80" x-show="!collapsed"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="collapsed = true"
        style="display: none;">
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-[#C41E3A] text-white">
        <div class="flex items-center gap-2 overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <h2 class="font-bold text-lg font-serif tracking-wide whitespace-nowrap transition-opacity duration-200"
                x-show="!collapsed" x-transition>Gia Phả Việt</h2>
        </div>
        <button @click="toggle()" class="p-1.5 hover:bg-white/20 rounded-lg transition-colors flex-shrink-0"
            :title="collapsed ? 'Mở rộng' : 'Thu gọn'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    :d="collapsed ? 'M13 5l7 7-7 7M5 5l7 7-7 7' : 'M11 19l-7-7 7-7m8 14l-7-7 7-7'" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu (Compact) - Hidden when collapsed -->
    <div class="flex border-b border-gray-100 bg-gray-50 transition-opacity duration-200" x-show="!collapsed"
        x-transition>
        <button class="flex-1 py-3 text-xs font-bold text-[#C41E3A] border-b-2 border-[#C41E3A] bg-white">
            <span class="block mb-1 text-lg">🌳</span>
            Cây Gia Phả
        </button>
        <button
            class="flex-1 py-3 text-xs font-bold text-gray-500 hover:bg-white hover:text-gray-700 transition-colors">
            <span class="block mb-1 text-lg">📊</span>
            Thống Kê
        </button>
        <button
            class="flex-1 py-3 text-xs font-bold text-gray-500 hover:bg-white hover:text-gray-700 transition-colors">
            <span class="block mb-1 text-lg">📋</span>
            Danh Sách
        </button>
    </div>

    <!-- Scrollable Content - Hidden when collapsed -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6 custom-scrollbar transition-opacity duration-200"
        x-show="!collapsed" x-transition>

        <!-- SEARCH SEARCH PANEL -->
        <div class="relative z-50">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tìm kiếm nhanh</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full pl-9 pr-4 py-2 text-sm border-gray-200 rounded-lg focus:border-[#C41E3A] focus:ring-[#C41E3A] placeholder-gray-400 bg-gray-50 focus:bg-white transition-all shadow-sm"
                    placeholder="Nhập tên thành viên...">

                <!-- Search Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-2.5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" wire:loading.remove wire:target="search">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>

                <!-- Loading Spinner -->
                <svg wire:loading wire:target="search"
                    class="animate-spin h-4 w-4 text-[#C41E3A] absolute left-3 top-2.5"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <!-- Search Results Dropdown -->
            @if (strlen($search) >= 2)
                <div
                    class="absolute w-full bg-white mt-1 rounded-lg shadow-xl border border-gray-100 overflow-hidden max-h-60 overflow-y-auto">
                    @if (count($searchResults) > 0)
                        @foreach ($searchResults as $result)
                            <div class="p-2 hover:bg-red-50 cursor-pointer border-b border-gray-50 last:border-0 flex items-center gap-3 transition-colors"
                                wire:click="selectPerson({{ $result->id }})">
                                <!-- Avatar Tiny -->
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 overflow-hidden">
                                    @if ($result->avatar_url)
                                        <img src="{{ $result->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        <span
                                            class="w-full h-full flex items-center justify-center text-[10px] text-gray-500 font-bold">{{ substr($result->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $result->name }}</p>
                                    <p class="text-[10px] text-gray-500">
                                        {{ $result->birth_year ?? '?' }} - {{ $result->death_year ?? '?' }}
                                        @if ($result->nickname)
                                            • {{ $result->nickname }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-3 text-center text-xs text-gray-500">
                            Không tìm thấy thành viên nào.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- STATISTICS PANEL -->
        <div class="space-y-3">
            <button @click="openStats = !openStats" class="flex items-center justify-between w-full text-left">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1 h-4 bg-[#FFD700] rounded-full"></span>
                    Tổng Quan
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200"
                    :class="openStats ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="openStats" x-collapse class="grid grid-cols-2 gap-3">
                <!-- Total Members -->
                <div
                    class="col-span-2 bg-gradient-to-br from-[#C41E3A] via-[#A01830] to-[#8B1428] rounded-xl p-5 text-white shadow-xl relative overflow-hidden hover:shadow-2xl transition-all duration-300">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <p class="text-sm text-red-100 font-bold uppercase mb-2 tracking-wide">Tổng thành viên</p>
                    <p class="text-5xl font-black font-serif mb-3 drop-shadow-lg">{{ $stats['total_members'] }}</p>
                    <div class="flex items-center gap-2">
                        <span class="bg-white/25 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold">
                            {{ $stats['total_generations'] }} thế hệ
                        </span>
                        <span
                            class="bg-white/25 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Cập nhật
                        </span>
                    </div>
                </div>

                <!-- Living/Deceased -->
                <div
                    class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-4 border-2 border-green-200 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-green-700 font-bold uppercase tracking-wide">Còn sống</p>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-4xl font-black text-green-700 mb-1">{{ $stats['living_members'] }}</p>
                    <p class="text-xs text-green-600 font-medium">
                        {{ $stats['total_members'] > 0 ? round(($stats['living_members'] / $stats['total_members']) * 100) : 0 }}%
                        tổng số
                    </p>
                </div>
                <div
                    class="bg-gradient-to-br from-gray-50 to-slate-100 rounded-xl p-4 border-2 border-gray-300 shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-600 font-bold uppercase tracking-wide">Đã mất</p>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-4xl font-black text-gray-700 mb-1">{{ $stats['deceased_members'] }}</p>
                    <p class="text-xs text-gray-600 font-medium">
                        {{ $stats['total_members'] > 0 ? round(($stats['deceased_members'] / $stats['total_members']) * 100) : 0 }}%
                        tổng số
                    </p>
                </div>

                <!-- Gender Ratio -->
                <div
                    class="col-span-2 bg-gradient-to-br from-amber-50 via-yellow-50 to-orange-50 border-2 border-amber-200 rounded-xl p-4 shadow-md">
                    <p class="text-xs text-amber-800 font-bold uppercase mb-3 tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        Tỉ lệ Nam / Nữ
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-blue-700 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Nam
                                </span>
                                <span class="text-gray-700 font-bold">{{ $stats['male_members'] }}</span>
                            </div>
                            <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500"
                                    style="width: {{ $stats['total_members'] > 0 ? ($stats['male_members'] / $stats['total_members']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-pink-700 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Nữ
                                </span>
                                <span class="text-gray-700 font-bold">{{ $stats['female_members'] }}</span>
                            </div>
                            <div class="h-2.5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full bg-gradient-to-r from-pink-500 to-pink-600 rounded-full transition-all duration-500"
                                    style="width: {{ $stats['total_members'] > 0 ? ($stats['female_members'] / $stats['total_members']) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Separator -->
        <div class="flex items-center gap-2 px-4">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
        </div>

        <!-- FILTERS PANEL -->
        <div class="space-y-3">
            <button @click="openFilters = !openFilters" class="flex items-center justify-between w-full text-left">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1 h-4 bg-[#8B4513] rounded-full"></span>
                    Bộ lọc hiển thị
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-gray-400 transition-transform duration-200"
                    :class="openFilters ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="openFilters" x-collapse class="space-y-3">
                <!-- Status Filter -->
                <div>
                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Trạng thái</label>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model.live="showAlive"
                                class="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800">Còn sống</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model.live="showDeceased"
                                class="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800">Đã mất</span>
                        </label>
                    </div>
                </div>

                <!-- Gender Filter -->
                <div>
                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Giới tính</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model.live="showMale"
                                class="rounded border-gray-300 text-blue-500 focus:ring-blue-500 transition-colors">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800">Nam</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" wire:model.live="showFemale"
                                class="rounded border-gray-300 text-pink-500 focus:ring-pink-500 transition-colors">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800">Nữ</span>
                        </label>
                    </div>
                </div>

                <!-- Branch Filter -->
                <div>
                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Thuộc Chi / Phái</label>
                    <select
                        class="w-full text-sm border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring-[#C41E3A]">
                        <option value="">Tất cả các chi</option>
                        <!-- Options will be populated -->
                    </select>
                </div>

                <!-- Actions -->
                <div class="pt-2">
                    <button
                        class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium text-xs rounded transition-colors">
                        Đặt lại bộ lọc
                    </button>
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        <!-- DISPLAY SETTINGS PANEL -->
        <div class="space-y-3" x-data="{ openSettings: true }">
            <button @click="openSettings = !openSettings" class="flex items-center justify-between w-full text-left">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-1 h-4 bg-gray-400 rounded-full"></span>
                    Cấu hình hiển thị
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-gray-400 transition-transform duration-200"
                    :class="openSettings ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="openSettings" x-collapse class="space-y-3">
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" wire:model.live="showDates"
                            class="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors">
                        <span class="text-sm text-gray-600 group-hover:text-gray-800">Hiển thị Năm Sinh/Mất</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" wire:model.live="showTitles"
                            class="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors">
                        <span class="text-sm text-gray-600 group-hover:text-gray-800">Hiển thị Chức danh</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" wire:model.live="showSpouses"
                            class="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors">
                        <span class="text-sm text-gray-600 group-hover:text-gray-800">Hiển thị Vợ/Chồng</span>
                    </label>

                    <hr class="border-gray-100 my-1">

                    <div>
                        <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Tiêu đề Gia Phả</label>
                        <input type="text" wire:model.live.debounce.500ms="treeTitle"
                            class="w-full text-sm border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring-[#C41E3A] placeholder-gray-300 transition-colors bg-gray-50 focus:bg-white"
                            placeholder="Nhập tiêu đề...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Branding - Hidden when collapsed or on mobile --}}
    <div class="hidden lg:block p-3 border-t border-gray-100 bg-gray-50 text-center transition-opacity duration-200"
        x-show="!collapsed" x-transition>
        <p class="text-[10px] text-gray-400">© 2026 Hệ Thống Gia Phả Số</p>
    </div>

    {{-- Collapsed Mode - Icon-Only Quick Actions (Desktop only) --}}
    <div class="hidden lg:flex flex-1 flex-col items-center justify-center gap-6 py-6" x-show="collapsed"
        x-transition>
        {{-- Expand Button --}}
        <button @click="toggle()" class="p-3 hover:bg-gray-100 rounded-lg transition-colors group"
            title="Mở rộng sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 group-hover:text-[#C41E3A]"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>

        {{-- Quick Stats Icons --}}
        <div class="flex flex-col items-center gap-4">
            <div class="text-center" title="Tổng thành viên">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C41E3A] mx-auto mb-1"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                <p class="text-xs font-bold text-gray-700">{{ $stats['total_members'] }}</p>
            </div>
        </div>
    </div>
</div>
