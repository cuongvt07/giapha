@props(['children', 'filters' => [], 'generationLevel' => 1])

@php
    // Use passed generationLevel or default to 1
    $currentGeneration = $generationLevel;
    $nextGeneration = $currentGeneration + 1;
@endphp

{{-- STANDARD HORIZONTAL TREE LAYOUT --}}
<div class="flex flex-row justify-center pt-16 relative">

    @foreach ($children as $child)
        <div class="flex flex-col items-center relative px-4">

            <!-- The Node Itself -->
            <div class="relative z-10 pt-2">
                @include('livewire.partials.node-card', [
                    'person' => $child,
                    'filters' => $filters,
                    'generationLevel' => $currentGeneration,
                ])
            </div>

            <!-- Recursion for next level -->
            @if ($child->children->isNotEmpty())
                {{-- Spacer --}}
                <div class="h-16 w-full"></div>

                @include('livewire.partials.tree-branch', [
                    'children' => $child->children,
                    'filters' => $filters,
                    'generationLevel' => $nextGeneration,
                ])
            @endif

        </div>
    @endforeach

</div>
