{{-- Vertical Tree Node (Recursive) --}}
@php
    $shouldShow = true;
    
    // Apply filters
    if (!$filters['showAlive'] && $person->is_alive) $shouldShow = false;
    if (!$filters['showDeceased'] && !$person->is_alive) $shouldShow = false;
    if (!$filters['showMale'] && $person->gender === 'male') $shouldShow = false;
    if (!$filters['showFemale'] && $person->gender === 'female') $shouldShow = false;
    
    $borderColor = $person->gender === 'male' ? '#3B82F6' : '#EC4899';
    $bgColor = $person->gender === 'male' ? '#EFF6FF' : '#FDF2F8';
    $statusColor = $person->is_alive ? '#22C55E' : '#6B7280';
@endphp

@if ($shouldShow)
    <div class="space-y-3" style="padding-left: {{ $depth * 16 }}px;">
        {{-- Person Card --}}
        <div class="bg-white rounded-xl shadow-md border-l-4 overflow-hidden active:shadow-lg transition-shadow"
             style="border-color: {{ $borderColor }}">
            
            {{-- Card Content --}}
            <div class="p-4"
                 wire:click="$dispatch('person-selected', { id: {{ $person->id }} })"
                 role="button"
                 tabindex="0">
                <div class="flex items-start gap-3">
                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 ring-2 ring-white shadow-md"
                             style="background: {{ $bgColor }}">
                            @if ($person->avatar_url)
                                <img src="{{ $person->avatar_url }}" 
                                     alt="{{ $person->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl font-bold"
                                     style="color: {{ $borderColor }}">
                                    {{ mb_substr($person->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        {{-- Status Indicator --}}
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-sm"
                             style="background-color: {{ $statusColor }}"
                             title="{{ $person->is_alive ? 'Còn sống' : 'Đã mất' }}">
                            @if (!$person->is_alive)
                                <span class="text-white text-xs flex items-center justify-center h-full">🕊️</span>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 text-base mb-1 truncate">
                            {{ $person->name }}
                        </h3>
                        
                        @if ($filters['showDates'])
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="inline-flex items-center gap-1">
                                    🎂 {{ $person->birth_year ?? '?' }}
                                    @if (!$person->is_alive)
                                        - 🕊️ {{ $person->death_year ?? '?' }}
                                    @endif
                                </span>
                            </p>
                        @endif

                        <div class="flex flex-wrap gap-2 text-xs">
                            {{-- Gender Badge --}}
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-medium"
                                  style="background: {{ $bgColor }}; color: {{ $borderColor }}">
                                {{ $person->gender === 'male' ? '👨 Nam' : '👩 Nữ' }}
                            </span>

                            {{-- Generation Badge --}}
                            @if ($person->generation)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full font-medium">
                                    🧬 Đời {{ $person->generation }}
                                </span>
                            @endif

                            {{-- Branch Badge --}}
                            @if ($person->familyBranch)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full font-medium">
                                    🏘️ {{ $person->familyBranch->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        @if ($filters['showTitles'] && $person->title)
                            <p class="text-xs text-gray-500 mt-1 italic">
                                {{ $person->title }}
                            </p>
                        @endif
                    </div>

                    {{-- Arrow --}}
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Spouses (if enabled) --}}
            @if ($filters['showSpouses'])
                @php
                    $marriages = $person->gender === 'male' 
                        ? $person->marriagesAsHusband 
                        : $person->marriagesAsWife;
                @endphp
                
                @if ($marriages && $marriages->count() > 0)
                    <div class="border-t border-gray-100 bg-gradient-to-r from-pink-50/50 to-blue-50/50 px-4 py-2">
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-500"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="font-medium">
                                @foreach ($marriages as $index => $marriage)
                                    @php
                                        $spouse = $person->gender === 'male' ? $marriage->wife : $marriage->husband;
                                    @endphp
                                    @if ($spouse)
                                        {{ $spouse->name }}@if (!$loop->last), @endif
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Children Count & Actions --}}
            @if ($person->children && $person->children->count() > 0)
                <div class="border-t border-gray-100 bg-gray-50 px-4 py-2 flex items-center justify-between">
                    <div class="text-xs text-gray-600 font-medium flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ $person->children->count() }} con
                    </div>
                    
                    <button wire:click.stop="focusOnPerson({{ $person->id }})"
                            class="text-xs text-blue-600 font-medium hover:text-blue-800">
                        Xem nhánh →
                    </button>
                </div>
            @endif
        </div>

        {{-- Children (Recursive) --}}
        @if ($person->children && $person->children->isNotEmpty())
            @foreach ($person->children as $child)
                @include('livewire.partials.vertical-tree-node', [
                    'person' => $child,
                    'depth' => $depth + 1,
                    'filters' => $filters,
                ])
            @endforeach
        @endif
    </div>
@endif
