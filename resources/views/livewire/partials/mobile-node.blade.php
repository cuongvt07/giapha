{{-- Mobile Node (Generation-aware layout) --}}
@php
    // Use passed generationLevel or default to 1
    $generationLevel = $generationLevel ?? 1;

    // Card sizes based on generation
    // Generation 1 (Thủy Tổ): Most Sacred
    if ($generationLevel == 1) {
        $cardWidth = 'w-48'; // Wider for Gen 1
        $avatarSize = 'w-16 h-16';
        $nameSize = 'text-base';
        $yearSize = 'text-xs';
        $cardBg = 'bg-gradient-to-br from-amber-50 via-yellow-100 to-amber-100'; // PC Gen 1 Bg
        $borderColor = 'border-red-700'; // PC Gen 1 Border
        $padding = 'p-3';
        $ringClass = 'ring-2 ring-yellow-400 ring-offset-1 ring-offset-red-800'; // PC Ring
    }
    // Generation 2: High Prestige
    elseif ($generationLevel == 2) {
        $cardWidth = 'w-40';
        $avatarSize = 'w-14 h-14';
        $nameSize = 'text-sm';
        $yearSize = 'text-[11px]';
        $cardBg = 'bg-gradient-to-br from-yellow-50 to-amber-100'; // PC Gen 2 Bg
        $borderColor = 'border-yellow-600'; // PC Gen 2 Border
        $padding = 'p-2.5';
        $ringClass = 'ring-1 ring-yellow-300';
    }
    // Generation 3: Respected
    elseif ($generationLevel == 3) {
        $cardWidth = 'w-32';
        $avatarSize = 'w-12 h-12';
        $nameSize = 'text-xs';
        $yearSize = 'text-[10px]';
        $cardBg = 'bg-gradient-to-br from-amber-50 to-yellow-50'; // PC Gen 3 Bg
        $borderColor = 'border-amber-500'; // PC Gen 3 Border
        $padding = 'p-2';
        $ringClass = '';
    }
    // Generation 4+: Standard (Vertical Text)
    else {
        // Gen 4+ Vertical Text
        $cardWidth = 'w-10'; // Narrow width for vertical text
        $avatarSize = 'w-8 h-8';
        $nameSize = 'text-[10px]';
        $yearSize = 'text-[9px]';
        // PC Gen 4+ Bg by Gender
        $cardBg =
            $person->gender === 'male'
                ? 'bg-gradient-to-b from-blue-50 to-blue-100'
                : 'bg-gradient-to-b from-pink-50 to-pink-100';
        // PC Gen 4+ Border by Gender
        $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
        $padding = 'py-2 px-1';
        $ringClass = '';
    }

    $topBorderColor =
        $generationLevel <= 3
            ? 'border-t-4' // Thicker top border for ancestors
            : ($person->is_alive
                ? 'border-t-green-500 border-t-[3px]'
                : 'border-t-gray-400 border-t-[3px]');

    // Specific top border colors for ancestors
    if ($generationLevel == 1) {
        $topBorderColor .= ' border-t-red-700';
    } elseif ($generationLevel == 2) {
        $topBorderColor .= ' border-t-yellow-600';
    } elseif ($generationLevel == 3) {
        $topBorderColor .= ' border-t-amber-500';
    }

    $childGeneration = $generationLevel + 1;
    $useVerticalText = $generationLevel >= 4;

@endphp

<div class="flex flex-col items-center">
    {{-- Person Card --}}
    <div id="node-{{ $person->id }}"
        @if ($person->father_id) data-parent-id="node-{{ $person->father_id }}" @endif
        @if ($person->mother_id && !$person->father_id) data-parent-id="node-{{ $person->mother_id }}" @endif
        class="{{ $cardWidth }} {{ $padding }} rounded-xl {{ $cardBg }} border-2 {{ $borderColor }} {{ $topBorderColor }} {{ $ringClass ?? '' }} shadow-lg text-center cursor-pointer active:scale-95 transition-all relative z-10 flex flex-col items-center"
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
