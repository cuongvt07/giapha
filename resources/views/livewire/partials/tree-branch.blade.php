@props(['children', 'filters' => []])

<div class="flex flex-row justify-center pt-8 relative">

    @foreach ($children as $child)
        <div class="flex flex-col items-center relative px-4">

            <!-- Branch Drawing Logic (Lines) -->
            <!-- Premium Curved Connectors with Gradient -->
            @if ($children->count() > 1)
                <div class="absolute top-0 w-full h-8 pointer-events-none">

                    @if ($loop->first)
                        <!-- First Child: Curve from Right to Bottom ( ╭ ) -->
                        <div
                            class="absolute left-1/2 w-1/2 h-full border-t-[3px] border-l-[3px] border-primary-300 rounded-tl-3xl opacity-80 shadow-sm">
                        </div>
                    @elseif ($loop->last)
                        <!-- Last Child: Curve from Left to Bottom ( ╮ ) -->
                        <div
                            class="absolute right-1/2 w-1/2 h-full border-t-[3px] border-r-[3px] border-primary-300 rounded-tr-3xl opacity-80 shadow-sm">
                        </div>
                    @else
                        <!-- Middle Child: T-Shape ( ─┬─ ) -->
                        <div class="absolute top-0 w-full border-t-[3px] border-primary-300 opacity-80"></div>
                        <div class="absolute left-1/2 -translate-x-1/2 top-0 h-full w-[3px] bg-primary-300 opacity-80">
                        </div>
                    @endif

                </div>
            @else
                <!-- Single child: Straight vertical line -->
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 h-8 w-[2px] bg-gradient-to-b from-primary-300 to-primary-400 opacity-80 shadow-sm">
                </div>
            @endif

            <!-- The Node Itself -->
            <div class="relative z-10">
                @include('livewire.partials.node-card', ['person' => $child, 'filters' => $filters])
            </div>

            <!-- Recursion for next level -->
            @if ($child->children->isNotEmpty())
                <!-- Vertical Line Down from Node to Children -->
                <div class="w-[2px] h-8 bg-gradient-to-b from-primary-300 to-primary-400 opacity-80 shadow-sm"></div>

                @include('livewire.partials.tree-branch', [
                    'children' => $child->children,
                    'filters' => $filters,
                ])
            @endif

        </div>
    @endforeach

</div>
