<div x-data="{ detailsOpen: @entangle('detailsOpen') }">
    {{-- Responsive Sidebar Right: Full screen on mobile, Fixed Sidebar on Desktop --}}
    {{-- Responsive Sidebar Right: Bottom Sheet on Mobile, Fixed Sidebar on Desktop --}}
    
    {{-- Mobile Backdrop --}}
    <div x-show="detailsOpen" wire:click="$set('detailsOpen', false)"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm">
    </div>

    <div wire:ignore.self
        class="pointer-events-auto z-50 font-sans transition-transform duration-300 ease-in-out
               fixed inset-y-0 right-0 w-full md:w-96
               h-full
               bg-white shadow-2xl 
               border-l border-gray-200"
        :class="detailsOpen ? 'translate-x-0' : 'translate-x-full'">

        {{-- Toggle Button (Desktop only) --}}
        <button wire:click="toggle"
            class="hidden lg:flex absolute top-3 -left-8 w-8 h-8 bg-white border border-gray-100 shadow-sm rounded-l-md items-center justify-center text-gray-400 hover:text-primary-600 focus:outline-none transition-all duration-300 z-50 group hover:w-10 overflow-visible"
            title="Ẩn/Hiện Menu">
            <template x-if="detailsOpen">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </template>
            <template x-if="!detailsOpen">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </template>
        </button>

        {{-- Main Card Content --}}
        <div class="w-full h-full overflow-hidden flex flex-col bg-white">

            <div class="flex flex-col w-full h-full overflow-hidden">

                <!-- VIEW 1: TOOLS (Default) -->
                @if ($viewState === 'tools')
                    <div class="hidden md:flex flex-col w-full px-3 py-2 overflow-y-auto h-full">
                        <!-- Compact Header -->
                        <div
                            class="flex items-center justify-between p-3 bg-[#C41E3A] text-white rounded-t-xl shrink-0 shadow-sm">
                            <h3 class="font-semibold text-sm flex items-center gap-1.5 font-serif tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-300" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                TRA CỨU
                            </h3>
                            <span
                                class="text-xs text-red-100 font-mono">{{ count($results) > 0 ? count($results) : \App\Models\Person::count() }}
                                thành viên</span>
                        </div>

                        <!-- Search Input -->
                        <!-- Search Input (Manual Trigger) -->
                        <div class="relative mb-2 flex items-center gap-1 mt-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" wire:model="search" wire:keydown.enter="performSearch"
                                    class="w-full pl-9 pr-8 py-2 text-sm border-gray-200 rounded-lg focus:border-[#C41E3A] focus:ring-[#C41E3A] placeholder-gray-400 bg-gray-50 focus:bg-white transition-all shadow-sm"
                                    placeholder="Nhập tên thành viên...">
                                
                                <!-- Clear Button -->
                                @if($search)
                                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            
                            <button wire:click="performSearch" 
                                class="bg-[#C41E3A] hover:bg-[#a01830] text-white p-2 rounded-lg shadow-md transition-all flex-shrink-0"
                                title="Tìm kiếm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Results List -->
                        @if (count($results) > 0)
                            <div class="flex-1 overflow-y-auto pr-1 space-y-1">
                                @foreach ($results as $res)
                                    <div class="group cursor-pointer p-2 hover:bg-primary-50 rounded-md flex items-center gap-3 transition-colors border border-transparent hover:border-primary-100"
                                        wire:click="selectResult({{ $res->id }})">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-200 shrink-0 overflow-hidden border border-white shadow-sm group-hover:border-primary-200">
                                            @if ($res->avatar_url)
                                                <img src="{{ $res->avatar_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 text-[10px] font-bold">
                                                    {{ substr($res->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div
                                                class="text-sm font-medium text-gray-700 truncate group-hover:text-primary-700">
                                                {{ $res->full_name }}</div>
                                            <div class="text-[10px] text-gray-400 flex items-center gap-1">
                                                <span>{{ $res->birth_year }}</span>
                                                @if (!$res->is_alive)
                                                    <span class="text-gray-300">•</span> <span class="text-gray-400">Đã
                                                        mất</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State / Quick Stats -->
                            <div class="mt-4 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-400 mb-4">Nhập tên để tìm kiếm</p>

                                <button wire:click="startAdding"
                                    class="px-4 py-2 bg-primary-50 text-primary-600 text-xs font-bold uppercase rounded-lg hover:bg-primary-100 transition-colors flex items-center gap-2 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Thêm người mới
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- VIEW 2: DETAILS -->
                @elseif ($viewState === 'details' && $person)
                    <div class="flex flex-col h-full bg-white relative group/details">
                        <!-- Unified Header for View/Details -->
                        <div class="flex items-center gap-3 p-4 border-b border-gray-100 bg-white shadow-sm z-10 shrink-0 sticky top-0">
                            {{-- Back Button (Mobile: Close Sheet, Desktop: Close/Back) --}}
                            <button wire:click="cancel" class="p-3 md:p-2 -ml-1 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0" title="Quay lại">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            {{-- Avatar --}}
                            <div class="shrink-0 relative">
                                <div class="w-14 h-14 md:w-12 md:h-12 rounded-full border border-gray-200 p-0.5 bg-white shadow-sm overflow-hidden">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-gray-50 flex items-center justify-center">
                                        @if ($person->avatar_url)
                                            <img src="{{ $person->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="h-8 w-8 md:h-6 md:w-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <h2 class="text-xl md:text-base font-bold text-gray-800 leading-tight break-words">
                                    {{ $person->name }}
                                </h2>
                                <div class="text-sm md:text-[10px] text-gray-500 mt-1 md:mt-0.5 flex flex-col items-start gap-1.5 overflow-visible">
                                    <span class="{{ $person->gender == 'male' ? 'text-blue-600 bg-blue-50' : 'text-pink-600 bg-pink-50' }} px-2 py-0.5 rounded-md font-bold uppercase text-[10px] leading-none shrink-0 w-fit">
                                        {{ $person->gender == 'male' ? 'Nam' : 'Nữ' }}
                                    </span>
                                    <span class="font-bold text-gray-700 leading-tight whitespace-normal break-words">
                                        {{ $person->birth_year ?? '?' }} - {{ $person->death_year ?? ($person->is_alive ? 'Nay' : '?') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1 shrink-0">
                                <button wire:click="startEditing" class="p-3 md:p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Sửa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button wire:click="deletePerson" onclick="confirm('Xóa thành viên này?') || event.stopImmediatePropagation()" class="p-3 md:p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors" title="Xóa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 px-6 py-2 border-b border-gray-100 overflow-x-auto scrollbar-hide shrink-0 w-full mb-1">
                            <button wire:click="setTab('info')"
                                class="snap-start flex-shrink-0 px-4 py-2 md:py-1.5 text-sm md:text-xs font-bold rounded-full transition-all whitespace-nowrap {{ $activeTab === 'info' ? 'bg-primary-100 text-primary-700 shadow-sm ring-1 ring-primary-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Thông tin
                            </button>
                            <button wire:click="setTab('bio')"
                                class="snap-start flex-shrink-0 px-4 py-2 md:py-1.5 text-sm md:text-xs font-bold rounded-full transition-all whitespace-nowrap {{ $activeTab === 'bio' ? 'bg-primary-100 text-primary-700 shadow-sm ring-1 ring-primary-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Tiểu sử
                            </button>
                            <button wire:click="setTab('burial')"
                                class="snap-start flex-shrink-0 px-4 py-2 md:py-1.5 text-sm md:text-xs font-bold rounded-full transition-all whitespace-nowrap {{ $activeTab === 'burial' ? 'bg-primary-100 text-primary-700 shadow-sm ring-1 ring-primary-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Mộ phần
                            </button>
                            <button wire:click="setTab('achievements')"
                                class="snap-start flex-shrink-0 px-4 py-2 md:py-1.5 text-sm md:text-xs font-bold rounded-full transition-all whitespace-nowrap {{ $activeTab === 'achievements' ? 'bg-primary-100 text-primary-700 shadow-sm ring-1 ring-primary-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Thành tích
                            </button>
                        </div>

                        <!-- TAB CONTENT -->
                        <div class="flex-1 overflow-y-auto px-4 pb-4 pt-3 space-y-4">

                            <!-- TAB: INFO -->
                            @if ($activeTab === 'info')
                                <div class="space-y-4">
                                    <!-- Personal Details -->
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3">
                                        @if ($person->nickname)
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tên tự / Tên húy</span>
                                                <span class="text-base font-semibold text-gray-900 mt-0.5">{{ $person->nickname }}</span>
                                            </div>
                                        @endif
                                        @if ($person->title)
                                            <div class="flex flex-col border-t border-gray-50 pt-2">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Chức vụ / Học vị</span>
                                                <span class="text-base font-semibold text-gray-900 mt-0.5">{{ $person->title }}</span>
                                            </div>
                                        @endif
                                        @if ($person->occupation)
                                            <div class="flex flex-col border-t border-gray-50 pt-2">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Nghề nghiệp</span>
                                                <span class="text-base font-medium text-gray-800 mt-0.5">{{ $person->occupation }}</span>
                                            </div>
                                        @endif
                                        @if ($person->hometown)
                                            <div class="flex flex-col border-t border-gray-50 pt-2">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Quê quán</span>
                                                <span class="text-base font-medium text-gray-800 mt-0.5">{{ $person->hometown }}</span>
                                            </div>
                                        @endif
                                        @if ($person->place_of_birth)
                                            <div class="flex flex-col border-t border-gray-50 pt-2">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Nơi sinh</span>
                                                <span class="text-base font-medium text-gray-800 mt-0.5">{{ $person->place_of_birth }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Contact Info -->
                                    @if ($person->phone || $person->email || $person->facebook_url || $person->address)
                                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 space-y-3">
                                            <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-2">Thông tin liên hệ</h4>
                                            
                                            @if ($person->address)
                                                <div class="flex gap-3 items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <span class="block text-xs font-bold text-gray-500 uppercase">Địa chỉ</span>
                                                        <span class="text-sm font-medium text-gray-800">{{ $person->address }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($person->phone)
                                                <div class="flex gap-3 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <span class="block text-xs font-bold text-gray-500 uppercase">Điện thoại</span>
                                                        <a href="tel:{{ $person->phone }}" class="text-sm font-bold text-blue-700 hover:underline">{{ $person->phone }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($person->email)
                                                <div class="flex gap-3 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                     <div class="flex-1">
                                                        <span class="block text-xs font-bold text-gray-500 uppercase">Email</span>
                                                        <a href="mailto:{{ $person->email }}" class="text-sm font-medium text-gray-800 hover:text-blue-700 hover:underline break-all">{{ $person->email }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($person->facebook_url)
                                                <div class="flex gap-3 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <span class="block text-xs font-bold text-gray-500 uppercase">Facebook</span>
                                                        <a href="{{ $person->facebook_url }}" target="_blank" class="text-sm font-medium text-blue-700 hover:underline break-all">Xem trang cá nhân</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Family Connections -->
                                    <div class="space-y-4 pt-2">
                                        <!-- Parents -->
                                        @if ($person->father || $person->mother)
                                            <div class="flex items-start gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-gray-500 font-bold text-xs mt-0.5">
                                                    P
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Cha Mẹ</p>
                                                    <div class="space-y-1">
                                                        @if ($person->father)
                                                            <div class="text-sm font-bold text-gray-800">{{ $person->father->name }}</div>
                                                        @endif
                                                        @if ($person->mother)
                                                            <div class="text-sm font-medium text-gray-600">{{ $person->mother->name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Spouses -->
                                        @php
                                            $marriages = $person->gender == 'male' ? $person->marriagesAsHusband : $person->marriagesAsWife;
                                        @endphp
                                        @if ($marriages->count() > 0)
                                            <div class="flex items-start gap-4 p-3 bg-pink-50/50 rounded-xl border border-pink-100">
                                                <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center shrink-0 text-pink-500 font-bold text-xs mt-0.5">
                                                    H
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs uppercase font-bold text-pink-400 tracking-wider mb-1">Vợ / Chồng</p>
                                                    <div class="space-y-2">
                                                        @foreach ($marriages as $marriage)
                                                            @php
                                                                $spouse = $person->gender == 'male' ? $marriage->wife : $marriage->husband;
                                                            @endphp
                                                            @if ($spouse)
                                                                <div class="flex items-center justify-between border-b border-pink-100 last:border-0 pb-1 last:pb-0 cursor-pointer"
                                                                    wire:click="loadPerson({{ $spouse->id }})">
                                                                    <div class="flex items-center gap-2">
                                                                         <span class="text-sm font-bold text-gray-800 hover:text-pink-600 transition-colors">{{ $spouse->name }}</span>
                                                                    </div>
                                                                    @if ($marriage->marriage_type)
                                                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-white text-pink-600 border border-pink-200 font-bold shadow-sm">
                                                                            {{ $marriage->marriage_type == 'chinh_thuc' ? 'Vợ cả' : ($marriage->marriage_type == 'thu_that' ? 'Vợ lẽ' : 'Kế thất') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Children -->
                                        <div class="flex items-start gap-4 p-3 bg-blue-50/50 rounded-xl border border-blue-100">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 text-blue-500 font-bold text-xs mt-0.5">
                                                C
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs uppercase font-bold text-blue-400 tracking-wider">
                                                        Con cái ({{ $person->children->count() }})
                                                    </p>
                                                    <button wire:click="startAdding({ parentId: {{ $person->id }} })" 
                                                        class="text-[10px] font-bold text-blue-600 bg-white border border-blue-200 px-2 py-1 rounded-full shadow-sm hover:bg-blue-50">
                                                        + Thêm
                                                    </button>
                                                </div>
                                                
                                                @if ($person->children->count() > 0)
                                                    <div class="space-y-0.5">
                                                        @foreach ($person->children as $child)
                                                            <div class="flex items-center justify-between py-2 px-2 rounded-lg hover:bg-white hover:shadow-sm transition-all cursor-pointer group"
                                                                wire:click="loadPerson({{ $child->id }})">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">{{ $child->name }}</span>
                                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 font-bold">{{ $child->gender == 'male' ? 'Nam' : 'Nữ'}}</span>
                                                                </div>
                                                                <span class="text-xs font-medium text-gray-400">{{ $child->birth_year }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-center text-gray-400 italic py-2">Chưa cập nhật thông tin con cái</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB: BIO -->
                            <!-- TAB: BIO -->
                            @elseif ($activeTab === 'bio')
                                <div class="space-y-5 px-1 py-1">
                                    @if ($person->biographyEntries && $person->biographyEntries->count() > 0)
                                        <div class="relative pl-4 border-l-2 border-amber-100 space-y-6">
                                            @foreach ($person->biographyEntries as $entry)
                                                <div class="relative group">
                                                    <!-- Dot -->
                                                    <div class="absolute -left-[21px] top-2 w-3 h-3 rounded-full bg-amber-400 border-2 border-white shadow-sm group-hover:scale-125 transition-transform"></div>
                                                    
                                                    <div class="flex flex-col bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                                        @if($entry->time_period)
                                                            <span class="inline-block self-start px-2 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-bold leading-none mb-1.5">
                                                                {{ $entry->time_period }}
                                                            </span>
                                                        @endif
                                                        <div class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                                                            {{ $entry->content }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif ($person->biography)
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm text-sm text-gray-800 leading-relaxed font-serif">
                                            {!! nl2br(e($person->biography)) !!}
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center py-12 text-gray-400 italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                            Chưa có tiểu sử
                                        </div>
                                    @endif
                                </div>

                                <!-- TAB: BURIAL -->
                            @elseif ($activeTab === 'burial')
                                <div class="space-y-4">
                                    @if ($person->burialInfo)
                                        <div class="bg-stone-50 p-4 rounded-xl border border-stone-200">
                                            <div class="flex items-start gap-3 mb-4">
                                                <div class="w-10 h-10 rounded-full bg-stone-100 flex items-center justify-center shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="block text-xs font-bold text-stone-500 uppercase">Nơi an táng</span>
                                                    <span class="text-base font-bold text-stone-900 leading-tight block mt-1">
                                                        {{ $person->burialInfo->burial_place ?? 'Chưa cập nhật địa điểm' }}
                                                    </span>
                                                </div>
                                            </div>

                                            @if ($person->burialInfo->burial_date)
                                                <div class="flex items-center gap-3 mb-4 px-3 py-2 bg-white rounded-lg border border-stone-100">
                                                    <svg class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                    <div>
                                                        <span class="block text-[10px] font-bold text-stone-500 uppercase">Ngày an táng</span>
                                                        <span class="text-sm font-semibold text-stone-800">
                                                            {{ \Carbon\Carbon::parse($person->burialInfo->burial_date)->format('d/m/Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($person->burialInfo->grave_photo_path)
                                                <div class="mt-4">
                                                    <span class="block text-xs font-bold text-stone-500 uppercase mb-2">Hình ảnh mộ phần</span>
                                                    <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                        <img src="{{ $person->burialInfo->grave_photo_path }}" class="w-full h-auto object-cover max-h-64 cursor-zoom-in" onclick="window.open(this.src)">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center py-12 text-gray-400 italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            Chưa có thông tin phần mộ
                                        </div>
                                    @endif
                                </div>

                                <!-- TAB: ACHIEVEMENTS -->
                            @elseif ($activeTab === 'achievements')
                                <div class="space-y-4">
                                    @if ($person->achievements && $person->achievements->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($person->achievements as $ach)
                                                <div class="bg-white p-4 rounded-xl border border-green-100 shadow-sm relative overflow-hidden group">
                                                    <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                                                    <div class="pl-2">
                                                        <h4 class="text-base font-bold text-gray-900 group-hover:text-green-700 transition-colors">
                                                            {{ $ach->title }}
                                                        </h4>
                                                        
                                                        <div class="flex items-center gap-2 mt-1 mb-2">
                                                            @if($ach->time_period)
                                                                 <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                                                                    {{ $ach->time_period }}
                                                                 </span>
                                                            @endif
                                                            
                                                            @if($ach->achievement_type && $ach->achievement_type !== 'other')
                                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide px-1">
                                                                    {{ $ach->achievement_type }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        @if($ach->description)
                                                            <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                                {{ $ach->description }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center py-12 text-gray-400 italic bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Chưa có thành tích
                                        </div>
                                    @endif

                                    <!-- Placeholder Add Button (Future Dev) -->
                                    <button wire:click="startEditing('achievements')"
                                        class="w-full py-3 border border-dashed border-green-200 text-green-600 font-bold text-sm rounded-xl hover:bg-green-50 transition-colors bg-white">
                                        + Thêm thành tích mới
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- VIEW 3: FORM (Edit/Add) -->
                @elseif ($viewState === 'form')
                    <div class="flex flex-col h-full bg-gray-50/50">
                        <!-- Header -->
                        <div class="flex items-center gap-2 p-6 border-b border-gray-100 bg-white sticky top-0 z-10">
                            <button wire:click="cancel" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 transition-colors rounded-full hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <h3 class="font-bold text-lg text-gray-800">
                                {{ $mode === 'edit' ? 'Sửa thông tin' : 'Thêm mới' }}</h3>
                        </div>

                        <!-- TABS NAVIGATION (Edit Mode) -->
                        <div class="flex items-center gap-2 px-6 py-2 border-b border-gray-100 bg-white overflow-x-auto scrollbar-hide shrink-0 w-full mb-2">
                            @foreach(['info' => 'Thông tin', 'bio' => 'Tiểu sử', 'burial' => 'Mộ phần', 'achievements' => 'Thành tích'] as $key => $label)
                                <button wire:click="setTab('{{ $key }}')"
                                    class="snap-start flex-shrink-0 px-4 py-2 text-sm font-bold rounded-full transition-all whitespace-nowrap {{ $activeTab === $key ? 'bg-primary-100 text-primary-700 shadow-sm ring-1 ring-primary-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex-1 overflow-y-auto p-6 space-y-5">
                            
                            @if ($errors->any())
                                <div class="bg-red-50 text-red-600 p-2 rounded text-xs">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- TAB: INFO -->
                            @if($activeTab === 'info')
                                <!-- Avatar Field -->
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 border border-gray-200 overflow-hidden relative group shrink-0 shadow-sm">
                                        @if ($avatar)
                                            <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @elseif($existing_avatar_url)
                                            <img src="{{ $existing_avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center w-full h-full text-gray-300 bg-gray-50">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <label class="absolute inset-0 bg-black/10 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-6 h-6 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <input type="file" wire:model="avatar" class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                    
                                    <div class="flex-1 space-y-3">
                                        <div>
                                            <input type="text" wire:model="name" placeholder="Họ và tên đầy đủ"
                                                class="w-full bg-white border border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2.5 text-base font-bold text-gray-900 placeholder-gray-400 focus:placeholder-transparent">
                                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center py-1 px-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                <input type="radio" wire:model="gender" value="male" class="form-radio text-blue-600 w-4 h-4 mr-2"> 
                                                <span class="text-sm font-medium text-gray-700">Nam</span>
                                            </label>
                                            <label class="inline-flex items-center py-1 px-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                <input type="radio" wire:model="gender" value="female" class="form-radio text-pink-500 w-4 h-4 mr-2"> 
                                                <span class="text-sm font-medium text-gray-700">Nữ</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('avatar') <span class="text-red-500 text-xs block mb-3">{{ $message }}</span> @enderror

                                <!-- Nickname & Title -->
                                <div class="grid grid-cols-1 gap-4"> {{-- Stack on mobile --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Tên tự / Biệt danh</label>
                                            <input type="text" wire:model="nickname" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Chức vụ / Học vị</label>
                                            <input type="text" wire:model="title" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                        </div>
                                    </div>
                                </div>

                                <!-- Occupation -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nghề nghiệp</label>
                                    <input type="text" wire:model="occupation" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                </div>

                                <!-- Places -->
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Quê quán (Nguyên quán)</label>
                                        <input type="text" wire:model="hometown" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nơi sinh</label>
                                        <input type="text" wire:model="place_of_birth" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-2 gap-3 pb-2 border-b border-gray-100">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Năm sinh</label>
                                        <input type="number" wire:model="birth_year" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Thứ tự hiển thị</label>
                                        <input type="number" wire:model="order" min="0" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent" placeholder="1">
                                    </div>
                                </div>
                                <div class="pt-2">
                                     <label class="inline-flex items-center p-3 bg-white border border-gray-200 rounded-lg w-full cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="checkbox" wire:model.live="is_alive" class="form-checkbox text-green-600 w-5 h-5 rounded border-gray-300 mr-3">
                                        <span class="text-sm font-bold text-gray-700">Thành viên còn sống</span>
                                    </label>
                                </div>
                                @if(!$is_alive)
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Năm mất</label>
                                        <input type="number" wire:model="death_year" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                @endif

                                <!-- Contact Header -->
                                <div class="pt-4 pb-2">
                                    <h4 class="text-sm font-bold text-primary-700 uppercase tracking-wide border-b border-gray-200 pb-1">Liên hệ</h4>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Địa chỉ hiện tại</label>
                                    <input type="text" wire:model="address" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Số điện thoại</label>
                                        <input type="text" wire:model="phone" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Email</label>
                                        <input type="email" wire:model="email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 focus:placeholder-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-blue-600 uppercase mb-1.5 ml-1">Link Facebook / MXH</label>
                                    <input type="text" wire:model="facebook_url" class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-primary-500 focus:ring-primary-500 bg-white placeholder-gray-400 text-blue-600 focus:placeholder-transparent">
                                </div>

                                @if ($mode === 'add')
                                    @if($parentId)
                                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mt-4 space-y-3">
                                            <span class="block text-xs font-bold text-blue-700 uppercase tracking-wide">Mối quan hệ gia đình</span>
                                            <div class="flex flex-col gap-3">
                                                <label class="inline-flex items-center p-3 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors shadow-sm">
                                                    <input type="radio" wire:model.live="relationship_type" value="child" class="form-radio text-blue-600 w-4 h-4 mr-3"> 
                                                    <span class="text-sm font-medium text-gray-800">Là Con cái</span>
                                                </label>
                                                <label class="inline-flex items-center p-3 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors shadow-sm">
                                                    <input type="radio" wire:model.live="relationship_type" value="spouse" class="form-radio text-pink-500 w-4 h-4 mr-3"> 
                                                    <span class="text-sm font-medium text-gray-800">Là Vợ / Chồng</span>
                                                </label>
                                            </div>

                                            @if ($relationship_type === 'spouse')
                                                <div class="mt-2 pt-3 border-t border-blue-200">
                                                    <label class="block font-bold text-blue-700 text-xs mb-2 uppercase">Danh phận</label>
                                                    <select wire:model="marriage_type_input" class="w-full rounded-lg border-blue-200 text-sm py-2.5 text-blue-900 bg-white focus:border-blue-500 focus:ring-blue-500 border-gray-300 shadow-sm">
                                                        <option value="chinh_thuc">Chính thất (Vợ cả)</option>
                                                        <option value="thu_that">Thứ thất (Vợ lẽ)</option>
                                                        <option value="ke_that">Kế thất (Vợ sau)</option>
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 mt-4 flex items-center gap-3">
                                            <span class="text-2xl">👑</span>
                                            <div>
                                               <span class="block text-xs font-bold text-amber-700 uppercase tracking-wide">Vai trò trong Gia phả</span>
                                               <p class="font-bold text-gray-800">Cụ Tổ (Người khởi tạo)</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif

                            <!-- TAB: BIO -->
                            @if($activeTab === 'bio')
                                <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100">
                                    <span class="block text-xs font-bold text-amber-600 mb-3 uppercase tracking-wide">Tiểu sử & Sự kiện</span>
                                    <div class="space-y-3">
                                        @foreach($biographyList as $index => $bio)
                                            <div class="flex flex-col gap-2 p-3 bg-white rounded-lg border border-amber-100 shadow-sm">
                                                <input type="text" wire:model="biographyList.{{ $index }}.time_period" 
                                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500 bg-white placeholder-gray-400 font-bold text-amber-700 focus:placeholder-transparent" 
                                                    placeholder="Giai đoạn (VD: 1990-1995)">
                                                <textarea wire:model="biographyList.{{ $index }}.content"
                                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500 bg-white placeholder-gray-400 min-h-[80px] focus:placeholder-transparent" 
                                                    placeholder="Nội dung sự kiện..."></textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button wire:click="addMoreBiographies" class="mt-4 w-full py-2.5 rounded-lg border border-dashed border-amber-300 text-amber-600 text-sm font-bold hover:bg-amber-50 transition-colors">
                                        + Thêm dòng tiểu sử
                                    </button>
                                </div>
                            @endif

                            <!-- TAB: BURIAL -->
                            @if($activeTab === 'burial')
                                @if(!$is_alive)
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="w-full">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Ngày mất (Dương lịch/Âm lịch)</label>
                                                <input type="text" wire:model="death_date_full"
                                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-gray-500 focus:ring-gray-500 bg-white placeholder-gray-400 focus:placeholder-transparent" placeholder="VD: 15/07 Âm lịch">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nơi an táng</label>
                                            <input type="text" wire:model="burial_place"
                                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-gray-500 focus:ring-gray-500 bg-white placeholder-gray-400 focus:placeholder-transparent"
                                                placeholder="Nghĩa trang, địa chỉ cụ thể...">
                                        </div>

                                        <!-- Grave Photo -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Hình ảnh mộ phần</label>
                                            
                                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                                <div class="flex items-center gap-4">
                                                    @if ($grave_photo)
                                                        <img src="{{ $grave_photo->temporaryUrl() }}"
                                                            class="w-20 h-20 object-cover rounded-md border border-gray-300 shadow-sm shrink-0">
                                                    @elseif ($existing_grave_photo_url)
                                                        <img src="{{ $existing_grave_photo_url }}"
                                                            class="w-20 h-20 object-cover rounded-md border border-gray-300 shadow-sm shrink-0">
                                                    @else
                                                        <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center shrink-0">
                                                             <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        </div>
                                                    @endif

                                                    <div class="flex-1">
                                                        <label class="block w-full text-center py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 font-bold cursor-pointer hover:bg-gray-50 transition-colors shadow-sm">
                                                            {{ $grave_photo || $existing_grave_photo_url ? 'Thay đổi ảnh' : 'Chọn ảnh mộ phần' }}
                                                            <input type="file" wire:model="grave_photo" class="hidden" accept="image/*">
                                                        </label>
                                                        <div wire:loading wire:target="grave_photo" class="text-xs text-primary-600 mt-2 font-medium">Đang tải ảnh lên...</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('grave_photo') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-10 bg-gray-50 rounded-xl border border-gray-100">
                                        <div class="text-gray-400 italic text-sm mb-3">Thành viên này còn sống.</div>
                                        <button type="button" wire:click="$set('is_alive', false)" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-bold hover:bg-red-50 transition-colors shadow-sm">
                                           Đánh dấu Đã mất
                                        </button>
                                    </div>
                                @endif
                            @endif

                            <!-- TAB: ACHIEVEMENTS -->
                            @if($activeTab === 'achievements')
                                <!-- Achievements List -->
                                <div class="bg-green-50/50 p-4 rounded-xl border border-green-100">
                                    <span class="block text-xs font-bold text-green-700 mb-3 uppercase tracking-wide">Thành tích / Công trạng</span>
                                    <div class="space-y-3">
                                        @foreach($achievementList as $index => $ach)
                                            <div class="flex flex-col gap-2 p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                                <input type="text" wire:model="achievementList.{{ $index }}.time_period" 
                                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-green-500 focus:ring-green-500 bg-white placeholder-gray-400 font-bold text-green-700 focus:placeholder-transparent" 
                                                    placeholder="Năm / Giai đoạn (VD: 2023)">
                                                <input type="text" wire:model="achievementList.{{ $index }}.title"
                                                    class="w-full rounded-lg border-gray-300 shadow-sm text-sm py-2.5 px-3 focus:border-green-500 focus:ring-green-500 bg-white placeholder-gray-400 focus:placeholder-transparent" 
                                                    placeholder="Tên thành tích...">
                                            </div>
                                        @endforeach
                                    </div>
                                    <button wire:click="addMoreAchievements" class="mt-4 w-full py-2.5 rounded-lg border border-dashed border-green-300 text-green-600 text-sm font-bold hover:bg-green-50 transition-colors">
                                        + Thêm dòng thành tích
                                    </button>
                                </div>
                            @endif

                            <!-- Save Button (Fixed at Bottom or always visible in content) -->
                            <div class="pt-2 mt-2 border-t border-gray-100">
                                <button wire:click="save" wire:loading.attr="disabled"
                                    class="w-full py-2.5 bg-[#C41E3A] hover:bg-[#a01830] disabled:opacity-70 disabled:cursor-wait text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md shadow-[#C41E3A]/20 transition-all flex items-center justify-center gap-2">
                                    <span wire:loading.remove
                                        wire:target="save">{{ $mode === 'edit' ? 'Lưu thay đổi' : 'Tạo thành viên' }}</span>
                                    <span wire:loading wire:target="save" class="flex items-center gap-1">
                                        <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Đang lưu...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
