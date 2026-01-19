{{-- Mobile Bottom Sheet for Person Details --}}
<div class="flex flex-col">
    {{-- Handle --}}
    <div class="flex justify-center py-2">
        <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
    </div>

    {{-- Header --}}
    <div class="flex items-center gap-4 px-4 pb-4 border-b border-gray-200">
        {{-- Avatar --}}
        <div
            class="w-16 h-16 rounded-full border-2 {{ $person->gender === 'male' ? 'border-blue-400' : 'border-pink-400' }} overflow-hidden bg-gray-200 flex-shrink-0">
            @if ($person->avatar_url)
                <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-gray-900 truncate">{{ $person->name }}</h2>
            <p class="text-sm text-gray-500">
                {{ $person->birth_year ?? '?' }} - {{ $person->death_year ?? ($person->is_alive ? 'nay' : '?') }}
            </p>
            <div class="flex items-center gap-2 mt-1">
                @if ($person->is_alive)
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Còn
                        sống</span>
                @else
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Đã mất</span>
                @endif
                <span
                    class="px-2 py-0.5 {{ $person->gender === 'male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} text-xs font-medium rounded-full">
                    {{ $person->gender === 'male' ? 'Nam' : 'Nữ' }}
                </span>
            </div>
        </div>

        {{-- Close --}}
        <button wire:click="closeBottomSheet" class="p-2 hover:bg-gray-100 rounded-full flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="p-4 overflow-y-auto max-h-[50vh]">
        {{-- Family Info --}}
        <div class="space-y-3">
            @if ($person->father)
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 text-sm w-16">Cha:</span>
                    <button wire:click="selectPerson({{ $person->father->id }})"
                        class="text-blue-600 font-medium text-sm">
                        {{ $person->father->name }}
                    </button>
                </div>
            @endif

            @if ($person->mother)
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 text-sm w-16">Mẹ:</span>
                    <button wire:click="selectPerson({{ $person->mother->id }})"
                        class="text-pink-600 font-medium text-sm">
                        {{ $person->mother->name }}
                    </button>
                </div>
            @endif

            @if ($person->spouses && $person->spouses->count() > 0)
                <div class="flex items-start gap-3">
                    <span class="text-gray-500 text-sm w-16">Vợ/Chồng:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($person->spouses as $spouse)
                            <button wire:click="selectPerson({{ $spouse->id }})"
                                class="text-purple-600 font-medium text-sm">
                                {{ $spouse->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($person->children && $person->children->count() > 0)
                <div class="flex items-start gap-3">
                    <span class="text-gray-500 text-sm w-16">Con:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($person->children as $child)
                            <button wire:click="selectPerson({{ $child->id }})"
                                class="{{ $child->gender === 'male' ? 'text-blue-600' : 'text-pink-600' }} font-medium text-sm">
                                {{ $child->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="p-4 border-t border-gray-200 flex gap-3">
        <button @click="$dispatch('center-on-node', { nodeId: 'node-{{ $person->id }}' }); $wire.closeBottomSheet()"
            class="flex-1 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg active:bg-gray-200">
            Xem nhánh
        </button>
        <button wire:click="openAddModal({{ $person->id }})"
            class="flex-1 py-3 bg-red-600 text-white font-medium rounded-lg active:bg-red-700">
            Thêm con
        </button>
        <button wire:click="deletePerson({{ $person->id }})"
            wire:confirm="Bạn có chắc muốn xóa người này? Nếu xóa, toàn bộ nhánh con cháu cũng có thể bị ảnh hưởng."
            class="px-4 py-3 bg-white border border-gray-300 text-red-600 font-medium rounded-lg active:bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
</div>
