{{-- Mobile Add Person Form --}}
<div class="flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Thêm thành viên mới</h2>
        <button wire:click="closeAddModal" class="p-2 hover:bg-gray-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Form --}}
    <form wire:submit="savePerson" class="p-4 space-y-4">
        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
            <input type="text" wire:model="newPersonName" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Nhập họ và tên">
        </div>

        {{-- Gender --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Giới tính *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2">
                    <input type="radio" wire:model="newPersonGender" value="male" class="text-blue-600">
                    <span class="text-sm">Nam</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" wire:model="newPersonGender" value="female" class="text-pink-600">
                    <span class="text-sm">Nữ</span>
                </label>
            </div>
        </div>

        {{-- Birth Year --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Năm sinh</label>
            <input type="number" wire:model="newPersonBirthYear" min="1800" max="2030"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="VD: 1990">
        </div>

        {{-- Is Alive --}}
        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model="newPersonIsAlive" class="rounded text-green-600">
                <span class="text-sm text-gray-700">Còn sống</span>
            </label>
        </div>

        {{-- Submit --}}
        <div class="pt-4">
            <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-lg active:bg-red-700">
                Thêm thành viên
            </button>
        </div>
    </form>
</div>
