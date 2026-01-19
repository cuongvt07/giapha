{{-- Mobile Node (Generation-aware layout) --}}
@php
    // Use passed generationLevel or default to 1
    $generationLevel = $generationLevel ?? 1;

    // Card sizes based on generation
    if ($generationLevel == 1) {
        $cardWidth = 'w-36';
        $avatarSize = 'w-14 h-14';
        $nameSize = 'text-sm';
        $yearSize = 'text-xs';
        $cardBg = 'bg-gradient-to-br from-amber-100 to-yellow-200';
        $borderColor = 'border-red-600';
        $padding = 'p-3';
    } elseif ($generationLevel <= 3) {
        $cardWidth = 'w-28';
        $avatarSize = 'w-11 h-11';
        $nameSize = 'text-xs';
        $yearSize = 'text-[11px]';
        $cardBg =
            $person->gender === 'male'
                ? 'bg-gradient-to-br from-blue-50 to-blue-100'
                : 'bg-gradient-to-br from-pink-50 to-pink-100';
        $borderColor = $person->gender === 'male' ? 'border-blue-500' : 'border-pink-500';
        $padding = 'p-2';
    } else {
        $cardWidth = 'w-24';
        $avatarSize = 'w-9 h-9';
        $nameSize = 'text-[10px]';
        $yearSize = 'text-[9px]';
        $cardBg = $person->gender === 'male' ? 'bg-blue-50' : 'bg-pink-50';
        $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
        $padding = 'p-1.5';
    }

    $topBorderColor = $person->is_alive ? 'border-t-green-500' : 'border-t-gray-400';

    // Layout direction: horizontal for gen 1-2, vertical for gen 3+ (in terms of tree structure? No, user said text layout)
    // Actually, user said "từ đời 4 đổ đi phải dạng dọc như bên PC".
    // PC uses vertical TEXT for Gen 4+.
    $useVerticalText = $generationLevel >= 4;

    if ($generationLevel == 1) {
        $cardWidth = 'w-36';
        $avatarSize = 'w-14 h-14';
        $nameSize = 'text-sm';
        $yearSize = 'text-xs';
        $cardBg = 'bg-gradient-to-br from-amber-100 to-yellow-200';
        $borderColor = 'border-red-600';
        $padding = 'p-3';
    } elseif ($generationLevel <= 3) {
        $cardWidth = 'w-28';
        $avatarSize = 'w-11 h-11';
        $nameSize = 'text-xs';
        $yearSize = 'text-[11px]';
        $cardBg =
            $person->gender === 'male'
                ? 'bg-gradient-to-br from-blue-50 to-blue-100'
                : 'bg-gradient-to-br from-pink-50 to-pink-100';
        $borderColor = $person->gender === 'male' ? 'border-blue-500' : 'border-pink-500';
        $padding = 'p-2';
    } else {
        // Gen 4+ Vertical Text
        $cardWidth = 'w-10'; // Narrow width for vertical text
        $avatarSize = 'w-8 h-8';
        $nameSize = 'text-[10px]';
        $yearSize = 'text-[9px]';
        $cardBg = $person->gender === 'male' ? 'bg-blue-50' : 'bg-pink-50';
        $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
        $padding = 'py-2 px-1';
    }

    $topBorderColor = $person->is_alive ? 'border-t-green-500' : 'border-t-gray-400';
    $childGeneration = $generationLevel + 1;
@endphp

<div class="flex flex-col items-center">
    {{-- Person Card --}}
    <div id="node-{{ $person->id }}"
        @if ($person->father_id) data-parent-id="node-{{ $person->father_id }}" @endif
        @if ($person->mother_id && !$person->father_id) data-parent-id="node-{{ $person->mother_id }}" @endif
        class="{{ $cardWidth }} {{ $padding }} rounded-xl {{ $cardBg }} border-2 {{ $borderColor }} {{ $topBorderColor }} border-t-4 shadow-lg text-center cursor-pointer active:scale-95 transition-all relative z-10 flex flex-col items-center"
        wire:click="selectPerson({{ $person->id }})">

        {{-- Generation Badge --}}
        @if ($generationLevel == 1)
            <div
                class="absolute -top-3 left-1/2 -translate-x-1/2 bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow">
                ĐỜI {{ $generationLevel }}
            </div>
        @elseif ($generationLevel <= 4)
            <div
                class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full">
                Đ{{ $generationLevel }}
            </div>
        @endif

        {{-- Avatar --}}
        <div
            class="{{ $avatarSize }} mx-auto mb-1 rounded-full border-2 border-white shadow overflow-hidden bg-gray-200 flex-shrink-0">
            @if ($person->avatar_url)
                <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
            @else
                <div
                    class="w-full h-full flex items-center justify-center {{ $person->gender === 'male' ? 'text-blue-300' : 'text-pink-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-1/2 w-1/2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </div>

        @if (!$useVerticalText)
            {{-- Normal Horizontal Text (Gen 1-3) --}}
            <p class="{{ $nameSize }} font-bold text-gray-800 leading-tight">
                {{ $person->name }}
            </p>
            <p class="{{ $yearSize }} text-gray-500 mt-0.5">
                {{ $person->birth_year ?? '?' }}{{ $person->death_year ? ' - ' . $person->death_year : ($person->is_alive ? '' : ' - ?') }}
            </p>
        @else
            {{-- Vertical Text (Gen 4+) --}}
            <div class="{{ $nameSize }} font-bold text-gray-800 leading-none whitespace-nowrap mt-1"
                style="writing-mode: vertical-rl; text-orientation: mixed; text-shadow: 0 1px 1px rgba(255,255,255,0.8);">
                {{ $person->name }}
            </div>
            <div class="{{ $yearSize }} text-gray-500 font-medium leading-none mt-1"
                style="writing-mode: vertical-rl; text-orientation: mixed;">
                {{ $person->birth_year ?? '?' }}
            </div>
        @endif
    </div>

    {{-- Children --}}
    @if ($person->children && $person->children->count() > 0)
        {{-- Spacer instead of vertical line --}}
        <div class="h-16 w-full"></div>

        {{-- Children nodes side by side --}}
        <div class="flex {{ $generationLevel == 1 ? 'gap-6' : 'gap-3' }}">
            @foreach ($person->children as $child)
                <div class="flex flex-col items-center">
                    @include('livewire.partials.mobile-node', [
                        'person' => $child,
                        'generationLevel' => $childGeneration,
                    ])
                </div>
            @endforeach
        </div>
    @endif
</div>
