<div class="h-full w-full flex flex-col relative bg-gray-50">
    {{-- Header --}}
    <header
        class="flex-shrink-0 h-14 bg-white border-b border-gray-200 flex items-center justify-between px-4 z-20 shadow-sm">
        {{-- Menu Button --}}
        <button wire:click="toggleMenu" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 active:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Title --}}
        <h1 class="text-lg font-bold text-gray-900">Cây Gia Phả</h1>

        {{-- Home Button --}}
        <button wire:click="resetToRoot" class="p-2 -mr-2 rounded-lg hover:bg-gray-100 active:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </button>
    </header>

    {{-- Custom Styles for Line Animation --}}
    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                border-color: rgba(156, 163, 175, 1);
            }

            /* gray-400 */
            50% {
                opacity: 0.6;
                border-color: rgba(96, 165, 250, 1);
            }

            /* blue-400 */
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    {{-- Tree Canvas --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.15.6/js/jsplumb.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mobileFamilyTreeLogic', () => ({
                jsPlumbInstance: null,
                scale: 0.7,
                pointX: 0,
                pointY: 60,
                startX: 0,
                startY: 0,
                touchStartX: 0,
                touchStartY: 0,
                isPanning: false,

                init() {
                    this.pointX = 0;
                    this.pointY = 60;
                    this.initJsPlumb();
                },

                initJsPlumb() {
                    jsPlumb.ready(() => {
                        this.drawConnections();
                        this.centerRoot();
                    });
                },

                createInstance() {
                    return jsPlumb.getInstance({
                        Container: "mobile-tree-content",
                        Connector: ["Flowchart", {
                            stub: [20, 20],
                            cornerRadius: 5,
                            gap: 5,
                            alwaysRespectStubs: true
                        }],
                        Endpoint: ["Blank", {}],
                        Anchor: ["Bottom", "Top"],
                        PaintStyle: {
                            stroke: "#9ca3af",
                            strokeWidth: 2,
                            dashstyle: "4 2"
                        },
                        HoverPaintStyle: {
                            stroke: "#60a5fa",
                            strokeWidth: 3
                        }
                    });
                },

                drawConnections() {
                    if (this.jsPlumbInstance) {
                        try {
                            this.jsPlumbInstance.reset();
                        } catch (e) {
                            console.warn('Error resetting jsPlumb:', e);
                        }
                    }

                    this.jsPlumbInstance = this.createInstance();

                    const nodes = document.querySelectorAll('#mobile-tree-content [data-parent-id]');
                    nodes.forEach(node => {
                        const parentId = node.getAttribute('data-parent-id');
                        const source = document.getElementById(parentId);
                        if (source && node) {
                            try {
                                this.jsPlumbInstance.connect({
                                    source: source,
                                    target: node,
                                    anchors: ["Bottom", "Top"],
                                    overlays: [
                                        ["Arrow", {
                                            location: 1,
                                            width: 8,
                                            length: 8,
                                            foldback: 0.8
                                        }]
                                    ]
                                });
                            } catch (e) {
                                console.error('Connection failed:', e);
                            }
                        }
                    });

                    setTimeout(() => {
                        if (this.jsPlumbInstance) this.jsPlumbInstance.repaintEverything();
                    }, 50);
                },

                handleTouchStart(e) {
                    if (e.touches.length === 1) {
                        this.touchStartX = e.touches[0].clientX;
                        this.touchStartY = e.touches[0].clientY;
                        this.startX = this.touchStartX - this.pointX;
                        this.startY = this.touchStartY - this.pointY;
                        this.isPanning = false;
                    }
                },

                handleTouchMove(e) {
                    if (e.touches.length !== 1) return;
                    const touch = e.touches[0];
                    const deltaX = Math.abs(touch.clientX - this.touchStartX);
                    const deltaY = Math.abs(touch.clientY - this.touchStartY);

                    if (deltaX > 10 || deltaY > 10) {
                        this.isPanning = true;
                        e.preventDefault();
                        this.pointX = touch.clientX - this.startX;
                        this.pointY = touch.clientY - this.startY;
                    }
                },

                handleTouchEnd(e) {
                    this.isPanning = false;
                },

                zoomIn() {
                    this.scale = Math.min(this.scale * 1.2, 2);
                    if (this.jsPlumbInstance) this.jsPlumbInstance.setZoom(this.scale);
                },

                zoomOut() {
                    this.scale = Math.max(this.scale / 1.2, 0.3);
                    if (this.jsPlumbInstance) this.jsPlumbInstance.setZoom(this.scale);
                },

                resetView() {
                    this.scale = 0.7;
                    this.pointX = 0;
                    this.pointY = 60;
                    if (this.jsPlumbInstance) this.jsPlumbInstance.setZoom(this.scale);
                },

                centerOnNode(nodeId) {
                    const el = document.getElementById(nodeId);
                    if (!el) return;

                    let target = el;
                    let nodeX = 0;
                    let nodeY = 0;

                    while (target && target.id !== 'mobile-tree-content') {
                        nodeX += target.offsetLeft;
                        nodeY += target.offsetTop;
                        target = target.offsetParent;
                    }

                    nodeX += el.offsetWidth / 2;
                    nodeY += el.offsetHeight / 2;

                    const screenW = window.innerWidth;
                    this.pointX = (screenW / 2) - (nodeX * this.scale);
                    this.pointY = 150 - (nodeY * this.scale);

                    if (this.jsPlumbInstance) this.jsPlumbInstance.repaintEverything();
                },

                centerRoot() {
                    setTimeout(() => {
                        const root = document.querySelector(
                            '#mobile-tree-content [id^="node-"]:not([data-parent-id])');
                        if (root) {
                            this.centerOnNode(root.id);
                        }
                    }, 500);
                }
            }));
        });
    </script>

    <div class="flex-1 overflow-hidden relative" x-data="mobileFamilyTreeLogic" @touchstart.passive="handleTouchStart($event)"
        @touchmove="handleTouchMove($event)" @touchend.passive="handleTouchEnd($event)"
        @center-on-node.window="centerOnNode($event.detail.nodeId)"
        @tree-updated.window="setTimeout(() => { drawConnections(); centerRoot(); }, 100)" style="touch-action: none;">

        {{-- Tree Content - Centered horizontally --}}
        <div id="mobile-tree-content" wire:ignore
            wire:key="tree-canvas-{{ $rootPerson ? $rootPerson->id : 'empty' }}-{{ $treeVersion }}"
            class="absolute inset-x-0 flex justify-center transition-transform duration-75 ease-out"
            :style="`transform: translateX(${pointX}px) translateY(${pointY}px) scale(${scale}); transform-origin: top center;`">
            @if ($rootPerson)
                @include('livewire.partials.mobile-node', [
                    'person' => $rootPerson,
                    'generationLevel' => 1,
                ])
            @else
                <div class="text-center p-8">
                    <p class="text-gray-500">Chưa có dữ liệu</p>
                    <button wire:click="openAddModal" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-full">
                        Thêm người đầu tiên
                    </button>
                </div>
            @endif
        </div>

        {{-- Zoom Controls (FABs) --}}
        <div class="absolute bottom-4 right-4 flex flex-col gap-2 z-10">
            <button @click="zoomIn()"
                class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-200 active:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <button @click="zoomOut()"
                class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-200 active:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <button @click="resetView()"
                class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-200 active:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Drawer Menu Overlay --}}
    @if ($showMenu)
        <div class="fixed inset-0 z-40" wire:click="closeMenu">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
    @endif

    {{-- Drawer Menu --}}
    <div
        class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl z-50 transform transition-transform duration-300 {{ $showMenu ? 'translate-x-0' : '-translate-x-full' }}">
        @include('livewire.partials.mobile-menu')
    </div>

    {{-- Bottom Sheet --}}
    @if ($showBottomSheet && $selectedPerson)
        <div class="fixed inset-0 z-40" wire:click="closeBottomSheet">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl z-50 max-h-[70vh] overflow-hidden">
            @include('livewire.partials.mobile-bottom-sheet', ['person' => $selectedPerson])
        </div>
    @endif

    {{-- Add Modal --}}
    @if ($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" wire:click="closeAddModal"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[80vh] overflow-y-auto">
                @include('livewire.partials.mobile-add-form')
            </div>
        </div>
    @endif

    {{-- Loading Overlay - Only shows for slow operations --}}
    <div wire:loading.flex wire:target="focusOnPerson, resetToRoot"
        class="fixed inset-0 z-[100] bg-white/50 backdrop-blur-sm items-center justify-center transition-opacity duration-300">
        <div class="flex flex-col items-center p-4 bg-white/90 rounded-2xl shadow-md">
            <svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="mt-1.5 text-xs text-gray-400">Đang tải...</p>
        </div>
    </div>
</div>
