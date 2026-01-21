{{-- Mobile Add Person Form --}}
<div class="flex flex-col">
    {{-- Header --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">
            {{ $editingPersonId ? 'Chỉnh sửa thông tin' : 'Thêm thành viên mới' }}
        </h2>
        <button wire:click="closeAddModal" class="p-2 hover:bg-gray-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Form --}}
    <form wire:submit="savePerson" class="p-4 space-y-6">

        {{-- Avatar Section --}}
        <div class="flex flex-col items-center">
            <div class="relative w-24 h-24 mb-3">
                @if ($newPersonAvatar)
                    <img src="{{ $newPersonAvatar->temporaryUrl() }}"
                        class="w-full h-full rounded-full object-cover border-2 border-gray-200">
                @elseif ($existingAvatarUrl)
                    <img src="{{ $existingAvatarUrl }}"
                        class="w-full h-full rounded-full object-cover border-2 border-gray-200">
                @else
                    <div
                        class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200 border-dashed">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif

                <label
                    class="absolute bottom-0 right-0 bg-blue-600 text-white p-1.5 rounded-full shadow-lg cursor-pointer active:scale-95 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    </svg>
                    <input type="file" wire:model="newPersonAvatar" class="hidden" accept="image/*">
                </label>
            </div>
            <div wire:loading wire:target="newPersonAvatar" class="text-xs text-blue-600 font-medium mb-2">Đang tải ảnh
                lên...</div>
        </div>

        {{-- Basic Info --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-gray-900 border-b pb-1">Thông tin cơ bản</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                <input type="text" wire:model="newPersonName" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Nhập họ và tên">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giới tính *</label>
                    <select wire:model="newPersonGender"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Năm sinh</label>
                    <input type="number" wire:model="newPersonBirthYear"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="VD: 1990">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên tự / Biệt danh</label>
                    <input type="text" wire:model="newPersonNickname"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chức vụ / Học vị</label>
                    <input type="text" wire:model="newPersonTitle"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nghề nghiệp</label>
                <input type="text" wire:model="newPersonOccupation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
        </div>

        {{-- Location Info --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-gray-900 border-b pb-1">Địa chỉ & Quê quán</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quê quán</label>
                    <input type="text" wire:model="newPersonHometown"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nơi sinh</label>
                    <input type="text" wire:model="newPersonPlaceOfBirth"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ hiện tại</label>
                <input type="text" wire:model="newPersonAddress"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-gray-900 border-b pb-1">Liên hệ</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Điện thoại</label>
                    <input type="tel" wire:model="newPersonPhone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="newPersonEmail"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
        </div>

        {{-- Status & Death --}}
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-gray-900 border-b pb-1">Trạng thái</h3>
            <div>
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <input type="checkbox" wire:model.live="newPersonIsAlive"
                        class="w-5 h-5 rounded text-green-600 focus:ring-green-500">
                    <span class="text-base font-medium text-gray-900">Người này còn sống</span>
                </label>
            </div>

            @if (!$newPersonIsAlive)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4 animate-fade-in-down">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Năm mất</label>
                            <input type="number" wire:model="newPersonDeathYear"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-gray-500"
                                placeholder="VD: 2024">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày mất (DL/AL)</label>
                            <input type="date" wire:model="newPersonBurialDate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-gray-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nơi an táng</label>
                        <input type="text" wire:model="newPersonBurialPlace"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-gray-500"
                            placeholder="Nghĩa trang, địa chỉ...">
                    </div>

                    {{-- Grave Photo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh mộ phần</label>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 flex-shrink-0">
                                @if ($newPersonGravePhoto)
                                    <img src="{{ $newPersonGravePhoto->temporaryUrl() }}"
                                        class="w-full h-full object-cover">
                                @elseif ($existingGravePhotoUrl)
                                    <img src="{{ $existingGravePhotoUrl }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <label
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 shadow-sm cursor-pointer hover:bg-gray-50">
                                Chọn ảnh mộ
                                <input type="file" wire:model="newPersonGravePhoto" class="hidden"
                                    accept="image/*">
                            </label>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <div class="pt-4 pb-8">
            <button type="submit"
                class="w-full py-3.5 bg-red-600 text-white font-bold rounded-xl shadow-lg active:bg-red-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span wire:loading.remove
                    wire:target="savePerson">{{ $editingPersonId ? 'Lưu thay đổi' : 'Thêm thành viên' }}</span>
                <span wire:loading wire:target="savePerson">Đang lưu...</span>
            </button>
        </div>
    </form>
</div>
