{{-- Mobile Unified Person Modal --}}
<div class="flex flex-col h-full bg-white">
    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">
            @if ($mode === 'add')
                Thêm thành viên
            @elseif($mode === 'edit')
                Chỉnh sửa thông tin
            @else
                Chi tiết thành viên
            @endif
        </h2>
        <button wire:click="closeModal" class="p-2 hover:bg-gray-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto">
        @if ($mode === 'view')
            {{-- VIEW MODE WITH TABS --}}
            <div x-data="{ activeTab: 'info' }">
                {{-- Tabs Header --}}
                <div class="flex border-b border-gray-200 sticky top-0 bg-white z-10">
                    <button @click="activeTab = 'info'"
                        :class="activeTab === 'info' ? 'border-primary-600 text-primary-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-3 text-sm font-medium border-b-2 text-center transition-colors">
                        Thông tin
                    </button>
                    <button @click="activeTab = 'family'"
                        :class="activeTab === 'family' ? 'border-primary-600 text-primary-600' :
                            'border-transparent text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-3 text-sm font-medium border-b-2 text-center transition-colors">
                        Gia đình
                    </button>
                    @if (!$selectedPerson?->is_alive)
                        <button @click="activeTab = 'burial'"
                            :class="activeTab === 'burial' ? 'border-primary-600 text-primary-600' :
                                'border-transparent text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-3 text-sm font-medium border-b-2 text-center transition-colors">
                            Mộ phần
                        </button>
                    @endif
                </div>

                {{-- Tab Contents --}}
                <div class="p-4 space-y-4">
                    {{-- TAB 1: INFO --}}
                    <div x-show="activeTab === 'info'" class="space-y-6 animate-fade-in">
                        {{-- Avatar & Basic Info --}}
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg overflow-hidden mb-3">
                                @if ($selectedPerson?->avatar_url)
                                    <img src="{{ $selectedPerson->avatar_url }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 text-center">{{ $selectedPerson?->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm text-gray-500">
                                    {{ $selectedPerson?->birth_year ?? '?' }} -
                                    {{ $selectedPerson?->death_year ?? ($selectedPerson?->is_alive ? 'Nay' : '?') }}
                                </span>
                                @if ($selectedPerson?->is_alive)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Đang
                                        sống</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Đã
                                        mất</span>
                                @endif
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Tên tự / Biệt danh</label>
                                <p class="font-medium text-gray-900">{{ $selectedPerson?->nickname ?? '---' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Giới tính</label>
                                <p class="font-medium text-gray-900">
                                    {{ $selectedPerson?->gender === 'male' ? 'Nam' : 'Nữ' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Thứ bậc</label>
                                <p class="font-medium text-gray-900">
                                    {{ $selectedPerson?->birth_order ? 'Con thứ ' . $selectedPerson->birth_order : '---' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Đời thứ</label>
                                <p class="font-medium text-gray-900">{{ $selectedPerson?->generation_id ?? '---' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Quê quán</label>
                                <p class="font-medium text-gray-900">{{ $selectedPerson?->hometown ?? '---' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Địa chỉ hiện tại</label>
                                <p class="font-medium text-gray-900">{{ $selectedPerson?->address ?? '---' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-gray-500 uppercase tracking-wider">Tiểu sử / Ghi chú</label>
                                <p class="text-sm text-gray-700 leading-relaxed mt-1">
                                    {{ $selectedPerson?->bio ?? 'Chưa có thông tin tiểu sử.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: FAMILY --}}
                    <div x-show="activeTab === 'family'" class="space-y-6 animate-fade-in" style="display: none;">
                        {{-- Parents --}}
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Cha Mẹ
                            </h4>
                            <div class="space-y-3 pl-2 border-l-2 border-blue-100">
                                @if ($selectedPerson?->father)
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $selectedPerson->father->avatar_url ?? asset('images/default-avatar.png') }}"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPerson->father->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">Cha</p>
                                            </div>
                                        </div>
                                        <button wire:click="selectPerson({{ $selectedPerson->father->id }})"
                                            class="text-blue-600 text-sm font-medium px-3 py-1 bg-blue-50 rounded-full">Xem</button>
                                    </div>
                                @endif
                                @if ($selectedPerson?->mother)
                                    <div class="flex items-center justify-between group">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $selectedPerson->mother->avatar_url ?? asset('images/default-avatar.png') }}"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $selectedPerson->mother->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">Mẹ</p>
                                            </div>
                                        </div>
                                        <button wire:click="selectPerson({{ $selectedPerson->mother->id }})"
                                            class="text-pink-600 text-sm font-medium px-3 py-1 bg-pink-50 rounded-full">Xem</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Spouses --}}
                        @if ($selectedPerson?->spouses->count() > 0)
                            <div>
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    Vợ / Chồng
                                </h4>
                                <div class="space-y-3 pl-2 border-l-2 border-pink-100">
                                    @foreach ($selectedPerson->spouses as $spouse)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $spouse->avatar_url ?? asset('images/default-avatar.png') }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $spouse->name }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $spouse->pivot->marriage_year ? 'Kết hôn: ' . $spouse->pivot->marriage_year : 'Vợ/Chồng' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <button wire:click="selectPerson({{ $spouse->id }})"
                                                class="text-purple-600 text-sm font-medium px-3 py-1 bg-purple-50 rounded-full">Xem</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Children --}}
                        @if ($selectedPerson?->children->count() > 0)
                            <div>
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Các Con
                                </h4>
                                <div class="space-y-3 pl-2 border-l-2 border-green-100">
                                    @foreach ($selectedPerson->children as $child)
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $child->avatar_url ?? asset('images/default-avatar.png') }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                <div>
                                                    <p
                                                        class="font-medium {{ $child->gender === 'male' ? 'text-blue-900' : 'text-pink-900' }}">
                                                        {{ $child->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $child->birth_year }}
                                                    </p>
                                                </div>
                                            </div>
                                            <button wire:click="selectPerson({{ $child->id }})"
                                                class="text-gray-600 text-sm font-medium px-3 py-1 bg-gray-100 rounded-full">Xem</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- TAB 3: BURIAL --}}
                    @if (!$selectedPerson?->is_alive)
                        <div x-show="activeTab === 'burial'" class="space-y-6 animate-fade-in"
                            style="display: none;">
                            <div class="bg-gray-50 p-4 rounded-xl space-y-4">
                                @if ($selectedPerson->burialInfo?->grave_photo_path)
                                    <div class="w-full aspect-video rounded-lg overflow-hidden shadow-sm">
                                        <img src="{{ $selectedPerson->burialInfo->grave_photo_path }}"
                                            class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                            onclick="window.open(this.src, '_blank')">
                                    </div>
                                @else
                                    <div
                                        class="w-full aspect-video bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                        <span class="text-sm">Chưa có ảnh mộ phần</span>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase tracking-wider">Ngày mất</label>
                                        <p class="font-medium text-gray-900">
                                            {{ $selectedPerson->burialInfo?->burial_date ? \Carbon\Carbon::parse($selectedPerson->burialInfo->burial_date)->format('d/m/Y') : '---' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase tracking-wider">Nơi an
                                            táng</label>
                                        <p class="font-medium text-gray-900">
                                            {{ $selectedPerson->burialInfo?->burial_place ?? '---' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase tracking-wider">Tọa độ / Vị
                                            trí</label>
                                        <p class="font-medium text-gray-900">
                                            {{ $selectedPerson->burialInfo?->coordinates ?? '---' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action Footer (View Mode Only) --}}
                <div class="p-4 border-t border-gray-200 bg-gray-50 flex gap-3 sticky bottom-0 z-20">
                    <button wire:click="openModal({{ $selectedPerson->id }}, 'add')"
                        class="flex-1 py-3 bg-white border border-green-500 text-green-600 font-bold rounded-xl shadow-sm hover:bg-green-50 active:scale-95 transition-all">
                        + Thêm con
                    </button>
                    <button wire:click="openModal({{ $selectedPerson->id }}, 'edit')"
                        class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 active:scale-95 transition-all">
                        Chỉnh sửa
                    </button>
                    <button wire:click="deletePerson({{ $selectedPerson->id }})" wire:confirm="Xóa thành viên này?"
                        class="px-4 py-3 bg-white border border-red-200 text-red-500 rounded-xl hover:bg-red-50 active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @else
            {{-- ADD / EDIT MODE (Reusing the form content logic, but in this structure) --}}
            <div class="p-1">
                @include('livewire.partials.mobile-add-form')
            </div>
        @endif
    </div>
</div>
