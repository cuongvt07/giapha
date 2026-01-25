<div class="w-full h-full">

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
                this.panning = false; // Start as not panning
                this.touchStartX = e.touches[0].clientX;
                this.touchStartY = e.touches[0].clientY;
                this.startX = this.touchStartX - this.pointX;
                this.startY = this.touchStartY - this.pointY;
            }
        },
    
        handleTouchMove(e) {
            if (e.touches.length !== 1) return;
    
            const touch = e.touches[0];
            const deltaX = Math.abs(touch.clientX - this.touchStartX);
            const deltaY = Math.abs(touch.clientY - this.touchStartY);
    
            // Only start panning if moved more than 10px (prevents accidental panning on tap)
            if (deltaX > 10 || deltaY > 10) {
                this.panning = true;
                e.preventDefault(); // Only prevent default when actually panning
                this.pointX = touch.clientX - this.startX;
                this.pointY = touch.clientY - this.startY;
            }
        },
    
        handleTouchEnd(e) {
            this.panning = false;
        },
    
        // Mouse Events for PC Testing
        handleMouseDown(e) {
            if (e.button !== 0) return; // Left click only
            this.panning = false;
            this.touchStartX = e.clientX;
            this.touchStartY = e.clientY;
            this.startX = this.touchStartX - this.pointX;
            this.startY = this.touchStartY - this.pointY;
            this.isMouseDown = true;
        },
    
        handleMouseMove(e) {
            if (!this.isMouseDown) return;
    
            const deltaX = Math.abs(e.clientX - this.touchStartX);
            const deltaY = Math.abs(e.clientY - this.touchStartY);
    
            if (deltaX > 5 || deltaY > 5) {
                this.panning = true;
                e.preventDefault();
                this.pointX = e.clientX - this.startX;
                this.pointY = e.clientY - this.startY;
            }
        },
    
        handleMouseUp(e) {
            this.isMouseDown = false;
            setTimeout(() => { this.panning = false; }, 50); // Small delay to prevent click triggering
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
        <div class="sticky top-0 z-30 bg-white border-b border-gray-200" style="touch-action: auto;">
            <div class="flex items-center justify-between px-4 py-3">
                {{-- Hamburger --}}
                <button @click="console.log('Toggle sidebar clicked'); $dispatch('toggle-sidebar')"
                    class="p-2 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                    <button wire:click="$dispatch('toggle-search')"
                        class="p-2 hover:bg-gray-100 active:bg-gray-200 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Tree Canvas --}}
        <div class="w-full h-full relative overflow-hidden bg-slate-50 tree-canvas-touch"
            @touchstart.passive="handleTouchStart($event)" @touchmove="handleTouchMove($event)"
            @touchend.passive="handleTouchEnd($event)" @mousedown="handleMouseDown($event)"
            @mousemove="handleMouseMove($event)" @mouseup="handleMouseUp($event)" @mouseleave="handleMouseUp($event)">

            {{-- Background Image (Traditional/Dragon Scroll) - Same as Desktop --}}
            <div class="absolute inset-0 pointer-events-none"
                style="background-image: url(/images/bg-dragon-scroll.jpg); background-size: cover; background-position: center; opacity: 0.5;">
            </div>

            {{-- Tree Content with Transform --}}
            <div class="absolute origin-top-left transition-transform duration-75 ease-out will-change-transform"
                :style="`transform: translate(${pointX}px, ${pointY}px) scale(${scale}); cursor: ${panning ? 'grabbing' : 'grab'};`">
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
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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
    {{-- Load D3.js --}}
    <script src="https://d3js.org/d3.v7.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('familyTreeLogic', () => ({
                scale: 0.5,
                panning: false,
                startX: 0,
                startY: 0,
                pointX: window.innerWidth / 2,
                pointY: 100,

                debugNodeCount: 0,
                debugConnCount: 0,
                debugStatus: 'Init',
                retryCount: 0,
                checkInterval: null,
                svgLayer: null,

                init() {
                    console.log('Alpine Init - D3.js Mode');
                    this.waitForD3();
                },

                waitForD3() {
                    if (typeof d3 !== 'undefined') {
                        this.initD3Connections();
                    } else {
                        console.log('Waiting for D3.js...');
                        setTimeout(() => this.waitForD3(), 100);
                    }
                },

                initD3Connections() {
                    console.log('D3.js Ready');
                    this.debugStatus = 'D3 Ready';

                    // Initial draw after DOM is ready
                    setTimeout(() => {
                        this.drawElbowConnections();
                        this.centerRoot();
                    }, 500);

                    // Polling safety net
                    this.checkInterval = setInterval(() => {
                        const nodes = document.querySelectorAll(
                            '#tree-content [data-parent-id]').length;
                        const svg = document.getElementById('connection-layer');
                        const conns = svg ? svg.querySelectorAll('path').length : 0;

                        this.debugNodeCount = nodes;
                        this.debugConnCount = conns;
                        this.debugStatus = 'Polling...';

                        if (nodes > 0 && conns === 0 && this.retryCount < 5) {
                            console.warn('Nodes found but no connections. Retrying...');
                            this.drawElbowConnections();
                            this.retryCount++;
                        }
                    }, 2000);

                    // Hook into Livewire updates
                    Livewire.hook('message.processed', (message, component) => {
                        console.log('Livewire processed. Redrawing connections.');
                        this.retryCount = 0;
                        setTimeout(() => this.drawElbowConnections(), 200);
                        setTimeout(() => this.drawElbowConnections(), 1000);
                    });

                    // Redraw on window resize
                    window.addEventListener('resize', () => {
                        this.drawElbowConnections();
                    });

                    // Expose global for manual trigger
                    window.forceRedraw = () => this.drawElbowConnections();
                },

                drawElbowConnections() {
                    const container = document.getElementById('tree-content');
                    if (!container) return;

                    // Remove existing SVG layer
                    let svg = document.getElementById('connection-layer');
                    if (svg) svg.remove();

                    // Get all nodes with parent relationships
                    const nodes = document.querySelectorAll('#tree-content [data-parent-id]');
                    this.debugNodeCount = nodes.length;

                    if (nodes.length === 0) {
                        this.debugStatus = 'No nodes';
                        return;
                    }

                    // Helper function to get element position relative to container using offsets
                    // This is NOT affected by CSS transforms, so it works correctly with zoom
                    const getOffsetPosition = (el) => {
                        let x = 0,
                            y = 0;
                        let current = el;
                        while (current && current !== container) {
                            x += current.offsetLeft;
                            y += current.offsetTop;
                            current = current.offsetParent;
                        }
                        return {
                            x,
                            y,
                            width: el.offsetWidth,
                            height: el.offsetHeight
                        };
                    };

                    // Calculate the bounding box of all nodes
                    let maxX = 0,
                        maxY = 0;
                    const allNodes = document.querySelectorAll('#tree-content [id^="node-"]');
                    allNodes.forEach(node => {
                        const pos = getOffsetPosition(node);
                        maxX = Math.max(maxX, pos.x + pos.width);
                        maxY = Math.max(maxY, pos.y + pos.height);
                    });

                    // Create SVG layer
                    svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.id = 'connection-layer';
                    svg.style.position = 'absolute';
                    svg.style.top = '0';
                    svg.style.left = '0';
                    svg.style.width = (maxX + 200) + 'px';
                    svg.style.height = (maxY + 200) + 'px';
                    svg.style.pointerEvents = 'none';
                    svg.style.overflow = 'visible';
                    svg.style.zIndex = '5';

                    container.insertBefore(svg, container.firstChild);

                    let connectionCount = 0;

                    // Draw connections using offset-based positions
                    nodes.forEach(node => {
                        const parentId = node.getAttribute('data-parent-id');
                        const parent = document.getElementById(parentId);

                        if (parent && node) {
                            const parentPos = getOffsetPosition(parent);
                            const nodePos = getOffsetPosition(node);

                            // Source: center-bottom of parent
                            const sourceX = parentPos.x + parentPos.width / 2;
                            const sourceY = parentPos.y + parentPos.height;

                            // Target: center-top of child
                            const targetX = nodePos.x + nodePos.width / 2;
                            const targetY = nodePos.y;

                            // Calculate midpoint for the horizontal line (15% from parent)
                            const midY = sourceY + (targetY - sourceY) * 0.15;

                            // Create Elbow path: Vertical → Horizontal → Vertical
                            const path = document.createElementNS('http://www.w3.org/2000/svg',
                                'path');
                            path.setAttribute('d',
                                `M${sourceX},${sourceY} V${midY} H${targetX} V${targetY}`);
                            path.setAttribute('fill', 'none');
                            path.setAttribute('stroke', '#6b7280');
                            path.setAttribute('stroke-width', '2');
                            path.setAttribute('stroke-linecap', 'round');
                            path.setAttribute('stroke-linejoin', 'round');

                            svg.appendChild(path);
                            connectionCount++;
                        }
                    });

                    this.debugConnCount = connectionCount;
                    this.debugStatus = 'D3 Drawn ' + connectionCount;
                    console.log('D3 Elbow: Drawn ' + connectionCount + ' connections');
                },

                setPanning(e) {
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

                        const xs = (e.clientX - this.pointX) / this.scale;
                        const ys = (e.clientY - this.pointY) / this.scale;

                        if (delta > 0) {
                            this.scale *= zoomFactor;
                        } else {
                            this.scale /= zoomFactor;
                        }

                        this.scale = Math.min(Math.max(0.2, this.scale), 3);

                        this.pointX = e.clientX - xs * this.scale;
                        this.pointY = e.clientY - ys * this.scale;

                        // Redraw connections after zoom
                        this.drawElbowConnections();
                    }
                },
                resetView() {
                    this.scale = 0.5;
                    this.pointX = window.innerWidth / 2;
                    this.pointY = 100;
                    this.drawElbowConnections();
                },
                centerView() {
                    this.scale = 0.5;
                    this.pointX = window.innerWidth / 2;
                    this.pointY = 200;
                    this.drawElbowConnections();
                },

                centerOnNode(nodeId) {
                    const el = document.getElementById(nodeId);
                    if (!el) return;

                    // console.log('Centering on:', nodeId);

                    let target = el;
                    let nodeX = 0;
                    let nodeY = 0;

                    // Simple accumulation of offsets relative to the container
                    while (target && target.id !== 'tree-content') {
                        nodeX += target.offsetLeft;
                        nodeY += target.offsetTop;
                        target = target.offsetParent;
                    }

                    // Add half width/height to center on the element center
                    nodeX += el.offsetWidth / 2;
                    nodeY += el.offsetHeight / 2;

                    // Calculate target translation
                    // Translation = Screen Center - (NodePos * Scale)
                    this.pointX = (window.innerWidth / 2) - (nodeX * this.scale);

                    // For Vertical position: "Like Gen 1" usually means near top.
                    // If we put it in exact center, it might feel "lost" if it's deep.
                    // Let's put it at 150px from top (similar to root).
                    this.pointY = 150 - (nodeY * this.scale);

                    // Redraw connections after panning
                    setTimeout(() => this.drawElbowConnections(), 50);
                },

                centerRoot() {
                    // Find the root node (node with no parent connection defined in data attribute, or just the top one)
                    // We can assume the first interactive element inside the tree-content wrapper is the root or close to it.
                    // Better: find the element that matches the root ID pattern if we passed it.
                    // Or: find the node with NO data-parent-id.
                    setTimeout(() => {
                        const root = document.querySelector(
                            '#tree-content [id^="node-"]:not([data-parent-id])');
                        if (root) {
                            console.log('Auto-centering root:', root.id);
                            this.centerOnNode(root.id);
                        }
                    }, 100);
                },

                exportTree() {
                    alert('Tính năng xuất ảnh đang được cập nhật.');
                }
            }));
        });
    </script>

    <div class="hidden lg:block w-full h-full" x-data="familyTreeLogic" @export-tree-triggered.window="exportTree()"
        @tree-focused.window="centerView()" @tree-reset.window="centerView()"
        @center-on-node.window="centerOnNode($event.detail.nodeId)">
        <!-- Canvas Container -->
        {{-- DEBUG CSS --}}
        <style>
            .jtk-connector {
                z-index: 50 !important;
            }

            .jtk-endpoint {
                z-index: 50 !important;
            }

            .jtk-overlay {
                z-index: 51 !important;
            }
        </style>

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
            <!-- Title Header (Compact & Floating) -->
            <div
                class="absolute top-4 left-1/2 -translate-x-1/2 z-40 pointer-events-none select-none flex flex-col items-center">
                <div
                    class="bg-white/90 backdrop-blur-md shadow-sm border border-primary-200/50 px-6 py-2 rounded-full flex items-center gap-2">
                    <!-- Optional decorative icon -->
                    <span class="text-lg opacity-80">📜</span>

                    <div class="overflow-hidden w-64 md:w-96">
                        <marquee scrollamount="4" class="font-serif text-base md:text-lg text-[#C41E3A] font-bold uppercase tracking-widest whitespace-nowrap">
                            {{ $filters['treeTitle'] ?? 'Gia phả dòng họ Nguyễn' }}
                        </marquee>
                    </div>

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
                    <button @click="scale *= 1.1" class="p-2 hover:bg-gray-100 rounded text-gray-600"
                        title="Zoom In">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
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
                    <button @click="scale /= 1.1" class="p-2 hover:bg-gray-100 rounded text-gray-600"
                        title="Zoom Out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Infinite Canvas World -->
            <div id="tree-content"
                class="absolute origin-top-left transition-transform duration-75 ease-linear will-change-transform"
                :style="`transform: translate(${pointX}px, ${pointY}px) scale(${scale});`">

                @if ($rootPerson)
                    <div class="flex flex-col items-center pt-48">
                        <!-- Root Node -->
                        @include('livewire.partials.node-card', [
                            'person' => $rootPerson,
                            'filters' => array_merge($filters, ['focusedPersonId' => $focusedPersonId]),
                            'generationLevel' => 1,
                        ])

                        <!-- Recursive Tree Rendering -->
                        @if ($rootPerson->children->isNotEmpty())
                            @include('livewire.partials.tree-branch', [
                                'children' => $rootPerson->children,
                                'filters' => array_merge($filters, ['focusedPersonId' => $focusedPersonId]),
                                'generationLevel' => 2,
                            ])
                        @endif
                    </div>
                @else
                    <div class="relative w-full h-full overflow-hidden flex items-center justify-center p-4">
                        <div class="absolute inset-0 z-0 bg-cover bg-center opacity-30 pointer-events-none"
                             style="background-image: url('/images/bg-dragon-scroll.jpg');"></div>

                        <div class="bg-white/80 backdrop-blur-xl p-10 rounded-3xl shadow-2xl text-center max-w-md border border-white/50 animate-fade-in-up z-10 relative">
                            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <span class="text-6xl">🌱</span>
                            </div>
                            
                            <h2 class="text-3xl font-bold text-gray-800 mb-2 font-serif">Khởi tạo Gia Phả</h2>
                            <p class="text-gray-500 mb-8 text-lg font-light">
                                "Cây có cội, nước có nguồn."<br>Hãy bắt đầu hành trình ghi chép lịch sử dòng họ.
                            </p>
                            
                            <button wire:click="$dispatch('open-add-modal')" class="w-full py-4 px-8 bg-gradient-to-r from-[#C41E3A] to-[#A01830] text-white rounded-xl font-bold text-lg shadow-lg shadow-red-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Thêm người đầu tiên</span>
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
</div>
