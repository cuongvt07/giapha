{{-- Mobile Horizontal Tree Node (Recursive) - Matches Desktop Styling --}}
@php
    // Calculate generation level
    $generationLevel = $person->generation ?? 1;

    // GENERATION-BASED STYLING (Match Desktop)
    if ($generationLevel == 1) {
        // Gen 1 (Thủy Tổ): Most Sacred
        $cardBg = 'bg-gradient-to-br from-amber-50 via-yellow-100 to-amber-100';
        $borderColor = 'border-red-700';
        $borderWidth = 'border-[4px]';
        $topBorder = 'border-t-[6px] border-t-red-700';
        $ringClass = 'ring-4 ring-yellow-400';
        $shadowClass = 'shadow-xl shadow-red-900/40';
        $nameClass = 'font-bold text-red-900';
        $yearClass = 'font-semibold text-red-800';
        $avatarBorder = 'border-yellow-400';
    } elseif ($generationLevel == 2) {
        // Gen 2: High Prestige
        $cardBg = 'bg-gradient-to-br from-yellow-50 to-amber-100';
        $borderColor = 'border-yellow-600';
        $borderWidth = 'border-[3px]';
        $topBorder = 'border-t-4 border-t-yellow-600';
        $ringClass = 'ring-2 ring-yellow-300';
        $shadowClass = 'shadow-lg shadow-yellow-700/30';
        $nameClass = 'font-bold text-yellow-900';
        $yearClass = 'font-semibold text-yellow-800';
        $avatarBorder = 'border-yellow-300';
    } elseif ($generationLevel == 3) {
        // Gen 3: Respected
        $cardBg = 'bg-gradient-to-br from-amber-50 to-yellow-50';
        $borderColor = 'border-amber-500';
        $borderWidth = 'border-2';
        $topBorder = 'border-t-[3px] border-t-amber-500';
        $ringClass = 'ring-1 ring-amber-200';
        $shadowClass = 'shadow-md shadow-amber-600/20';
        $nameClass = 'font-bold text-amber-900';
        $yearClass = 'font-medium text-amber-700';
        $avatarBorder = 'border-amber-200';
    } else {
        // Gen 4+: Gender-based colors
        $cardBg =
            $person->gender === 'male'
                ? 'bg-gradient-to-b from-blue-50 to-blue-100'
                : 'bg-gradient-to-b from-pink-50 to-pink-100';
        $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
        $borderWidth = 'border-2';
        $topBorder = $person->is_alive ? 'border-t-[3px] border-t-green-500' : 'border-t-[3px] border-t-gray-400';
        $ringClass = '';
        $shadowClass = 'shadow-md hover:shadow-lg';
        $nameClass = $person->gender === 'male' ? 'font-bold text-blue-900' : 'font-bold text-pink-900';
        $yearClass = 'font-semibold text-gray-700';
        $avatarBorder = $person->gender === 'male' ? 'border-blue-200' : 'border-pink-200';
    }

    // Apply filters
    $shouldShow = true;
    if (!$filters['showAlive'] && $person->is_alive) {
        $shouldShow = false;
    }
    if (!$filters['showDeceased'] && !$person->is_alive) {
        $shouldShow = false;
    }
    if (!$filters['showMale'] && $person->gender === 'male') {
        $shouldShow = false;
    }
    if (!$filters['showFemale'] && $person->gender === 'female') {
        $shouldShow = false;
    }

    // Status overlay
    $statusClass = $person->is_alive ? '' : 'grayscale-[20%] opacity-95';
    if (!$shouldShow) {
        $statusClass .= ' opacity-20 filter grayscale';
    }

    // Get spouses
    $spouses = $person->gender === 'male' ? $person->marriagesAsHusband : $person->marriagesAsWife;
@endphp

@if ($shouldShow)
    <div class="flex flex-col items-center relative group">
        {{-- Connector Point Top --}}
        <div
            class="w-2 h-2 bg-white border-2 border-gray-400 rounded-full absolute -top-1 z-20 group-hover:border-primary-500 group-hover:scale-125 transition-all">
        </div>

        {{-- Node Card Container --}}
        <div class="relative">
            {{-- Zoom Arrow Above (if has children) --}}
            @if ($person->children && $person->children->count() > 0)
                <button wire:click="focusOnPerson({{ $person->id }})"
                    class="absolute -top-10 left-1/2 -translate-x-1/2 w-7 h-7 bg-white rounded-full border-2 border-gray-300 shadow-md flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 z-10 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            @endif

            {{-- Card --}}
            <div class="w-28 {{ $cardBg }} {{ $borderWidth }} {{ $borderColor }} {{ $ringClass }} {{ $topBorder }} rounded-lg p-2 text-center {{ $shadowClass }} {{ $statusClass }} transition-all hover:scale-105 active:shadow-xl cursor-pointer relative"
                style="touch-action: auto;"
                @click="console.log('Node clicked: {{ $person->id }}'); $wire.selectPerson({{ $person->id }})"
                role="button" tabindex="0">

                {{-- Decorative Corner Ornaments for Gen 1 (Thủy Tổ) --}}
                @if ($generationLevel == 1)
                    <div
                        class="absolute -top-0.5 -left-0.5 w-3 h-3 border-t-2 border-l-2 border-yellow-500 rounded-tl-md">
                    </div>
                    <div
                        class="absolute -top-0.5 -right-0.5 w-3 h-3 border-t-2 border-r-2 border-yellow-500 rounded-tr-md">
                    </div>
                    <div
                        class="absolute -bottom-0.5 -left-0.5 w-3 h-3 border-b-2 border-l-2 border-yellow-500 rounded-bl-md">
                    </div>
                    <div
                        class="absolute -bottom-0.5 -right-0.5 w-3 h-3 border-b-2 border-r-2 border-yellow-500 rounded-br-md">
                    </div>
                @endif

                {{-- Status Indicator (top right) --}}
                @if (!$person->is_alive)
                    <div class="absolute top-1 right-1 w-1.5 h-1.5 bg-gray-500 rounded-full" title="Đã mất"></div>
                @endif

                {{-- Avatar --}}
                <div
                    class="w-12 h-12 mx-auto mb-1 rounded-full border-2 {{ $avatarBorder }} shadow-md overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 group-hover:scale-105 transition-transform">
                    @if ($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Name --}}
                <p class="{{ $nameClass }} text-xs truncate leading-tight"
                    style="text-shadow: 0 1px 2px rgba(255,255,255,0.8);">
                    {{ $person->name }}
                </p>

                {{-- Years --}}
                @if ($filters['showDates'] ?? true)
                    <p class="{{ $yearClass }} text-[10px] leading-tight mt-0.5">
                        {{ $person->birth_year ?? '?' }}-{{ $person->death_year ?? ($person->is_alive ? 'nay' : '?') }}
                    </p>
                @endif

                {{-- Generation Badge --}}
                @if ($person->generation_id)
                    <span
                        class="inline-block mt-1 px-1.5 py-0.5 bg-indigo-500/90 text-white text-[8px] font-bold rounded leading-none">
                        Đời {{ $person->generation_id }}
                    </span>
                @endif

                {{-- Hover Action Buttons --}}
                <div
                    class="absolute -bottom-2 left-1/2 -translate-x-1/2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-30">
                    <button
                        class="bg-indigo-500 text-white rounded-full p-0.5 shadow-md hover:bg-indigo-600 hover:scale-110 transition-all"
                        title="Tập trung"
                        wire:click.stop="$dispatch('focus-on-branch', { personId: {{ $person->id }} })">
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
                        wire:click.stop="$dispatch('open-add-modal', { parentId: {{ $person->id }} })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Spouses (if showing) --}}
            @if (($filters['showSpouses'] ?? true) && $spouses && $spouses->count() > 0)
                @foreach ($spouses as $marriage)
                    @php
                        $spouse = $person->gender === 'male' ? $marriage->wife : $marriage->husband;
                        if (!$spouse) {
                            continue;
                        }

                        $spouseBg =
                            $spouse->gender === 'male'
                                ? 'bg-gradient-to-br from-blue-50 to-blue-100'
                                : 'bg-gradient-to-br from-pink-50 to-pink-100';
                        $spouseBorder = $spouse->is_alive ? 'border-t-green-500' : 'border-t-gray-400';
                        $spouseNameColor = $spouse->gender === 'male' ? 'text-blue-900' : 'text-pink-900';
                    @endphp

                    <div class="mt-2 w-24 {{ $spouseBg }} border border-gray-300 {{ $spouseBorder }} border-t-[3px] rounded-md p-1.5 text-center shadow-sm {{ !$spouse->is_alive ? 'grayscale-[20%] opacity-95' : '' }} cursor-pointer hover:bg-opacity-80 transition-all"
                        wire:click.stop="selectPerson({{ $spouse->id }})">
                        <div
                            class="w-8 h-8 mx-auto mb-1 rounded-full border border-gray-200 shadow-sm overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
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
                        <p class="font-bold {{ $spouseNameColor }} text-[10px] truncate">{{ $spouse->name }}</p>
                        <p class="text-[9px] text-gray-600">{{ $spouse->birth_year ?? '?' }}</p>
                    </div>
                @endforeach
            @endif

            {{-- Plus Button Below --}}
            <button wire:click="$dispatch('open-add-modal', { parentId: {{ $person->id }} })"
                class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-6 h-6 bg-gray-700 hover:bg-gray-800 active:bg-gray-900 text-white rounded-full flex items-center justify-center shadow-md z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>

        {{-- Connector Point Bottom --}}
        <div class="w-2 h-2 bg-gray-900 rounded-full absolute -bottom-1 z-20"></div>

        {{-- Children Branch --}}
        @if ($person->children && $person->children->count() > 0)
            {{-- Vertical Connection Line Down --}}
            <div class="w-0.5 h-10 bg-gray-400 mt-1"></div>

            {{-- Children Container --}}
            <div class="flex flex-col items-center">
                {{-- Horizontal Line (if multiple children) --}}
                @if ($person->children->count() > 1)
                    <div class="relative h-0.5 bg-gray-400"
                        style="width: {{ ($person->children->count() - 1) * 144 + 56 }}px;">
                    </div>
                @endif

                {{-- Children Nodes --}}
                <div class="flex gap-4 mt-0">
                    @foreach ($person->children as $child)
                        {{-- Individual child with vertical connector --}}
                        <div class="flex flex-col items-center">
                            @if ($person->children->count() > 1)
                                {{-- Vertical line from horizontal bar to child --}}
                                <div class="w-0.5 h-10 bg-gray-400"></div>
                            @endif

                            {{-- Recursive child node --}}
                            @include('livewire.partials.mobile-tree-node', [
                                'person' => $child,
                                'filters' => $filters,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif
