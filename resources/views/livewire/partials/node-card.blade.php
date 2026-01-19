@props(['person', 'generationLevel' => null])

@php
    // Calculate generation level if not provided
    if ($generationLevel === null) {
        $generationLevel = 1;
        $current = $person;
        while ($current->father_id || $current->mother_id) {
            $generationLevel++;
            $current = $current->father ?? $current->mother;
            if ($generationLevel > 10) {
                break;
            }
        }
    }

    // COMPACT HIERARCHICAL STYLING
    // Generation 1 (Thủy Tổ): Most Sacred - HORIZONTAL
    if ($generationLevel == 1) {
        $cardWidth = 'w-56';
        $cardPadding = 'p-3';
        $avatarSize = 'w-16 h-16';
        $avatarIcon = 'h-8 w-8';
        $nameSize = 'text-base';
        $yearSize = 'text-sm';
        $borderWidth = 'border-[4px]';
        $borderColor = 'border-red-700';
        $shadowClass = 'shadow-xl shadow-red-900/40';
        $bgOverride = 'bg-gradient-to-br from-amber-50 via-yellow-100 to-amber-100';
        $topBorderColor = 'border-t-[6px] border-t-red-700';
        $ringClass = 'ring-4 ring-yellow-400 ring-offset-2 ring-offset-red-800';
        $decorativeClass = 'ancestor-card';
        $nameClass = 'font-bold text-red-900 tracking-wide';
        $yearClass = 'font-semibold text-red-800';
        $layoutHorizontal = true;
    }
    // Generation 2: High Prestige - HORIZONTAL
    elseif ($generationLevel == 2) {
        $cardWidth = 'w-48';
        $cardPadding = 'p-2.5';
        $avatarSize = 'w-14 h-14';
        $avatarIcon = 'h-7 w-7';
        $nameSize = 'text-sm';
        $yearSize = 'text-xs';
        $borderWidth = 'border-[3px]';
        $borderColor = 'border-yellow-600';
        $shadowClass = 'shadow-lg shadow-yellow-700/30';
        $bgOverride = 'bg-gradient-to-br from-yellow-50 to-amber-100';
        $topBorderColor = 'border-t-4 border-t-yellow-600';
        $ringClass = 'ring-2 ring-yellow-300';
        $decorativeClass = '';
        $nameClass = 'font-bold text-yellow-900';
        $yearClass = 'font-semibold text-yellow-800';
        $layoutHorizontal = true;
    }
    // Generation 3: Respected - Horizontal
    elseif ($generationLevel == 3) {
        $cardWidth = 'w-44';
        $cardPadding = 'p-2';
        $avatarSize = 'w-12 h-12';
        $avatarIcon = 'h-6 w-6';
        $nameSize = 'text-sm';
        $yearSize = 'text-xs';
        $borderWidth = 'border-2';
        $borderColor = 'border-amber-500';
        $shadowClass = 'shadow-md shadow-amber-600/20';
        $bgOverride = 'bg-gradient-to-br from-amber-50 to-yellow-50';
        $topBorderColor = 'border-t-[3px] border-t-amber-500';
        $ringClass = 'ring-1 ring-amber-200';
        $decorativeClass = '';
        $nameClass = 'font-bold text-amber-900';
        $yearClass = 'font-medium text-amber-700';
        $layoutHorizontal = true;
        $layoutVerticalText = false;
    }
    // Generation 4+: VERTICAL TEXT layout (like traditional gia pha)
    else {
        $cardWidth = '';
        $cardPadding = 'px-1.5 py-2';
        $avatarSize = 'w-8 h-8';
        $avatarIcon = 'h-4 w-4';
        $nameSize = 'text-xs';
        $yearSize = 'text-[10px]';
        $borderWidth = 'border-2';
        $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
        $shadowClass = 'shadow-md hover:shadow-lg';
        $bgOverride =
            $person->gender === 'male'
                ? 'bg-gradient-to-b from-blue-50 to-blue-100'
                : 'bg-gradient-to-b from-pink-50 to-pink-100';
        $topBorderColor = $person->is_alive ? 'border-t-[3px] border-t-green-500' : 'border-t-[3px] border-t-gray-400';
        $ringClass = '';
        $decorativeClass = '';
        $nameClass = $person->gender === 'male' ? 'font-bold text-blue-900' : 'font-bold text-pink-900';
        $yearClass = 'font-semibold text-gray-700';
        $layoutHorizontal = false;
        $layoutVerticalText = true;
    }

    // Visibility Logic
    $isVisible = true;
    if (isset($filters)) {
        if (!$filters['showMale'] && $person->gender === 'male') {
            $isVisible = false;
        }
        if (!$filters['showFemale'] && $person->gender === 'female') {
            $isVisible = false;
        }
        if (!$filters['showAlive'] && $person->is_alive) {
            $isVisible = false;
        }
        if (!$filters['showDeceased'] && !$person->is_alive) {
            $isVisible = false;
        }
    }

    $statusClass = $person->is_alive ? '' : 'grayscale-[20%] opacity-95';
    if (!$isVisible) {
        $statusClass .= ' opacity-20 filter grayscale';
    }
@endphp

<div class="group relative flex flex-col items-center cursor-pointer transition-all duration-300 hover:scale-105 hover:-translate-y-0.5 z-10 focus:outline-none focus:ring-2 focus:ring-primary-400 rounded-xl"
    tabindex="0" @keydown.enter.prevent="$dispatch('person-selected', { id: {{ $person->id }} })"
    @keydown.space.prevent="$dispatch('person-selected', { id: {{ $person->id }} })"
    wire:click="$dispatch('person-selected', { id: {{ $person->id }} })">

    <!-- Connector Point Top -->
    <div
        class="w-2 h-2 bg-white border-2 border-gray-400 rounded-full absolute -top-1 z-20 group-hover:border-primary-500 group-hover:scale-125 transition-all">
    </div>

    <!-- Combined Card Container (Main Person + Spouses) -->
    <div id="node-{{ $person->id }}"
        @if ($person->father_id) data-parent-id="node-{{ $person->father_id }}" @endif
        @if ($person->mother_id && !$person->father_id) data-parent-id="node-{{ $person->mother_id }}" @endif
        class="flex items-stretch rounded-xl {{ $shadowClass }} {{ $borderWidth }} {{ $borderColor }} {{ $ringClass }} {{ $decorativeClass }} transition-all duration-300 ease-out group-hover:border-primary-400 relative
        @if (isset($filters['focusedPersonId']) && $filters['focusedPersonId'] == $person->id) !border-purple-500 !border-[3px] !ring-2 !ring-purple-300 @endif">

        @if ($generationLevel == 1)
            <!-- Decorative Corner Ornaments for Ancestor -->
            <div class="absolute -top-1 -left-1 w-5 h-5 border-t-3 border-l-3 border-yellow-500 rounded-tl-md"></div>
            <div class="absolute -top-1 -right-1 w-5 h-5 border-t-3 border-r-3 border-yellow-500 rounded-tr-md"></div>
            <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-3 border-l-3 border-yellow-500 rounded-bl-md"></div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-3 border-r-3 border-yellow-500 rounded-br-md">
            </div>
        @endif

        <!-- Primary Person Card -->
        @if ($layoutHorizontal)
            {{-- HORIZONTAL LAYOUT for Gen 1-2 --}}
            <div
                class="{{ $cardWidth }} flex items-center gap-2 {{ $cardPadding }} pb-3 relative {{ $bgOverride }} {{ $topBorderColor }} {{ $statusClass }}">

                @if (!$person->is_alive)
                    <div class="absolute top-1 right-1 w-1.5 h-1.5 bg-gray-500 rounded-full" title="Đã mất"></div>
                @endif

                <!-- Avatar -->
                <div
                    class="{{ $avatarSize }} flex-shrink-0 rounded-full border-2 border-white shadow-md overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 group-hover:scale-105 transition-transform">
                    @if ($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $avatarIcon }}" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <h3 class="{{ $nameClass }} {{ $nameSize }} truncate leading-tight"
                        style="text-shadow: 0 1px 2px rgba(255,255,255,0.8);">
                        {{ $person->name }}
                    </h3>

                    @if ($filters['showDates'] ?? true)
                        <p class="{{ $yearClass }} {{ $yearSize }} leading-tight mt-0.5">
                            {{ $person->birth_year ?? '?' }} -
                            {{ $person->death_year ?? ($person->is_alive ? 'nay' : '?') }}
                        </p>
                    @endif

                    @if ($person->generation_id)
                        <span
                            class="inline-block mt-1 px-1.5 py-0.5 bg-indigo-500/90 text-white text-[9px] font-bold rounded leading-none w-fit">
                            Đời {{ $person->generation_id }}
                        </span>
                    @endif
                </div>

                <!-- Action Buttons (Hover) -->
                <div
                    class="absolute -bottom-2 left-1/2 -translate-x-1/2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-30">
                    <button
                        class="bg-indigo-500 text-white rounded-full p-0.5 shadow-md hover:bg-indigo-600 hover:scale-110 transition-all center-node-btn"
                        title="Tập trung"
                        @click.stop="$dispatch('center-on-node', { nodeId: 'node-{{ $person->id }}' })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        class="bg-primary-500 text-white rounded-full p-0.5 shadow-md hover:bg-primary-600 hover:scale-110 transition-all"
                        title="Thêm"
                        x-on:click.stop="$dispatch('open-add-modal', { parentId: {{ $person->id }} })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        class="bg-red-500 text-white rounded-full p-0.5 shadow-md hover:bg-red-600 hover:scale-110 transition-all"
                        title="Xóa" wire:confirm="Bạn có chắc muốn xóa?"
                        wire:click.stop="deletePerson({{ $person->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @else
            {{-- VERTICAL TEXT LAYOUT for Gen 4+ (like traditional gia pha) --}}
            <div class="flex flex-col items-center {{ $cardPadding }} pb-3 relative {{ $bgOverride }} {{ $topBorderColor }} {{ $statusClass }}"
                style="min-width: 2.5rem;">

                @if (!$person->is_alive)
                    <div class="absolute top-0.5 right-0.5 w-1 h-1 bg-gray-500 rounded-full" title="Đã mất"></div>
                @endif

                <!-- Avatar (small) -->
                <div
                    class="{{ $avatarSize }} rounded-full border border-white shadow-sm overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 group-hover:scale-105 transition-transform mb-1 flex-shrink-0">
                    @if ($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $avatarIcon }}" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Name (Vertical Text) -->
                <div class="{{ $nameClass }} {{ $nameSize }} leading-none text-center whitespace-nowrap"
                    style="writing-mode: vertical-rl; text-orientation: mixed; text-shadow: 0 1px 1px rgba(255,255,255,0.8);">
                    {{ $person->name }}
                </div>

                <!-- Year -->
                @if ($filters['showDates'] ?? true)
                    <div class="{{ $yearClass }} {{ $yearSize }} mt-1 leading-none"
                        style="writing-mode: vertical-rl; text-orientation: mixed;">
                        {{ $person->birth_year ?? '?' }}
                    </div>
                @endif

                <!-- Action Buttons (Hover) -->
                <div
                    class="absolute -bottom-2 left-1/2 -translate-x-1/2 flex gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity z-30">
                    <button
                        class="bg-indigo-500 text-white rounded-full p-0.5 shadow-md hover:bg-indigo-600 hover:scale-110 transition-all center-node-btn"
                        title="Tập trung"
                        @click.stop="$dispatch('center-on-node', { nodeId: 'node-{{ $person->id }}' })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        class="bg-primary-500 text-white rounded-full p-0.5 shadow-md hover:bg-primary-600 hover:scale-110 transition-all"
                        title="Thêm"
                        x-on:click.stop="$dispatch('open-add-modal', { parentId: {{ $person->id }} })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        class="bg-red-500 text-white rounded-full p-0.5 shadow-md hover:bg-red-600 hover:scale-110 transition-all"
                        title="Xóa" wire:confirm="Bạn có chắc muốn xóa?"
                        wire:click.stop="deletePerson({{ $person->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Spouses List - COMPACT -->
        @if ($filters['showSpouses'] ?? true)
            @foreach ($person->spouses as $spouse)
                @php
                    $spouseGenderBg =
                        $spouse->gender === 'male'
                            ? 'bg-gradient-to-br from-blue-50 to-blue-100'
                            : 'bg-gradient-to-br from-pink-50 to-pink-100';
                    $spouseStatusBorder = $spouse->is_alive ? 'border-t-green-500' : 'border-t-gray-400';
                    $spouseNameColor = $spouse->gender === 'male' ? 'text-blue-900' : 'text-pink-900';
                @endphp

                @if ($layoutHorizontal)
                    {{-- Spouse for Gen 1-2: Horizontal compact --}}
                    <div class="w-28 flex items-center gap-1.5 p-2 border-l border-gray-300 relative {{ $spouseGenderBg }} {{ $spouseStatusBorder }} border-t-[3px] {{ !$spouse->is_alive ? 'grayscale-[20%] opacity-95' : '' }} hover:bg-opacity-80 transition-all"
                        tabindex="0"
                        @keydown.enter.prevent="$dispatch('person-selected', { id: {{ $spouse->id }} })"
                        @keydown.space.prevent="$dispatch('person-selected', { id: {{ $spouse->id }} })"
                        wire:click.stop="$dispatch('person-selected', { id: {{ $spouse->id }} })">

                        @if (!$spouse->is_alive)
                            <div class="absolute top-1 right-1 w-1.5 h-1.5 bg-gray-400 rounded-full" title="Đã mất">
                            </div>
                        @endif

                        <div
                            class="w-8 h-8 flex-shrink-0 rounded-full border border-white shadow-sm overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                            @if ($spouse->avatar_url)
                                <img src="{{ $spouse->avatar_url }}" alt="{{ $spouse->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold {{ $spouseNameColor }} text-xs truncate leading-tight">
                                {{ $spouse->name }}</h3>
                            <p class="text-[10px] text-gray-600 font-medium leading-tight">
                                {{ $spouse->birth_year ?? '?' }}</p>
                        </div>
                    </div>
                @else
                    {{-- Spouse for Gen 4+: Vertical text --}}
                    <div class="flex flex-col items-center px-1 py-1.5 border-l border-gray-300 relative {{ $spouseGenderBg }} {{ $spouseStatusBorder }} border-t-[3px] {{ !$spouse->is_alive ? 'grayscale-[20%] opacity-95' : '' }} hover:bg-opacity-80 transition-all"
                        style="min-width: 2rem;" tabindex="0"
                        @keydown.enter.prevent="$dispatch('person-selected', { id: {{ $spouse->id }} })"
                        @keydown.space.prevent="$dispatch('person-selected', { id: {{ $spouse->id }} })"
                        wire:click.stop="$dispatch('person-selected', { id: {{ $spouse->id }} })">

                        @if (!$spouse->is_alive)
                            <div class="absolute top-0.5 right-0.5 w-1 h-1 bg-gray-400 rounded-full" title="Đã mất">
                            </div>
                        @endif

                        <div
                            class="w-6 h-6 rounded-full border border-white shadow-sm overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 mb-0.5 flex-shrink-0">
                            @if ($spouse->avatar_url)
                                <img src="{{ $spouse->avatar_url }}" alt="{{ $spouse->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="font-bold {{ $spouseNameColor }} text-[10px] leading-none whitespace-nowrap"
                            style="writing-mode: vertical-rl; text-orientation: mixed;">
                            {{ $spouse->name }}
                        </div>
                        <div class="text-[9px] text-gray-600 font-medium leading-none mt-0.5"
                            style="writing-mode: vertical-rl; text-orientation: mixed;">
                            {{ $spouse->birth_year ?? '?' }}
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

    </div>

    <!-- Connector Point Bottom -->
    <div class="w-2 h-2 bg-gray-900 rounded-full absolute -bottom-1 z-20"></div>
</div>
