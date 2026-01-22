{{-- Mobile Node (Generation-aware layout) --}}
@php
    // Default values to prevent undefined variable errors
    $generationLevel = $generationLevel ?? 1;
    $cardWidth = 'w-24';
    $avatarSize = 'w-10 h-10';
    $nameSize = 'text-xs';
    $yearSize = 'text-[10px]';
    $cardBg = 'bg-white';
    $borderColor = 'border-gray-300';
    $topBorderColor = 'border-t-gray-400';
    $padding = 'p-2';
    $ringClass = '';
    $useVerticalText = false;

    // Apply Logic based on Generation
    // Logic ported from Desktop node-card.blade.php but sized for Mobile
    
    $useVerticalText = false;
    $customStyle = "";
    $bgOverride = "";
    
    // Gen 1: nencuto.png - Red - Gold (Reverted to Gold)
    if ($generationLevel == 1) {
        $cardWidth = 'w-80'; 
        $avatarSize = 'w-20 h-20';
        $nameSize = 'text-2xl font-black uppercase text-[#FFD700]';
        $yearSize = 'text-sm font-bold text-[#FFA000]';
        
        $borderColor = 'border-transparent';
        $topBorderColor = 'border-t-0';
        $padding = 'pt-10 pb-8 px-8';
        $ringClass = '';
        
        // Red #8B0000, nencuto frame
        $bgOverride = 'bg-no-repeat bg-[length:100%_100%]';
        $customStyle = "background-image: url('" . asset('images/nencuto.png') . "'); background-color: #8B0000;";
        
    } elseif ($generationLevel == 2) {
        // Gen 2: nento1.png - Red - Gold (Reverted to Gold)
        $cardWidth = 'w-72'; 
        $avatarSize = 'w-16 h-16';
        $nameSize = 'text-xl font-black uppercase text-[#FFD700]';
        $yearSize = 'text-xs font-bold text-[#FFA000]';
        
        $borderColor = 'border-transparent';
        $topBorderColor = 'border-t-0';
        $padding = 'pt-8 pb-6 px-6';
        $ringClass = '';
        
        $bgOverride = 'bg-no-repeat bg-[length:100%_100%]';
        $customStyle = "background-image: url('" . asset('images/nento1.png') . "'); background-color: #8B0000;";

    } elseif ($generationLevel >= 3 && $generationLevel <= 5) {
        // Gen 3-5: nendoi.png - Yellow/Pink - Black Text
        $cardWidth = 'w-64'; 
        $avatarSize = 'w-14 h-14';
        
        // Gender Colors
        if ($person->gender === 'female') {
            $bgColor = '#FFB6C1'; 
            $textColor = '#000000'; // Black
        } else {
            $bgColor = '#FFCC00'; 
            $textColor = '#000000'; // Black
        }
        
        $nameSize = "text-lg font-black uppercase text-[{$textColor}]";
        $yearSize = "text-xs font-bold text-gray-800";
        
        $borderColor = 'border-transparent';
        $topBorderColor = 'border-t-0';
        $padding = 'pt-6 pb-4 px-6';
        $ringClass = '';
        
        $bgOverride = 'bg-no-repeat bg-[length:100%_100%]';
        $customStyle = "background-image: url('" . asset('images/nendoi.png') . "'); background-color: {$bgColor};";

    } else {
        // Gen 6+: Check Siblings Check
        // Mobile might not query deeply, but let's try basic count if relation loaded
        $siblingsCount = 0;
        if ($person->father_id) {
             // In recursive views, direct query might be heavy, but necessary for logic match. 
             // Optimized: Assuming parent relation available or using lighter check? 
             // We'll stick to query for consistency with desktop request.
             $siblingsCount = \App\Models\Person::where('father_id', $person->father_id)->count();
        } elseif ($person->mother_id) {
             $siblingsCount = \App\Models\Person::where('mother_id', $person->mother_id)->count();
        }
        
        // Force Vertical if Gen 7+ OR Crowded Gen 6
        if ($generationLevel >= 7 || $siblingsCount >= 4) {
            $useVerticalText = true;
            $cardWidth = 'w-12';
            $avatarSize = 'w-8 h-8';
            $yearSize = 'text-[9px] text-gray-500';
    
            $isMale = $person->gender === 'male';
            
            // Match Desktop Vertical Text Colors
            $nameColor = $isMale ? 'text-blue-900' : 'text-pink-900';
            $nameSize = "text-[10px] uppercase font-bold $nameColor";
            
            $cardBg = $isMale ? 'bg-gradient-to-b from-blue-50 to-blue-100' : 'bg-gradient-to-b from-pink-50 to-pink-100';
            $borderColor = $isMale ? 'border-blue-400' : 'border-pink-400';
            $padding = 'py-2 px-1';
            $ringClass = '';
            $topBorderColor = $person->is_alive ? 'border-t-green-500 border-t-[3px]' : 'border-t-gray-400 border-t-[3px]';
            
            // Override frame vars
            $bgOverride = $cardBg; 
            // No custom style for vertical default
        } else {
            // Gen 6 Sparse -> Horizontal Nendoi Style (Small)
            $cardWidth = 'w-56'; 
            $avatarSize = 'w-12 h-12';
            
            if ($person->gender === 'female') {
                $bgColor = '#FFB6C1'; $textColor = '#000000'; // Black
            } else {
                $bgColor = '#FFCC00'; $textColor = '#000000'; // Black
            }
            
            $nameSize = "text-sm font-black uppercase text-[{$textColor}]";
            $yearSize = "text-[10px] font-bold text-gray-800";
            
            $borderColor = 'border-transparent';
            $topBorderColor = 'border-t-0';
            $padding = 'pt-5 pb-3 px-4';
            $ringClass = '';
            
            $bgOverride = 'bg-no-repeat bg-[length:100%_100%]';
            $customStyle = "background-image: url('" . asset('images/nendoi.png') . "'); background-color: {$bgColor};";
        }
    }

    $childGeneration = $generationLevel + 1;

@endphp

<div class="flex flex-col items-center">
    {{-- Person Card --}}
    <div id="node-{{ $person->id }}"
        @if ($person->father_id) data-parent-id="node-{{ $person->father_id }}" @endif
        @if ($person->mother_id && !$person->father_id) data-parent-id="node-{{ $person->mother_id }}" @endif
        class="{{ $cardWidth }} {{ $padding }} rounded-xl {{ $bgOverride }} border-2 {{ $borderColor }} {{ $topBorderColor }} {{ $ringClass ?? '' }} shadow-lg text-center cursor-pointer active:scale-95 transition-all relative z-10 flex flex-col items-center"
        style="{{ $customStyle ?? '' }}"
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
            <p class="{{ $nameSize }} leading-tight">
                {{ $person->name }}
            </p>
            <p class="{{ $yearSize }} mt-0.5">
                {{ $person->birth_year ?? '?' }}{{ $person->death_year ? ' - ' . $person->death_year : ($person->is_alive ? '' : ' - ?') }}
            </p>
        @else
            {{-- Vertical Text (Gen 4+) --}}
            <div class="{{ $nameSize }} leading-none whitespace-nowrap mt-1"
                style="writing-mode: vertical-rl; text-orientation: mixed; text-shadow: 0 1px 1px rgba(255,255,255,0.8);">
                {{ $person->name }}
            </div>
            <div class="{{ $yearSize }} font-medium leading-none mt-1"
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
