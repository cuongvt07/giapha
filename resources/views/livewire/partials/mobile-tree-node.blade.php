{{-- Mobile Horizontal Tree Node (Recursive) --}}
@php
    $shouldShow = true;
    
    // Apply filters
    if (!$filters['showAlive'] && $person->is_alive) $shouldShow = false;
    if (!$filters['showDeceased'] && !$person->is_alive) $shouldShow = false;
    if (!$filters['showMale'] && $person->gender === 'male') $shouldShow = false;
    if (!$filters['showFemale'] && $person->gender === 'female') $shouldShow = false;
    
    $bgColor = $person->gender === 'male' ? 'bg-blue-50' : 'bg-pink-50';
    $borderColor = $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400';
    $avatarBg = $person->gender === 'male' ? 'bg-blue-300' : 'bg-pink-300';
@endphp

@if ($shouldShow)
    <div class="flex flex-col items-center relative">
        {{-- Node Card Container --}}
        <div class="relative">
            {{-- Zoom Arrow Above (if has children) --}}
            @if ($person->children && $person->children->count() > 0)
                <button wire:click="focusOnPerson({{ $person->id }})"
                        class="absolute -top-10 left-1/2 -translate-x-1/2 w-7 h-7 bg-white rounded-full border-2 border-gray-300 shadow-md flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            @endif
            
            {{-- Card --}}
            <div class="w-28 {{ $bgColor }} border-2 {{ $borderColor }} rounded-lg p-2 text-center shadow-md active:shadow-lg transition-shadow"
                 wire:click="$dispatch('person-selected', { id: {{ $person->id }} })"
                 role="button"
                 tabindex="0">
                 
                {{-- Avatar --}}
                <div class="w-12 h-12 mx-auto mb-1 rounded-full {{ $avatarBg }} overflow-hidden border-2 border-white shadow-sm">
                    @if ($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-full h-full text-white p-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    @endif
                </div>
                
                {{-- Name --}}
                <p class="text-xs font-bold text-gray-900 truncate leading-tight">
                    {{ $person->name }}
                </p>
                
                {{-- Years --}}
                <p class="text-[10px] text-gray-600 leading-tight mt-0.5">
                    @if ($filters['showDates'])
                        {{ $person->birth_year ?? '?' }}-{{ $person->death_year ?? ($person->is_alive ? '' : '?') }}
                    @endif
                </p>
            </div>
            
            {{-- Plus Button Below (always show for adding children) --}}
            <button wire:click="$dispatch('open-add-modal', { parentId: {{ $person->id }} })"
                    class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-6 h-6 bg-gray-700 hover:bg-gray-800 active:bg-gray-900 text-white rounded-full flex items-center justify-center shadow-md z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
        
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
