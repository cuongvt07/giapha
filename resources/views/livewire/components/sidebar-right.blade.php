<div>
    {{-- Responsive Sidebar Right: Full screen on mobile, floating card on desktop --}}
    <div wire:ignore.self
        class="pointer-events-auto z-40 font-sans transition-transform duration-300 ease-in-out
               fixed inset-0 lg:absolute lg:inset-auto lg:right-4 lg:top-4 lg:w-80
               bg-white lg:bg-transparent"
        x-data="{
            detailsOpen: @entangle('detailsOpen'),
        }" 
        :class="detailsOpen ? 'translate-x-0 lg:translate-x-0' : 'translate-x-full'"
        style="padding-bottom: env(safe-area-inset-bottom);">

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
        <div class="bg-white/95 backdrop-blur-sm lg:rounded-xl lg:shadow-xl lg:border lg:border-gray-100 w-full overflow-hidden flex flex-col h-full lg:max-h-[85vh]">

            <div class="flex flex-col w-full h-full overflow-hidden">

                <!-- VIEW 1: TOOLS (Default) -->
                @if ($viewState === 'tools')
                    <div class="flex flex-col w-full p-3 overflow-y-auto h-full">
                        <!-- Compact Header -->
                        <div
                            class="flex items-center justify-between p-3 bg-[#C41E3A] text-white rounded-t-xl shrink-0">
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
                        <div class="relative mb-2">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Tìm thành viên..."
                                class="w-full bg-gray-50 border-0 text-gray-700 text-sm rounded-lg focus:ring-1 focus:ring-primary-500 focus:bg-white py-1.5 pl-8 transition-all shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
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
                        <!-- Solid Header (Theme Red) -->
                        <div
                            class="flex items-center justify-between p-3 bg-[#C41E3A] text-white shrink-0 shadow-sm z-20">
                            <button wire:click="cancel"
                                class="p-1.5 hover:bg-white/20 rounded-full transition-colors group text-white"
                                title="Quay lại">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <h3 class="font-bold text-sm uppercase tracking-wider font-serif">Chi Tiết</h3>

                            <div class="flex gap-1">
                                <button wire:click="startEditing"
                                    class="p-1.5 hover:bg-white/20 rounded-full text-white transition-colors"
                                    title="Sửa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button wire:click="deletePerson"
                                    onclick="confirm('Xóa?') || event.stopImmediatePropagation()"
                                    class="p-1.5 hover:bg-white/20 rounded-full text-red-200 hover:text-white transition-colors"
                                    title="Xóa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Profile Cover -->
                        <div class="relative h-28 shrink-0 overflow-hidden">
                            <!-- Blurred BG -->
                            <div class="absolute inset-0 bg-cover bg-center blur-md scale-110 opacity-50"
                                style="background-image: url('{{ $person->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($person->name) }}'); filter: blur(20px);">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/90"></div>

                            <!-- Avatar Center -->
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-6">
                                <div
                                    class="w-20 h-20 rounded-full border-[3px] border-white shadow-lg overflow-hidden bg-white">
                                    @if ($person->avatar_url)
                                        <img src="{{ $person->avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="pt-8 pb-3 px-4 text-center shrink-0">
                            <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ $person->name }}</h2>
                            <div class="text-xs text-gray-500 mt-1 flex items-center justify-center gap-2">
                                <span
                                    class="{{ $person->gender == 'male' ? 'text-blue-500' : 'text-pink-500' }} font-medium">
                                    {{ $person->gender == 'male' ? 'Nam' : 'Nữ' }}
                                </span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $person->birth_year }} - {{ $person->death_year ?? '...' }}</span>
                            </div>
                        </div>

                        {{-- TABS NAVIGATION - Horizontal Scrollable on Mobile --}}
                        <div class="flex items-center gap-1 px-2 border-b border-gray-100 overflow-x-auto snap-x snap-mandatory scrollbar-hide">
                            <button wire:click="setTab('info')"
                                class="snap-start flex-shrink-0 px-3 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'info' ? 'border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                Thông tin
                            </button>
                            <button wire:click="setTab('bio')"
                                class="snap-start flex-shrink-0 px-3 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'bio' ? 'border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                Tiểu sử
                            </button>
                            <button wire:click="setTab('burial')"
                                class="snap-start flex-shrink-0 px-3 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'burial' ? 'border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                Mộ phần
                            </button>
                            <button wire:click="setTab('achievements')"
                                class="snap-start flex-shrink-0 px-3 py-2 text-xs font-semibold border-b-2 transition-colors whitespace-nowrap {{ $activeTab === 'achievements' ? 'border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                                Thành tích
                            </button>
                        </div>

                        <!-- TAB CONTENT -->
                        <div class="flex-1 overflow-y-auto px-4 pb-4 pt-3 space-y-4">

                            <!-- TAB: INFO -->
                            @if ($activeTab === 'info')
                                <div class="space-y-4">
                                    <!-- Personal Details -->
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-2">
                                        @if ($person->nickname)
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-400">Tên tự/kỵ:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-700">{{ $person->nickname }}</span>
                                            </div>
                                        @endif
                                        @if ($person->title)
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-400">Chức vụ/Học vị:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-700">{{ $person->title }}</span>
                                            </div>
                                        @endif
                                        @if ($person->occupation)
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-400">Nghề nghiệp:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-700">{{ $person->occupation }}</span>
                                            </div>
                                        @endif
                                        @if ($person->hometown)
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-400">Quê quán:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-700">{{ $person->hometown }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Family Connections (Original content re-inserted here) -->
                                    <div class="space-y-3">
                                        <!-- Parents -->
                                        @if ($person->father || $person->mother)
                                            <div class="flex items-start gap-3">
                                                <div class="w-6 shrink-0 flex justify-center pt-0.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300 mt-1.5"></div>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">
                                                        Cha Mẹ</p>
                                                    <div class="text-sm text-gray-700">
                                                        @if ($person->father)
                                                            <div>{{ $person->father->name }}</div>
                                                        @endif
                                                        @if ($person->mother)
                                                            <div class="text-gray-500 text-xs">
                                                                {{ $person->mother->name }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Spouses with Detail -->
                                        @php
                                            $marriages =
                                                $person->gender == 'male'
                                                    ? $person->marriagesAsHusband
                                                    : $person->marriagesAsWife;
                                        @endphp
                                        @if ($marriages->count() > 0)
                                            <div class="flex items-start gap-3">
                                                <div class="w-6 shrink-0 flex justify-center pt-0.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-pink-300 mt-1.5"></div>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">
                                                        Vợ / Chồng</p>
                                                    <div class="space-y-1">
                                                        @foreach ($marriages as $marriage)
                                                            @php
                                                                $spouse =
                                                                    $person->gender == 'male'
                                                                        ? $marriage->wife
                                                                        : $marriage->husband;
                                                            @endphp
                                                            @if ($spouse)
                                                                <div class="text-sm text-gray-700 flex items-center gap-1 cursor-pointer hover:text-primary-600 transition-colors"
                                                                    wire:click="loadPerson({{ $spouse->id }})">
                                                                    <span>{{ $spouse->name }}</span>
                                                                    @if ($marriage->marriage_type)
                                                                        <span
                                                                            class="text-[10px] px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                                            {{ $marriage->marriage_type == 'chinh_thuc' ? 'Chính thất' : ($marriage->marriage_type == 'thu_that' ? 'Thứ thất' : $marriage->marriage_type) }}
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
                                        <div class="flex items-start gap-3">
                                            <div class="w-6 shrink-0 flex justify-center pt-0.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-blue-300 mt-1.5"></div>
                                            </div>
                                            <div>
                                                <div class="flex items-center justify-between gap-4">
                                                    <p
                                                        class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">
                                                        Con cái ({{ $person->children->count() }})</p>
                                                </div>
                                                @if ($person->children->count() > 0)
                                                    <div class="mt-1 space-y-2 relative">
                                                        <div
                                                            class="absolute left-[-19px] top-2 bottom-2 w-px bg-gray-100">
                                                        </div>
                                                        @foreach ($person->children as $child)
                                                            <div class="text-sm text-gray-600 pl-0 flex items-center gap-2 cursor-pointer hover:text-primary-600 transition-colors"
                                                                wire:click="loadPerson({{ $child->id }})">
                                                                <span>{{ $child->name }}</span>
                                                                <span
                                                                    class="text-[10px] text-gray-400">{{ $child->birth_year }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-400 italic mt-0.5">Chưa có thông tin
                                                    </p>
                                                @endif

                                                <!-- Add Child Button Inside Info Tab -->
                                                <button wire:click="startAdding({ parentId: {{ $person->id }} })"
                                                    class="mt-2 w-full py-1.5 rounded border border-dashed border-gray-300 text-[10px] font-medium text-gray-500 hover:bg-gray-50 hover:text-primary-600 transition-all flex items-center justify-center gap-1">
                                                    + Thêm con
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB: BIO -->
                            @elseif ($activeTab === 'bio')
                                <div class="prose prose-sm max-w-none text-gray-600 text-sm">
                                    @if ($person->biography)
                                        {!! nl2br(e($person->biography)) !!}
                                    @else
                                        <div class="text-center py-8 text-gray-400 italic">
                                            Chưa có tiểu sử.
                                        </div>
                                    @endif
                                </div>

                                <!-- TAB: BURIAL -->
                            @elseif ($activeTab === 'burial')
                                <div class="space-y-4">
                                    @if ($person->burialInfo)
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                            <div class="flex items-center gap-2 mb-2 text-gray-800 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $person->burialInfo->burial_place ?? 'Chưa cập nhật địa điểm' }}
                                            </div>
                                            @if ($person->burialInfo->burial_date)
                                                <div class="text-xs text-gray-500 mb-2">
                                                    Ngày an táng:
                                                    {{ \Carbon\Carbon::parse($person->burialInfo->burial_date)->format('d/m/Y') }}
                                                </div>
                                            @endif
                                            @if ($person->burialInfo->grave_photo_path)
                                                <img src="{{ $person->burialInfo->grave_photo_path }}"
                                                    class="w-full h-32 object-cover rounded-md mt-2">
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-400 italic">
                                            Chưa có thông tin mộ phần.
                                        </div>
                                    @endif
                                </div>

                                <!-- TAB: ACHIEVEMENTS -->
                            @elseif ($activeTab === 'achievements')
                                <div class="space-y-4">
                                    @if ($person->achievements && $person->achievements->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($person->achievements as $ach)
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <div class="text-sm font-semibold text-gray-800">
                                                                {{ $ach->title }}</div>
                                                            <div class="text-xs text-secondary-600 font-medium mb-1">
                                                                {{ $ach->achievement_type }}</div>
                                                            @if ($ach->achievement_date)
                                                                <div class="text-[10px] text-gray-400 mb-1">
                                                                    {{ \Carbon\Carbon::parse($ach->achievement_date)->format('d/m/Y') }}
                                                                </div>
                                                            @endif
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                {{ $ach->description }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-400 italic">
                                            Chưa có thành tích nào được ghi nhận.
                                        </div>
                                    @endif

                                    <!-- Placeholder Add Button (Future Dev) -->
                                    <button
                                        class="w-full py-2 border border-dashed border-gray-300 text-gray-400 text-xs rounded hover:bg-gray-50 hover:text-primary-500 transition-colors">
                                        + Thêm thành tích
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- VIEW 3: FORM (Edit/Add) -->
                @elseif ($viewState === 'form')
                    <div class="flex flex-col h-full bg-gray-50/50">
                        <!-- Header -->
                        <div class="flex items-center gap-2 p-3 border-b border-gray-100 bg-white">
                            <button wire:click="cancel" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <h3 class="font-bold text-sm text-gray-800">
                                {{ $mode === 'edit' ? 'Sửa thông tin' : 'Thêm mới' }}</h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-3 space-y-3">
                            <!-- Avatar Field -->
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-14 h-14 rounded-full bg-gray-100 border border-gray-200 overflow-hidden relative group shrink-0">
                                    @if ($avatar)
                                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif($existing_avatar_url)
                                        <img src="{{ $existing_avatar_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="flex items-center justify-center w-full h-full text-gray-300 bg-gray-50">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <label
                                        class="absolute inset-0 bg-black/10 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-5 h-5 text-white drop-shadow-sm" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <input type="file" wire:model="avatar" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <div class="flex-1">
                                    <input type="text" wire:model="name" placeholder="Họ và tên"
                                        class="w-full bg-transparent border-0 border-b border-gray-200 focus:border-primary-500 focus:ring-0 px-0 py-1 text-sm font-semibold text-gray-800 placeholder-gray-400">
                                    @error('name')
                                        <span class="text-red-500 text-[10px]">{{ $message }}</span>
                                    @enderror
                                    <div class="flex gap-3 mt-1">
                                        <label class="inline-flex items-center text-xs text-gray-600">
                                            <input type="radio" wire:model="gender" value="male"
                                                class="form-radio text-primary-500 w-3 h-3 mr-1"> Nam
                                        </label>
                                        <label class="inline-flex items-center text-xs text-gray-600">
                                            <input type="radio" wire:model="gender" value="female"
                                                class="form-radio text-pink-500 w-3 h-3 mr-1"> Nữ
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Branch Selection -->
                            <div class="mb-3">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Thuộc Chi /
                                    Phái / Nhánh</label>
                                <select wire:model="family_branch_id"
                                    class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                    <option value="">-- Không xác định --</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nickname & Title -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tên tự /
                                        Biệt danh</label>
                                    <input type="text" wire:model="nickname"
                                        class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Chức vụ /
                                        Học vị</label>
                                    <input type="text" wire:model="title"
                                        class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                </div>
                            </div>

                            <!-- Occupation & Hometown -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nghề
                                    nghiệp</label>
                                <input type="text" wire:model="occupation"
                                    class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Quê
                                        quán</label>
                                    <input type="text" wire:model="hometown"
                                        class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nơi
                                        sinh</label>
                                    <input type="text" wire:model="place_of_birth"
                                        class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Năm
                                        sinh</label>
                                    <input type="number" wire:model="birth_year"
                                        class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-primary-500 focus:ring-primary-500 bg-white">
                                </div>
                                <div class="flex items-end pb-1.5">
                                    <label
                                        class="inline-flex items-center text-xs text-gray-600 cursor-pointer select-none">
                                        <input type="checkbox" wire:model.live="is_alive"
                                            class="form-checkbox text-green-500 w-3.5 h-3.5 rounded mr-1.5 border-gray-300">
                                        Còn sống
                                    </label>
                                </div>
                            </div>

                            @if (!$is_alive)
                                <div x-transition class="bg-gray-100 p-2 rounded-md space-y-2">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Năm
                                                mất</label>
                                            <input type="number" wire:model="death_year"
                                                class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-gray-500 focus:ring-gray-500">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Ngày
                                                mất (Full)</label>
                                            <input type="date" wire:model="death_date_full"
                                                class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-gray-500 focus:ring-gray-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Nơi an
                                            táng</label>
                                        <input type="text" wire:model="burial_place"
                                            class="w-full rounded-md border-gray-200 text-xs py-1.5 focus:border-gray-500 focus:ring-gray-500"
                                            placeholder="Nghĩa trang, địa chỉ...">
                                    </div>

                                    <!-- Grave Photo -->
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Hình
                                            ảnh mộ phần</label>
                                        <div class="mt-1 flex items-center gap-3">
                                            @if ($grave_photo)
                                                <img src="{{ $grave_photo->temporaryUrl() }}"
                                                    class="w-16 h-16 object-cover rounded-md border border-gray-300 shadow-sm">
                                            @elseif ($existing_grave_photo_url)
                                                <img src="{{ $existing_grave_photo_url }}"
                                                    class="w-16 h-16 object-cover rounded-md border border-gray-300 shadow-sm">
                                            @endif

                                            <label
                                                class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs text-gray-700 cursor-pointer hover:bg-gray-50 transition-colors shadow-sm font-medium flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $grave_photo || $existing_grave_photo_url ? 'Thay ảnh' : 'Chọn ảnh' }}</span>
                                                <input type="file" wire:model="grave_photo" class="hidden"
                                                    accept="image/*">
                                            </label>
                                        </div>
                                        <div wire:loading wire:target="grave_photo"
                                            class="text-[10px] text-gray-500 mt-1 italic">Đang tải ảnh lên...</div>
                                    </div>
                                </div>
                            @endif

                            @if ($mode === 'add')
                                <div class="bg-blue-50/50 p-2 rounded-md border border-blue-100">
                                    <span class="block text-[10px] font-bold text-blue-600 mb-1.5 uppercase">Mối quan
                                        hệ</span>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center text-xs text-gray-700">
                                            <input type="radio" wire:model.live="relationship_type" value="child"
                                                class="form-radio text-blue-600 w-3.5 h-3.5 mr-1.5"> Con cái
                                        </label>
                                        <label class="inline-flex items-center text-xs text-gray-700">
                                            <input type="radio" wire:model.live="relationship_type" value="spouse"
                                                class="form-radio text-blue-600 w-3.5 h-3.5 mr-1.5"> Vợ/Chồng
                                        </label>
                                    </div>

                                    @if ($relationship_type === 'spouse')
                                        <div class="mt-2 pt-2 border-t border-blue-200">
                                            <label
                                                class="block font-bold text-blue-700 text-[10px] mb-1 uppercase">Danh
                                                phận</label>
                                            <select wire:model="marriage_type_input"
                                                class="w-full rounded border-blue-200 text-xs py-1.5 text-blue-800 bg-white focus:border-blue-500 focus:ring-blue-500">
                                                <option value="chinh_thuc">Chính thất (Vợ cả)</option>
                                                <option value="thu_that">Thứ thất (Vợ lẽ)</option>
                                                <option value="ke_that">Kế thất (Vợ sau)</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Save Button -->
                            <button wire:click="save" wire:loading.attr="disabled"
                                class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 disabled:opacity-70 disabled:cursor-wait text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md shadow-primary-500/20 transition-all mt-2 flex items-center justify-center gap-2">
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
                @endif
            </div>
        </div>
    </div>
</div>
