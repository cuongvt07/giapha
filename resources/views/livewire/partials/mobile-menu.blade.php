{{-- Mobile Drawer Menu --}}
<div class="h-full flex flex-col">
    {{-- Header --}}
    <div class="flex-shrink-0 p-4 bg-red-600 text-white">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold">Gia Phả Việt</h2>
            <button wire:click="closeMenu" class="p-1 hover:bg-white/20 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">Thống kê</h3>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-red-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-red-600">{{ \App\Models\Person::count() }}</p>
                <p class="text-xs text-gray-600">Thành viên</p>
            </div>
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-green-600">{{ \App\Models\Person::where('is_alive', true)->count() }}
                </p>
                <p class="text-xs text-gray-600">Còn sống</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="p-4 flex-1 overflow-y-auto">
        <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">Bộ lọc</h3>

        <div class="space-y-3">
            <label class="flex items-center gap-3">
                <input type="checkbox" wire:model.live="filters.showAlive" class="rounded text-red-600">
                <span class="text-sm text-gray-700">Hiển thị người còn sống</span>
            </label>
            <label class="flex items-center gap-3">
                <input type="checkbox" wire:model.live="filters.showDeceased" class="rounded text-red-600">
                <span class="text-sm text-gray-700">Hiển thị người đã mất</span>
            </label>
            <label class="flex items-center gap-3">
                <input type="checkbox" wire:model.live="filters.showMale" class="rounded text-blue-600">
                <span class="text-sm text-gray-700">Hiển thị nam</span>
            </label>
            <label class="flex items-center gap-3">
                <input type="checkbox" wire:model.live="filters.showFemale" class="rounded text-pink-600">
                <span class="text-sm text-gray-700">Hiển thị nữ</span>
            </label>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex-shrink-0 p-4 border-t border-gray-200">
        <button wire:click="openAddModal"
            class="w-full py-3 bg-red-600 text-white font-bold rounded-lg active:bg-red-700">
            + Thêm thành viên mới
        </button>
    </div>
</div>
