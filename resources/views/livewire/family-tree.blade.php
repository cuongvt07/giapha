<div class="relative w-full h-full">
    {{-- MOBILE: Horizontal Tree with Touch Pan/Zoom --}}
    <div class="lg:hidden w-full h-full" x-data="{
        scale: 0.7,
        panning: false,
        pointX: 0,
        pointY: 0,
        startX: 0,
        startY: 0,
        touchStartX: 0,
        touchStartY: 0,
        
        init() {
            // Initial position: center horizontally, top with margin
            this.pointX = window.innerWidth / 2;
            this.pointY = 120;
        },
        
        handleTouchStart(e) {
            if (e.touches.length === 1) {
                this.panning = true;
                this.touchStartX = e.touches[0].clientX;
                this.touchStartY = e.touches[0].clientY;
                this.startX = this.touchStartX - this.pointX;
                this.startY = this.touchStartY - this.pointY;
            }
        },
        
        handleTouchMove(e) {
            if (!this.panning) return;
            e.preventDefault();
            const touch = e.touches[0];
            this.pointX = touch.clientX - this.startX;
            this.pointY = touch.clientY - this.startY;
        },
        
        handleTouchEnd(e) {
            this.panning = false;
        },
        
        zoomIn() {
            this.scale = Math.min(this.scale * 1.2, 2);
        },
        
        zoomOut() {
            this.scale = Math.max(this.scale / 1.2, 0.3);
        },
        
        resetView() {
            this.scale = 0.7;
            this.pointX = window.innerWidth / 2;
            this.pointY = 120;
        }
    }">
        
        {{-- Mobile Header --}}
        <div class="sticky top-0 z-30 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between px-4 py-3">
                {{-- Hamburger --}}
                <button wire:click="$dispatch('toggle-sidebar')" 
                        class="p-2 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                {{-- Title (Centered) --}}
                <h1 class="text-base font-bold text-gray-900 absolute left-1/2 -translate-x-1/2 pointer-events-none">
                    Cây Gia Phả
                </h1>
                
                {{-- Right Actions --}}
                <div class="flex items-center gap-1">
                    <button wire:click="$dispatch('toggle-user-menu')"
                            class="p-2 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                    <button wire:click="$dispatch('toggle-search')"
                            class="p-2 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Tree Canvas --}}
        <div class="w-full h-full relative overflow-hidden bg-gray-50"
             @touchstart="handleTouchStart($event)"
             @touchmove="handleTouchMove($event)"
             @touchend="handleTouchEnd($event)">
             
            {{-- Tree Content with Transform --}}
            <div class="absolute origin-top-left transition-transform duration-75 ease-out will-change-transform"
                 :style="`transform: translate(${pointX}px, ${pointY}px) scale(${scale});`">
                @if ($rootPerson)
                    <div class="flex justify-center pt-8">
                        @include('livewire.partials.mobile-tree-node', [
                            'person' => $rootPerson,
                            'filters' => $filters,
                        ])
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="flex items-center justify-center h-screen">
                        <div class="text-center px-6">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">Chưa có dữ liệu</h3>
                            <p class="text-sm text-gray-500 mb-6">Bắt đầu xây dựng cây gia phả</p>
                            <button wire:click="$dispatch('open-add-modal')"
                                    class="px-6 py-3 bg-blue-500 text-white rounded-full font-medium shadow-lg active:scale-95 transition-transform">
                                Thêm người đầu tiên
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- DESKTOP: Horizontal Tree with Pan/Zoom --}}
    <div class="hidden lg:block w-full h-full" x-data="{
        scale: 0.5,
        panning: false,
        pointX: 0,
        pointY: 0,
        startX: 0,
        startY: 0,
    
        init() {
            // Initial positioning: Center of viewport
            this.pointX = window.innerWidth / 2;
            this.pointY = 100;
        },
        setPanning(e) {
            // Only left mouse button (0)
            if (e.button !== 0) return;
            this.panning = true;
            this.startX = e.clientX - this.pointX;
            this.startY = e.clientY - this.pointY;
            e.currentTarget.style.cursor = 'grabbing';
        },
        releasePanning(e) {
            this.panning = false;
            e.currentTarget.style.cursor = 'grab';
        },
        pan(e) {
            if (!this.panning) return;
            e.preventDefault();
            this.pointX = e.clientX - this.startX;
            this.pointY = e.clientY - this.startY;
        },
        zoom(e) {
            if (e.ctrlKey || e.metaKey || e.deltaY) {
                e.preventDefault();
                const delta = -e.deltaY;
                const zoomFactor = 1.1;
    
                // Calculate cursor position relative to the transformed origin
                const xs = (e.clientX - this.pointX) / this.scale;
                const ys = (e.clientY - this.pointY) / this.scale;
    
                if (delta > 0) {
                    this.scale *= zoomFactor;
                } else {
                    this.scale /= zoomFactor;
                }
    
                // Clamp scale
                this.scale = Math.min(Math.max(0.2, this.scale), 3);
    
                // Adjust pointXY to keep the point under cursor stable
                this.pointX = e.clientX - xs * this.scale;
                this.pointY = e.clientY - ys * this.scale;
            }
        },
        resetView() {
            this.scale = 0.5;
            this.pointX = window.innerWidth / 2;
            this.pointY = 100;
        },
    
        exportTree() {
            alert('Đang xử lý xuất ảnh... Vui lòng đợi trong giây lát.');
    
            const element = document.getElementById('tree-content');
            const originalTransform = element.style.transform;
    
            // Reset transform to capture full size
            element.style.transform = 'none';
    
            html2canvas(element, {
                backgroundColor: '#ffffff',
                scale: 2, // High resolution
                useCORS: true,
                logging: true,
                x: 0,
                y: 0
            }).then(canvas => {
                // Restore transform
                element.style.transform = originalTransform;
    
                // Download
                const link = document.createElement('a');
                link.download = 'GiaPhaViet_Export_' + new Date().toISOString().slice(0, 10) + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }).catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra khi xuất ảnh.');
                element.style.transform = originalTransform;
            });
        },
    
        // Auto-center on focused person
        centerView() {
            this.scale = 0.5;
            this.pointX = window.innerWidth / 2;
            this.pointY = 200;
        }
    }" @export-tree-triggered.window="exportTree()"
        @tree-focused.window="centerView()" @tree-reset.window="centerView()">
        <!-- Canvas Container -->
        <div class="w-full h-full bg-slate-50 relative overflow-hidden cursor-grab active:cursor-grabbing"
            @mousedown="setPanning($event)" @mouseup="releasePanning($event)" @mouseleave="releasePanning($event)"
            @mousemove="pan($event)" @wheel="zoom($event)">

            <!-- Background Image (Traditional/Dragon Scroll) -->
            <div class="absolute inset-0 pointer-events-none"
                :style="'background-image: url(/images/bg-dragon-scroll.jpg); background-size: cover; background-position: center; opacity: 0.5;'">
            </div>

            <!-- Optional Dot Grid Overlay (Subtle, for alignment) -->
            <div class="absolute inset-0 pointer-events-none opacity-10"
                :style="'background-image: radial-gradient(#000 1px, transparent 1px); background-size: ' + (20 * scale) +
                'px ' + (
                    20 * scale) + 'px; background-position: ' + pointX + 'px ' + pointY + 'px;'">
            </div>

            <!-- Title Header (Top Center) -->
            <!-- Title Header (Top Center) -->
            <!-- Title Header (Compact & Floating) -->
            <div
                class="absolute top-4 left-1/2 -translate-x-1/2 z-40 pointer-events-none select-none flex flex-col items-center">
                <div
                    class="bg-white/90 backdrop-blur-md shadow-sm border border-primary-200/50 px-6 py-2 rounded-full flex items-center gap-2">
                    <!-- Optional decorative icon -->
                    <span class="text-lg opacity-80">📜</span>

                    <h1
                        class="font-serif text-base md:text-lg text-[#C41E3A] font-bold uppercase tracking-widest whitespace-nowrap">
                        {{ $filters['treeTitle'] ?? 'Gia phả dòng họ Nguyễn' }}
                    </h1>

                    <span class="text-lg transform scale-x-[-1] opacity-80">📜</span>
                </div>
            </div>

            <!-- Breadcrumb Navigation (shown when focused) -->
            @if (!empty($breadcrumbPath))
                <div class="absolute top-20 left-6 z-40 pointer-events-auto">
                    <div
                        class="bg-white/95 backdrop-blur-sm rounded-lg shadow-lg border border-gray-200 px-4 py-2 flex items-center gap-2 max-w-2xl overflow-x-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 flex-shrink-0"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        @foreach ($breadcrumbPath as $index => $ancestor)
                            @if ($index > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 flex-shrink-0"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                            <button wire:click="focusOnPerson({{ $ancestor['id'] }})"
                                class="text-sm font-medium whitespace-nowrap transition-colors {{ $loop->last ? 'text-primary-600 font-bold' : 'text-gray-600 hover:text-primary-500' }}">
                                {{ $ancestor['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reset to Root Button (shown when focused) -->
            @if ($focusedPersonId)
                <div class="absolute top-20 right-6 z-40 pointer-events-auto">
                    <button wire:click="resetToRoot"
                        class="bg-white/95 backdrop-blur-sm hover:bg-primary-50 text-gray-700 hover:text-primary-600 px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all border border-gray-200 hover:border-primary-300 flex items-center gap-2 group"
                        title="Quay về cây gốc">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 group-hover:rotate-180 transition-transform duration-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-sm font-medium">Quay về cây gốc</span>
                    </button>
                </div>
            @endif

            <!-- Floating Controls (Bottom Right) -->
            <div class="absolute bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-auto">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-1 flex flex-col">
                    <button @click="scale *= 1.1" class="p-2 hover:bg-gray-100 rounded text-gray-600" title="Zoom In">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button @click="resetView()"
                        class="p-2 hover:bg-gray-100 rounded text-gray-600 border-t border-b border-gray-100"
                        title="Reset">
                        <span class="text-xs font-bold" x-text="Math.round(scale * 100) + '%'"></span>
                    </button>
                    <button @click="scale /= 1.1" class="p-2 hover:bg-gray-100 rounded text-gray-600" title="Zoom Out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Infinite Canvas World -->
            <div id="tree-content" wire:ignore
                class="absolute origin-top-left transition-transform duration-75 ease-linear will-change-transform"
                :style="`transform: translate(${pointX}px, ${pointY}px) scale(${scale});`">

                @if ($rootPerson)
                    <div class="flex flex-col items-center -translate-x-1/2">
                        <!-- Root Node -->
                        @include('livewire.partials.node-card', [
                            'person' => $rootPerson,
                            'filters' => array_merge($filters, ['focusedPersonId' => $focusedPersonId]),
                        ])

                        <!-- Recursive Tree Rendering -->
                        @if ($rootPerson->children->isNotEmpty())
                            @include('livewire.partials.tree-branch', [
                                'children' => $rootPerson->children,
                                'filters' => array_merge($filters, ['focusedPersonId' => $focusedPersonId]),
                            ])
                        @endif
                    </div>
                @else
                    <!-- Infinite Canvas Container -->
                    <div class="relative w-full h-full overflow-hidden bg-gradient-to-br from-gray-50 via-blue-50/30 to-pink-50/30"
                        x-on:mousedown="startDrag($event)" x-on:mousemove="drag($event)" x-on:mouseup="stopDrag"
                        x-on:mouseleave="stopDrag" x-on:wheel.prevent="zoom($event)"
                        style="background-image: 
            radial-gradient(circle, rgba(200, 200, 200, 0.15) 1px, transparent 1px),
            radial-gradient(circle, rgba(200, 200, 200, 0.15) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;">

                        <!-- Zoom Controls -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2 z-50 pointer-events-auto">
                            <button @click="zoomIn"
                                class="bg-white hover:bg-primary-50 text-gray-700 hover:text-primary-600 p-3 rounded-xl shadow-lg hover:shadow-xl transition-all border border-gray-200 hover:border-primary-300 group">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            <button @click="zoomOut"
                                class="bg-white hover:bg-primary-50 text-gray-700 hover:text-primary-600 p-3 rounded-xl shadow-lg hover:shadow-xl transition-all border border-gray-200 hover:border-primary-300 group">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4" />
                                </svg>
                            </button>
                            <button @click="resetView"
                                class="bg-white hover:bg-primary-50 text-gray-700 hover:text-primary-600 p-3 rounded-xl shadow-lg hover:shadow-xl transition-all border border-gray-200 hover:border-primary-300 group"
                                title="Reset View">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 group-hover:rotate-180 transition-transform duration-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div> Tạo người đầu tiên
                        </button>
                    </div>
            </div>
            @endif
        </div>
    </div>

    <!-- UI Overlay Controls (Sidebars loaded via livewire) -->
    <div class="absolute inset-0 pointer-events-none flex justify-between z-30">
        <!-- Left Sidebar (Pointer events auto to allow interaction) -->
        <div class="pointer-events-auto h-full">
            <livewire:components.sidebar-left />
        </div>

        <!-- Right Sidebar (Pointer events auto) -->
        <div class="pointer-events-auto h-full flex flex-col items-end pointer-events-none">
            <!-- Right Sidebar Component loads its own container -->
        </div>
    </div>

    <!-- Right Sidebar is actually absolute positioned in its own component, but let's keep the structure clean -->
    <livewire:components.sidebar-right />

    <!-- Global Loading Overlay -->
    <div wire:loading.flex
        class="absolute inset-0 z-[60] bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-300">
        <div
            class="flex flex-col items-center p-6 bg-white rounded-xl shadow-2xl border border-gray-100 animate-pulse">
            <svg class="animate-spin h-10 w-10 text-[#C41E3A] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-[#C41E3A] font-bold text-lg uppercase tracking-widest font-serif">Đang tải gia phả...</p>
            <p class="text-xs text-gray-400 mt-1">Vui lòng đợi trong giây lát</p>
        </div>
    </div>

    {{-- Mobile Bottom Navigation --}}
    @include('components.bottom-nav')
</div>
