{{-- Mobile Menu Drawer --}}
<div class="h-full flex flex-col bg-white">
    {{-- Header --}}
    <div class="flex-shrink-0 p-4 bg-[#C41E3A] text-white">
        <div class="flex items-center justify-between gap-3">
             <div class="flex-1 overflow-hidden">
                <marquee scrollamount="4" class="font-bold font-serif text-lg tracking-wide whitespace-nowrap">
                    {{ $filters['treeTitle'] ?? 'Gia Phả Đại Tộc' }}
                </marquee>
            </div>
            <button wire:click="closeMenu" class="p-1 hover:bg-white/20 rounded flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex border-b border-gray-100 bg-gray-50 flex-shrink-0">
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

    {{-- Scrollable Content --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-6 custom-scrollbar bg-white">

        {{-- TAB: TREE --}}
        @if ($activeTab === 'tree')
            <div class="space-y-4">
                 <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-1 h-4 bg-gray-400 rounded-full"></span>
                    Tùy chọn hiển thị
                </h3>
                
                <div class="space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm font-medium text-gray-700">Hiển thị ngày tháng</span>
                            <div class="relative inline-block w-9 h-5 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="filters.showDates" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm font-medium text-gray-700">Hiển thị Chức vụ</span>
                            <div class="relative inline-block w-9 h-5 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="filters.showTitles" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm font-medium text-gray-700">Hiển thị Vợ/Chồng</span>
                             <div class="relative inline-block w-9 h-5 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" wire:model.live="filters.showSpouses" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-400"/>
                                <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer checked:bg-green-400"></label>
                            </div>
                        </label>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200">
                        <label class="text-xs uppercase font-bold text-gray-500 mb-1.5 block">Tiêu đề Gia Phả</label>
                        <input type="text" wire:model.live.debounce.500ms="filters.treeTitle"
                            class="w-full text-sm border-gray-200 rounded-lg focus:border-[#C41E3A] focus:ring-[#C41E3A] placeholder-gray-400"
                            placeholder="Nhập tiêu đề...">
                    </div>
                </div>
                
                 <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-2 pt-2">
                    <span class="w-1 h-4 bg-[#8B4513] rounded-full"></span>
                    Bộ lọc thành viên
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-3 bg-white border border-gray-200 rounded-lg shadow-sm active:scale-95 transition-transform">
                        <input type="checkbox" wire:model.live="filters.showAlive" class="rounded text-green-600 focus:ring-green-600 w-4 h-4 border-gray-300">
                        <span class="text-xs font-bold text-gray-700">Còn sống</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 bg-white border border-gray-200 rounded-lg shadow-sm active:scale-95 transition-transform">
                        <input type="checkbox" wire:model.live="filters.showDeceased" class="rounded text-gray-500 focus:ring-gray-500 w-4 h-4 border-gray-300">
                        <span class="text-xs font-bold text-gray-700">Đã mất</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg shadow-sm active:scale-95 transition-transform">
                        <input type="checkbox" wire:model.live="filters.showMale" class="rounded text-blue-600 focus:ring-blue-600 w-4 h-4 border-gray-300">
                        <span class="text-xs font-bold text-gray-700">Nam</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 bg-pink-50 border border-pink-100 rounded-lg shadow-sm active:scale-95 transition-transform">
                        <input type="checkbox" wire:model.live="filters.showFemale" class="rounded text-pink-600 focus:ring-pink-600 w-4 h-4 border-gray-300">
                        <span class="text-xs font-bold text-gray-700">Nữ</span>
                    </label>
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
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-center shadow-sm">
                        <span class="block text-3xl font-black text-gray-800">{{ $stats['total_members'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold tracking-wide">Tổng thành viên</span>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 text-center shadow-sm">
                        <span class="block text-3xl font-black text-amber-600">{{ $stats['total_generations'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold tracking-wide">Số thế hệ</span>
                    </div>
                     <div class="bg-green-50 p-3 rounded-xl border border-green-200 text-center shadow-sm">
                        <span class="block text-xl font-bold text-green-600">{{ $stats['living_members'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold">Còn sống</span>
                    </div>
                     <div class="bg-gray-100 p-3 rounded-xl border border-gray-200 text-center shadow-sm">
                        <span class="block text-xl font-bold text-gray-500">{{ $stats['deceased_members'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-400 font-bold">Đã mất</span>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-200 text-center shadow-sm">
                        <span class="block text-xl font-bold text-blue-600">{{ $stats['male_members'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold">Nam</span>
                    </div>
                    <div class="bg-pink-50 p-3 rounded-xl border border-pink-200 text-center shadow-sm">
                        <span class="block text-xl font-bold text-pink-500">{{ $stats['female_members'] ?? 0 }}</span>
                        <span class="text-[10px] uppercase text-gray-500 font-bold">Nữ</span>
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
                
                <div class="relative sticky top-0 z-10">
                    <input type="text" wire:model.live.debounce.300ms="listSearch" placeholder="Tìm kiếm..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A] shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="space-y-2 pb-20"> {{-- Padding bottom for footer --}}
                    @forelse($members as $member)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-colors border border-gray-100 shadow-sm active:scale-[0.99]"
                            wire:click="selectPerson({{ $member->id }}); closeMenu()">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center shrink-0 overflow-hidden border border-gray-300">
                                     @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-bold text-gray-500">{{ substr($member->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $member->name }}</div>
                                    @if($member->birth_year)
                                        <div class="text-xs text-gray-500">{{ $member->birth_year }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-[10px] px-2 py-0.5 rounded-full {{ $member->gender === 'male' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }} font-bold">
                                {{ $member->gender === 'male' ? 'Nam' : 'Nữ' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 flex flex-col items-center">
                            <svg class="w-12 h-12 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                            </svg>
                            <span class="text-sm">Không tìm thấy thành viên nào.</span>
                        </div>
                    @endforelse

                    @if(method_exists($members, 'links'))
                        <div class="pt-4">
                            {{ $members->links(data: ['scrollTo' => false]) }}
                        </div>
                    @endif
                </div>
           </div>
        @endif
    </div>

    {{-- Footer Actions --}}
    <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
        <button wire:click="openAddModal"
            class="w-full py-3 bg-[#C41E3A] text-white font-bold rounded-xl active:bg-[#a01830] shadow-lg flex items-center justify-center gap-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm thành viên mới
        </button>
    </div>
</div>
