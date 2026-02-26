<style>
    /* Hide all overlapping UI when Print Preview is active */
    body.print-preview-mode [data-print-hide],
    body.print-preview-mode .z-\[60\] {
        display: none !important;
    }
    /* Also force-hide any z-50 fixed elements (sidebar-right toggle) */
    body.print-preview-mode .z-50.fixed {
        visibility: hidden !important;
    }
</style>
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
                        const zoomFactor = 1.02; // Change to 2% (1.02)

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

                init() {
                    this.centerRoot();
                    window.addEventListener('resize', () => {
                        if (this.printPreviewActive) this.updateSheetPreview();
                    });
                },
                PAPER_SIZES: {
                    'a4': { name: 'A4 (210x297mm)', w: 210, h: 297 },
                    'a3': { name: 'A3 (297x420mm)', w: 297, h: 420 },
                    'a2': { name: 'A2 (420x594mm)', w: 420, h: 594 },
                    'a1': { name: 'A1 (594x841mm)', w: 594, h: 841 },
                    'a0': { name: 'A0 (841x1189mm)', w: 841, h: 1189 },
                    'auto': { name: 'Tự động (Vừa khít)', w: 0, h: 0 }
                },
                selectedPaperSize: 'auto',
                paperOrientation: 'landscape', // 'landscape' or 'portrait'
                exportQuality: 2, // 1=Normal, 2=High, 3=Ultra
                printTab: 'tree', // 'tree' or 'calendar'
                printPreviewActive: false,

                // Added for visual preview frame
                sheetW: 0,
                sheetH: 0,
                sheetScale: 1,

                mmToPx(mm) {
                    return Math.round(mm * 96 / 25.4);
                },

                async imageToBase64(url) {
                    try {
                        const response = await fetch(url);
                        const blob = await response.blob();
                        return new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.onerror = reject;
                            reader.readAsDataURL(blob);
                        });
                    } catch (e) {
                        console.warn('Image fetch failed:', url, e);
                        return url;
                    }
                },

                getTitleFontSize() {
                    if (this.selectedPaperSize === 'auto') return 'text-2xl md:text-3xl';
                    
                    const mapping = {
                        'a4': 'text-2xl',      // ~24px
                        'a3': 'text-3xl',      // ~30px
                        'a2': 'text-4xl',      // ~36px
                        'a1': 'text-5xl',      // ~48px
                        'a0': 'text-6xl'       // ~60px
                    };
                    return mapping[this.selectedPaperSize] || 'text-2xl md:text-3xl';
                },

                getTitleTopMargin() {
                    if (this.selectedPaperSize === 'auto') return 40;
                    const mapping = {
                        'a4': 30,
                        'a3': 40,
                        'a2': 40,
                        'a1': 50,
                        'a0': 50
                    };
                    return mapping[this.selectedPaperSize] || 40;
                },

                // Refresh the visual frame dimensions to fit the screen
                updateSheetPreview() {
                    this.$nextTick(() => {
                        if (this.selectedPaperSize === 'auto') {
                            this.sheetW = 2000; 
                            this.sheetH = 2000;
                            this.sheetScale = 1;
                        } else {
                            const size = this.PAPER_SIZES[this.selectedPaperSize];
                            let baseW, baseH;
                            if (this.paperOrientation === 'landscape') {
                                baseW = this.mmToPx(size.h);
                                baseH = this.mmToPx(size.w);
                            } else {
                                baseW = this.mmToPx(size.w);
                                baseH = this.mmToPx(size.h);
                            }
                            this.sheetW = baseW;
                            this.sheetH = baseH;
                            this.sheetScale = 1; // No viewport scaling needed anymore
                        }
                    });
                },

                printDragEnabled: true,
                printDragging: false,
                printDragNodeId: null,
                printDragStartX: 0,
                printDragOriginalOffset: 0,
                printNodeOffsets: {},
                printExporting: false,
                printPanning: false,
                printPanStartX: 0,
                printPanStartY: 0,
                printPanX: 0,
                printPanY: 0,
                printScale: 0.45,

                exportTree() {
                    this.openPrintPreview();
                },

                openPrintPreview() {
                    this.printPreviewActive = true;
                    this.printNodeOffsets = {};
                    this.printPanX = 0;
                    this.printPanY = 0;
                    this.printScale = 0.45;
                    this.printTab = 'tree';
                    document.body.classList.add('print-preview-mode');
                    document.body.style.overflow = 'hidden';

                    setTimeout(() => {
                        this.cloneTreeToPreview();
                    }, 100);
                },

                closePrintPreview() {
                    this.printPreviewActive = false;
                    this.printNodeOffsets = {};
                    document.body.classList.remove('print-preview-mode');
                    document.body.style.overflow = '';
                },

                cloneTreeToPreview() {
                    const source = document.getElementById('tree-content');
                    const target = document.getElementById('print-preview-tree');
                    if (!source || !target) return;

                    // Clone the tree HTML
                    target.innerHTML = source.innerHTML;

                    // Remove action buttons, hover effects, connector dots, and unnecessary UI from clones
                    target.querySelectorAll('.center-node-btn, .opacity-0.group-hover\\:opacity-100, .z-40, .z-50, [data-print-hide], svg').forEach(el => el.remove());
                    // Remove ALL connector dots (w-2 h-2 circles at top/bottom of nodes)
                    target.querySelectorAll('.w-2.h-2.rounded-full').forEach(el => el.remove());
                    // Remove group hover scale effects
                    target.querySelectorAll('.group').forEach(el => {
                        el.classList.remove('hover:scale-105', 'hover:-translate-y-0.5');
                        el.style.cursor = this.printDragEnabled ? 'grab' : 'default';
                    });

                    // Draw SVG connections in the preview
                    setTimeout(() => this.drawPreviewConnections(), 200);
                },

                drawPreviewConnections() {
                    const container = document.getElementById('print-preview-tree');
                    if (!container) return;

                    let svg = document.getElementById('preview-connection-layer');
                    if (svg) svg.remove();

                    const nodes = container.querySelectorAll('[data-parent-id]');
                    if (nodes.length === 0) return;

                    const getOffsetPos = (el) => {
                        let x = 0, y = 0;
                        let current = el;
                        while (current && current !== container) {
                            x += current.offsetLeft;
                            y += current.offsetTop;
                            current = current.offsetParent;
                        }
                        // Add drag offset
                        const nodeId = el.id;
                        if (nodeId && this.printNodeOffsets[nodeId]) {
                            x += this.printNodeOffsets[nodeId];
                        }
                        return { x, y, width: el.offsetWidth, height: el.offsetHeight };
                    };

                    let maxX = 0, maxY = 0;
                    container.querySelectorAll('[id^="node-"]').forEach(node => {
                        const pos = getOffsetPos(node);
                        maxX = Math.max(maxX, pos.x + pos.width);
                        maxY = Math.max(maxY, pos.y + pos.height);
                    });

                    svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.id = 'preview-connection-layer';
                    svg.style.position = 'absolute';
                    svg.style.top = '0';
                    svg.style.left = '0';
                    svg.style.width = (maxX + 200) + 'px';
                    svg.style.height = (maxY + 200) + 'px';
                    svg.style.pointerEvents = 'none';
                    svg.style.overflow = 'visible';
                    svg.style.zIndex = '5';
                    container.insertBefore(svg, container.firstChild);

                    nodes.forEach(node => {
                        const parentId = node.getAttribute('data-parent-id');
                        const parent = container.querySelector('#' + parentId);
                        if (parent && node) {
                            const pp = getOffsetPos(parent);
                            const np = getOffsetPos(node);
                            
                            const sx = pp.x + pp.width / 2;
                            const sy = pp.y + pp.height;
                            const tx = np.x + np.width / 2;
                            const ty = np.y;

                            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                            
                            // Traditional Elbow style (50/50 split)
                            const midY = sy + (ty - sy) * 0.5;
                            path.setAttribute('d', `M${sx},${sy} V${midY} H${tx} V${ty}`);

                            path.setAttribute('fill', 'none');
                            path.setAttribute('stroke', '#4b5563'); 
                            path.setAttribute('stroke-width', '2.5');
                            path.setAttribute('stroke-linecap', 'round');
                            path.setAttribute('stroke-linejoin', 'round');
                            svg.appendChild(path);
                        }
                    });
                },

                // Drag logic for print preview nodes (horizontal only)
                printStartDrag(e, nodeEl) {
                    if (!this.printDragEnabled) return;
                    const nodeId = nodeEl.closest('[id^="node-"]')?.id;
                    if (!nodeId) return;
                    e.preventDefault();
                    e.stopPropagation();
                    this.printDragging = true;
                    this.printDragNodeId = nodeId;
                    this.printDragStartX = e.clientX;
                    this.printDragOriginalOffset = this.printNodeOffsets[nodeId] || 0;
                    nodeEl.closest('[id^="node-"]').style.cursor = 'grabbing';
                },

                printOnDrag(e) {
                    if (!this.printDragging || !this.printDragNodeId) return;
                    e.preventDefault();
                    const delta = (e.clientX - this.printDragStartX) / this.printScale;
                    this.printNodeOffsets[this.printDragNodeId] = this.printDragOriginalOffset + delta;
                    // Apply transform
                    const el = document.querySelector('#print-preview-tree #' + this.printDragNodeId);
                    if (el) {
                        el.style.transform = `translateX(${this.printNodeOffsets[this.printDragNodeId]}px)`;
                    }
                    // Redraw connections
                    this.drawPreviewConnections();
                },

                printEndDrag(e) {
                    if (this.printDragging && this.printDragNodeId) {
                        const el = document.querySelector('#print-preview-tree #' + this.printDragNodeId);
                        if (el) el.style.cursor = 'grab';
                    }
                    this.printDragging = false;
                    this.printDragNodeId = null;
                },

                printResetOffsets() {
                    this.printNodeOffsets = {};
                    const container = document.getElementById('print-preview-tree');
                    if (container) {
                        container.querySelectorAll('[id^="node-"]').forEach(el => {
                            el.style.transform = '';
                        });
                    }
                    this.drawPreviewConnections();
                },

                // Pan the print preview canvas
                printStartPan(e) {
                    if (this.printDragging) return;
                    this.printPanning = true;
                    this.printPanStartX = e.clientX - this.printPanX;
                    this.printPanStartY = e.clientY - this.printPanY;
                },
                printDoPan(e) {
                    if (this.printDragging) return;
                    if (!this.printPanning) return;
                    this.printPanX = e.clientX - this.printPanStartX;
                    this.printPanY = e.clientY - this.printPanStartY;
                    this._updateCenterGuide();
                },
                printEndPan(e) {
                    this.printPanning = false;
                    this._hideCenterGuide();
                },

                _updateCenterGuide() {
                    const sheet = document.getElementById('print-paper-sheet');
                    const tree = document.getElementById('print-preview-tree');
                    if (!sheet || !tree) return;

                    const sheetRect = sheet.getBoundingClientRect();
                    const treeRect = tree.getBoundingClientRect();
                    const sheetCenterX = sheetRect.left + sheetRect.width / 2;
                    const treeCenterX = treeRect.left + treeRect.width / 2;
                    const diff = Math.abs(sheetCenterX - treeCenterX);

                    let guide = document.getElementById('center-guide-line');
                    if (diff < 10) {
                        // Snap and show guide
                        if (!guide) {
                            guide = document.createElement('div');
                            guide.id = 'center-guide-line';
                            guide.style.cssText = 'position:absolute;top:0;bottom:0;width:2px;background:red;z-index:999;pointer-events:none;opacity:0.7;';
                            sheet.appendChild(guide);
                        }
                        guide.style.left = '50%';
                        guide.style.display = 'block';
                    } else if (guide) {
                        guide.style.display = 'none';
                    }
                },
                _hideCenterGuide() {
                    const guide = document.getElementById('center-guide-line');
                    if (guide) guide.style.display = 'none';
                },
                printZoom(e) {
                    e.preventDefault();
                    const delta = -e.deltaY;
                    if (delta > 0) {
                        this.printScale = Math.min(this.printScale * 1.02, 3); // 2% zoom step
                    } else {
                        this.printScale = Math.max(this.printScale / 1.02, 0.1); // 2% zoom step
                    }
                },

                async exportPNG() {
                    this.printExporting = true;
                    try {
                        await this._exportAsImage('png');
                    } catch (err) {
                        console.error('Export PNG failed:', err);
                        alert('Lỗi xuất ảnh: ' + err.message);
                    } finally {
                        this.printExporting = false;
                    }
                },

                async exportPDF() {
                    this.printExporting = true;
                    try {
                        await this._exportAsImage('pdf');
                    } catch (err) {
                        console.error('Export PDF failed:', err);
                        alert('Lỗi xuất PDF: ' + err.message);
                    } finally {
                        this.printExporting = false;
                    }
                },

                async _exportAsImage(format) {
                    // === WYSIWYG APPROACH: Clone the entire print-paper-sheet ===
                    // Export EXACTLY what the user sees in the preview.
                    // Clone sheet → inline styles → set real paper size → capture.

                    const sheetEl = document.getElementById('print-paper-sheet');
                    if (!sheetEl) throw new Error('Không tìm thấy tờ giấy.');

                    // 1. Calculate actual paper dimensions (not viewport-scaled)
                    let paperW, paperH, mmW, mmH;
                    if (this.selectedPaperSize === 'auto') {
                        // For auto, calculate from actual tree content + title + padding
                        const treeEl = document.getElementById('print-preview-tree');
                        const PADDING = 80;
                        const TITLE_H = 120;
                        if (treeEl) {
                            const treeW = treeEl.scrollWidth;
                            const treeH = treeEl.scrollHeight;
                            paperW = treeW + PADDING * 2;
                            paperH = treeH + PADDING * 2 + TITLE_H;
                        } else {
                            paperW = sheetEl.scrollWidth;
                            paperH = sheetEl.scrollHeight;
                        }
                        mmW = paperW * 25.4 / 96;
                        mmH = paperH * 25.4 / 96;
                    } else {
                        const size = this.PAPER_SIZES[this.selectedPaperSize];
                        if (this.paperOrientation === 'landscape') {
                            mmW = size.h; mmH = size.w;
                        } else {
                            mmW = size.w; mmH = size.h;
                        }
                        paperW = this.mmToPx(mmW);
                        paperH = this.mmToPx(mmH);
                    }
                    console.log('[EXPORT] Paper size:', paperW, 'x', paperH, 'mm:', mmW, 'x', mmH);

                    // 2. Clone the ENTIRE sheet
                    const clone = sheetEl.cloneNode(true);

                    // 3. Inline computed styles from live DOM to clone
                    const origEls = sheetEl.querySelectorAll('*');
                    const cloneEls = clone.querySelectorAll('*');

                    const KEY_PROPS = [
                        'display','position','top','left','right','bottom','width','height',
                        'min-width','min-height','max-width','max-height',
                        'padding','padding-top','padding-bottom','padding-left','padding-right',
                        'margin','margin-top','margin-bottom','margin-left','margin-right',
                        'flex-direction','flex-wrap','align-items','justify-content','gap',
                        'flex-shrink','flex-grow','flex-basis',
                        'background-color','background-image','background-size','background-position',
                        'background-repeat',
                        'color','font-family','font-size','font-weight','font-style',
                        'text-align','text-transform','text-shadow','text-decoration',
                        'letter-spacing','line-height','white-space','writing-mode','text-orientation',
                        'border','border-width','border-style','border-color','border-radius',
                        'border-top-width','border-top-style','border-top-color',
                        'border-bottom-width','border-bottom-style','border-bottom-color',
                        'border-left-width','border-left-style','border-left-color',
                        'border-right-width','border-right-style','border-right-color',
                        'box-shadow','opacity','z-index','overflow','overflow-x','overflow-y',
                        'transform','transform-origin',
                        'background-blend-mode','filter','pointer-events',
                    ];

                    // Inline styles on clone root (the sheet) 
                    const sheetCS = getComputedStyle(sheetEl);
                    clone.style.cssText = `position:relative;width:${paperW}px;height:${paperH}px;overflow:hidden;background:${sheetCS.backgroundColor};`;
                    clone.removeAttribute(':style');

                    // Inline styles on all children
                    for (let i = 0; i < origEls.length && i < cloneEls.length; i++) {
                        const cs = getComputedStyle(origEls[i]);
                        const cel = cloneEls[i];
                        let css = '';
                        for (const p of KEY_PROPS) {
                            const v = cs.getPropertyValue(p);
                            if (v && v !== '' && v !== 'none' && v !== 'normal' && v !== '0px' && v !== 'auto') {
                                css += `${p}:${v};`;
                            }
                        }
                        css += `display:${cs.getPropertyValue('display')};`;
                        // Preserve position for absolutely positioned elements
                        if (cs.position === 'absolute' || cs.position === 'fixed') {
                            css += `position:${cs.position};`;
                            css += `top:${cs.top};left:${cs.left};`;
                        }
                        cel.style.cssText = css;
                        
                        // Remove Alpine directives
                        cel.removeAttribute('x-show');
                        cel.removeAttribute('x-init');
                        cel.removeAttribute('x-effect');
                        cel.removeAttribute(':style');
                        cel.removeAttribute(':class');
                        cel.removeAttribute('@click');
                        cel.removeAttribute('@mousedown');
                        cel.removeAttribute('@wheel.prevent');
                    }

                    // 4. Embed all background images as base64
                    const allCloneEls = clone.querySelectorAll('*');
                    for (const el of [clone, ...allCloneEls]) {
                        const bg = el.style.backgroundImage;
                        if (bg && bg.includes('url(') && !bg.includes('data:')) {
                            const match = bg.match(/url\(['"]?([^'"]+)['"]?\)/);
                            if (match && match[1]) {
                                try {
                                    const base64 = await this.imageToBase64(match[1]);
                                    el.style.backgroundImage = `url("${base64}")`;
                                } catch(e) { /* skip */ }
                            }
                        }
                    }

                    // 4b. Ensure background fills full paper (zoomed to hide red borders)
                    const bgChild = clone.querySelector('div[style*="background-image"]');
                    if (bgChild) {
                        bgChild.style.position = 'absolute';
                        bgChild.style.top = '0';
                        bgChild.style.left = '0';
                        bgChild.style.width = '100%';
                        bgChild.style.height = '100%';
                        bgChild.style.backgroundSize = '115% 115%'; // zoom to crop red borders
                        bgChild.style.backgroundPosition = 'center center';
                        bgChild.style.opacity = '0.5';
                    }

                    // 4c. Ensure title is centered at top
                    const titleClone = clone.querySelector('.print-title-container');
                    if (titleClone) {
                        titleClone.style.position = 'absolute';
                        titleClone.style.top = '20px';
                        titleClone.style.left = '0';
                        titleClone.style.right = '0';
                        titleClone.style.width = '100%';
                        titleClone.style.display = 'flex';
                        titleClone.style.justifyContent = 'center';
                        titleClone.style.zIndex = '40';
                        titleClone.style.pointerEvents = 'none';
                    }

                    // 5. Append clone to body (offscreen)
                    const wrapper = document.createElement('div');
                    wrapper.style.cssText = `position:fixed;left:-99999px;top:0;width:${paperW}px;height:${paperH}px;overflow:visible;z-index:-1;`;
                    wrapper.appendChild(clone);
                    document.body.appendChild(wrapper);

                    await document.fonts.ready;
                    await new Promise(r => setTimeout(r, 300));

                    // 5b. FIT-TO-PAGE: Recalculate tree transform to fill the paper
                    const treeInClone = clone.querySelector('#print-preview-tree');
                    if (treeInClone) {
                        // Measure tree natural size at scale=1
                        const origTreeStyle = treeInClone.style.cssText;
                        treeInClone.style.transform = 'none';
                        treeInClone.style.position = 'absolute';
                        treeInClone.style.left = '0';
                        treeInClone.style.top = '0';
                        void treeInClone.offsetHeight; // force layout
                        await new Promise(r => setTimeout(r, 100));

                        // Measure ACTUAL content bounds from all nodes
                        const allNodes = treeInClone.querySelectorAll('[id^="node-"]');
                        const treeRect = treeInClone.getBoundingClientRect();
                        let minX = Infinity, maxX = 0, minY = Infinity, maxY = 0;
                        allNodes.forEach(n => {
                            const r = n.getBoundingClientRect();
                            minX = Math.min(minX, r.left - treeRect.left);
                            maxX = Math.max(maxX, r.right - treeRect.left);
                            minY = Math.min(minY, r.top - treeRect.top);
                            maxY = Math.max(maxY, r.bottom - treeRect.top);
                        });
                        
                        const contentW = maxX - minX;
                        const contentH = maxY - minY;
                        const contentCenterX = minX + contentW / 2; // center of actual content
                        const natW = treeInClone.scrollWidth;
                        const natH = treeInClone.scrollHeight;

                        // Get title height from the clone
                        const titleContainer = clone.querySelector('.print-title-container');
                        const titleH = titleContainer ? titleContainer.offsetHeight + 10 : 80;
                        const MARGIN = 15;

                        // Calculate available space and fit scale
                        const availW = paperW - MARGIN * 2;
                        const availH = paperH - titleH - MARGIN;
                        const fitScale = Math.min(availW / natW, availH / natH, 1.5);

                        // Center based on ACTUAL content center, not tree element center
                        const scaledContentCenterX = contentCenterX * fitScale;
                        const offsetX = (paperW / 2) - scaledContentCenterX;
                        const offsetY = titleH + MARGIN;

                        treeInClone.style.cssText = origTreeStyle;
                        treeInClone.style.transform = `translate(${offsetX}px, ${offsetY}px) scale(${fitScale})`;
                        treeInClone.style.transformOrigin = 'top left';
                        treeInClone.style.position = 'absolute';
                        treeInClone.style.left = '0';
                        treeInClone.style.top = '0';
                        treeInClone.style.overflow = 'visible';
                        
                        console.log('[EXPORT] Content bounds:', minX.toFixed(0), '-', maxX.toFixed(0), 'x', minY.toFixed(0), '-', maxY.toFixed(0));
                        console.log('[EXPORT] Fit-to-page: scale', fitScale.toFixed(3), 'offset', offsetX.toFixed(0), offsetY.toFixed(0));
                    }

                    await new Promise(r => setTimeout(r, 200));

                    // 6. Redraw SVG connections based on clone's actual positions
                    const treeClone = clone.querySelector('#print-preview-tree');
                    if (treeClone) {
                        const oldSvg = treeClone.querySelector('#preview-connection-layer');
                        if (oldSvg) oldSvg.remove();

                        const cloneNodes = treeClone.querySelectorAll('[data-parent-id]');
                        if (cloneNodes.length > 0) {
                            // Get the tree's scale from its transform matrix
                            const treeTransform = getComputedStyle(treeClone).transform;
                            let treeScale = 1;
                            if (treeTransform && treeTransform !== 'none') {
                                const m = treeTransform.match(/matrix\(([^)]+)\)/);
                                if (m) { treeScale = parseFloat(m[1].split(',')[0]) || 1; }
                            }
                            
                            const treeR = treeClone.getBoundingClientRect();
                            const getPos = (el) => {
                                const r = el.getBoundingClientRect();
                                // Convert from screen coords back to tree-local coords
                                return { 
                                    x: (r.left - treeR.left) / treeScale, 
                                    y: (r.top - treeR.top) / treeScale, 
                                    w: r.width / treeScale, 
                                    h: r.height / treeScale 
                                };
                            };

                            let maxX = 0, maxY = 0;
                            treeClone.querySelectorAll('[id^="node-"]').forEach(n => {
                                const p = getPos(n);
                                maxX = Math.max(maxX, p.x + p.w);
                                maxY = Math.max(maxY, p.y + p.h);
                            });

                            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                            svg.style.cssText = `position:absolute;top:0;left:0;width:${maxX+200}px;height:${maxY+200}px;pointer-events:none;overflow:visible;z-index:5;`;
                            treeClone.insertBefore(svg, treeClone.firstChild);

                            cloneNodes.forEach(node => {
                                const pid = node.getAttribute('data-parent-id');
                                const par = treeClone.querySelector('#' + pid);
                                if (par) {
                                    const pp = getPos(par);
                                    const np = getPos(node);
                                    const sx = pp.x + pp.w / 2, sy = pp.y + pp.h;
                                    const tx = np.x + np.w / 2, ty = np.y;
                                    const midY = sy + (ty - sy) * 0.5;

                                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                                    path.setAttribute('d', `M${sx},${sy} V${midY} H${tx} V${ty}`);
                                    path.setAttribute('fill', 'none');
                                    path.setAttribute('stroke', '#4b5563');
                                    path.setAttribute('stroke-width', '2.5');
                                    path.setAttribute('stroke-linecap', 'round');
                                    path.setAttribute('stroke-linejoin', 'round');
                                    svg.appendChild(path);
                                }
                            });
                        }
                    }

                    // 7. Capture the clone at paper dimensions
                    const CANVAS_LIMIT = 14500;
                    let captureScale = parseInt(this.exportQuality) || 1;
                    if (paperW * captureScale > CANVAS_LIMIT) captureScale = Math.floor(CANVAS_LIMIT / paperW * 10) / 10;
                    if (paperH * captureScale > CANVAS_LIMIT) captureScale = Math.min(captureScale, Math.floor(CANVAS_LIMIT / paperH * 10) / 10);

                    const dataUrl = await modernScreenshot.domToPng(clone, {
                        width: paperW,
                        height: paperH,
                        scale: captureScale,
                        backgroundColor: '#f5f0e8',
                    });

                    // Cleanup
                    document.body.removeChild(wrapper);

                    if (!dataUrl || dataUrl.length < 1000) {
                        throw new Error('Chụp ảnh thất bại.');
                    }
                    console.log('[EXPORT] Captured OK, length:', dataUrl.length);

                    // 8. Download
                    if (format === 'pdf') {
                        const { jsPDF } = window.jspdf;
                        const orientation = mmW > mmH ? 'l' : 'p';
                        const pdf = new jsPDF(orientation, 'mm', [mmW, mmH]);
                        pdf.addImage(dataUrl, 'PNG', 0, 0, mmW, mmH);
                        pdf.save(`gia-pha-${this.selectedPaperSize}-${new Date().toISOString().slice(0,10)}.pdf`);
                    } else {
                        const link = document.createElement('a');
                        link.download = `gia-pha-${this.selectedPaperSize}-${new Date().toISOString().slice(0,10)}.png`;
                        link.href = dataUrl;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                },

                _loadImage(src) {
                    return new Promise((resolve, reject) => {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = () => resolve(img);
                        img.onerror = reject;
                        img.src = src;
                    });
                },

                closePrintPreview() {
                    this.printPreviewActive = false;
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
            <div class="absolute bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-auto" x-show="!printPreviewActive">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-1 flex flex-col">
                    {{-- Calendar Button --}}
                    <button wire:click="$dispatch('open-important-dates')" class="relative p-2 hover:bg-gray-100 rounded text-gray-600 border-b border-gray-100" title="Lịch sự kiện">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        @if($hasUpcomingEvents)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] text-white font-bold animate-shake z-10 border border-white">!</span>
                        @endif
                    </button>
                    {{-- Print / Export Button --}}
                    <button @click="openPrintPreview()" class="p-2 hover:bg-red-50 rounded text-[#C41E3A] border-b border-gray-100" title="In Gia Phả">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button @click="scale *= 1.05" class="p-2 hover:bg-gray-100 rounded text-gray-600"
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
                    <button @click="scale /= 1.05" class="p-2 hover:bg-gray-100 rounded text-gray-600"
                        title="Zoom Out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Important Dates Modal moved to root --}}

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

        {{-- ====== EXPORT MODAL OVERLAY ====== --}}
        <div x-show="printPreviewActive" x-cloak
             class="fixed inset-0 z-[100] bg-black/60 flex items-center justify-center p-4"
             @keydown.escape.window="closePrintPreview()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col" @click.stop>
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h2 class="text-xl font-bold text-gray-800 font-serif text-[#C41E3A]">Xuất Gia Phả</h2>
                    <button @click="closePrintPreview()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="p-6 flex flex-col gap-6">
                    {{-- Tab Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung in</label>
                        <div class="flex items-center bg-gray-100 p-1.5 rounded-xl">
                            <button @click="printTab = 'tree'" class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all" :class="printTab === 'tree' ? 'bg-white shadow-sm text-[#C41E3A]' : 'text-gray-500 hover:text-gray-700'">Cây Gia Phả</button>
                            <button @click="printTab = 'calendar'" class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all" :class="printTab === 'calendar' ? 'bg-white shadow-sm text-[#C41E3A]' : 'text-gray-500 hover:text-gray-700'">Lịch Sự Kiện</button>
                        </div>
                    </div>

                    {{-- Paper Size & Orientation --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Khổ giấy</label>
                            <select x-model="selectedPaperSize" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl py-3 px-4 focus:ring-2 focus:ring-[#C41E3A]/20 focus:border-[#C41E3A] outline-none transition-all cursor-pointer font-medium">
                                <template x-for="(info, key) in PAPER_SIZES" :key="key">
                                    <option :value="key" x-text="info.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Độ sắc nét</label>
                            <select x-model="exportQuality" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl py-3 px-4 focus:ring-2 focus:ring-[#C41E3A]/20 focus:border-[#C41E3A] outline-none transition-all cursor-pointer font-medium">
                                <option value="1">Thường (Nhanh)</option>
                                <option value="2">Nét (HD)</option>
                                <option value="3">Siêu Nét (4K)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Orientation --}}
                    <div x-show="selectedPaperSize !== 'auto'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Chiều giấy</label>
                        <div class="flex items-center bg-gray-100 p-1.5 rounded-xl">
                            <button @click="paperOrientation = 'landscape'" class="flex flex-1 items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-bold transition-all" :class="paperOrientation === 'landscape' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" :disabled="selectedPaperSize === 'auto'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                Xoay Ngang
                            </button>
                            <button @click="paperOrientation = 'portrait'" class="flex flex-1 items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-bold transition-all" :class="paperOrientation === 'portrait' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" :disabled="selectedPaperSize === 'auto'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4v16M12 4v16M18 4v16" /></svg>
                                Xoay Dọc
                            </button>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button @click="closePrintPreview()" class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-800 rounded-xl transition-all shadow-sm">Hủy</button>
                    <button @click="exportPNG()" :disabled="printExporting" class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-xl shadow-md disabled:opacity-50 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Xuất PNG
                    </button>
                    <button @click="exportPDF()" :disabled="printExporting" class="flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#C41E3A] to-[#A01830] hover:from-[#A01830] hover:to-[#800020] rounded-xl shadow-md disabled:opacity-50 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        Xuất PDF
                    </button>
                </div>
            </div>

            {{-- Hidden Canvas Area for Export Snapshot --}}
            <div id="print-canvas-area" class="fixed top-[-9999px] left-[-9999px] opacity-0 pointer-events-none w-[2000px] h-[2000px] overflow-visible"
                 x-init="setTimeout(() => updateSheetPreview(), 500)"
                 x-effect="selectedPaperSize; paperOrientation; updateSheetPreview()">

                {{-- The Visual Paper Frame --}}
                <div id="print-paper-sheet" 
                     class="shadow-2xl relative bg-white transition-all duration-300 overflow-hidden shrink-0"
                     :style="selectedPaperSize === 'auto' 
                        ? `width: 100%; height: 100%; transform: none;` 
                        : `width: ${sheetW}px; height: ${sheetH}px; transform: scale(${sheetScale}); transform-origin: top left;`
                     ">

                    {{-- Background --}}
                    <div class="absolute inset-0 pointer-events-none"
                         style="background-image: url(/images/bg-dragon-scroll.jpg); background-size: cover; background-position: center; opacity: 0.5;"></div>

                    {{-- Title Header --}}
                    <div class="print-title-container absolute left-0 right-0 z-40 px-10 pointer-events-none select-none flex justify-center w-full"
                         :style="`top: ${getTitleTopMargin()}px`">
                        <div class="print-title-frame flex items-center justify-center transition-all duration-300 w-full overflow-visible">
                            <span class="font-serif font-bold uppercase tracking-[0.2em] block text-center text-[#C41E3A] whitespace-nowrap overflow-visible max-w-none"
                                  :class="getTitleFontSize()">
                                {{ $filters['treeTitle'] ?? 'Gia phả dòng họ Nguyễn' }}
                            </span>
                        </div>
                    </div>

                    {{-- Tree Content (Cloned) --}}
                    <div id="print-preview-tree" x-show="printTab === 'tree'"
                         class="absolute origin-top-left will-change-transform"
                         :style="`transform: translate(0px, 0px) scale(${printScale});`">
                    </div>

                    {{-- Calendar Content --}}
                    <div x-show="printTab === 'calendar'" class="absolute inset-0 pt-32 px-16 overflow-auto">
                        <div class="max-w-4xl mx-auto space-y-8">
                            <h2 class="text-3xl font-serif font-bold text-[#C41E3A] border-b-2 border-[#C41E3A]/20 pb-4 text-center">
                                DANH SÁCH NGÀY QUAN TRỌNG
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse(\App\Models\ImportantDate::orderBy('lunar_month')->orderBy('lunar_day')->get() as $date)
                                    <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl flex flex-col gap-2">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-xl text-gray-800">{{ $date->title }}</h3>
                                            <span class="text-xs font-serif text-[#C41E3A] uppercase tracking-wider">
                                                {{ $date->calendar === 'lunar' ? 'Âm lịch' : 'Dương lịch' }}
                                            </span>
                                        </div>
                                        <div class="flex flex-col gap-1 text-gray-600">
                                            <p class="text-lg">Ngày: <span class="font-bold text-indigo-600">{{ $date->lunar_day }}/{{ $date->lunar_month }}</span></p>
                                            <p class="text-sm italic opacity-75">Hàng năm vào ngày {{ $date->lunar_day }} tháng {{ $date->lunar_month }} ({{ $date->calendar === 'lunar' ? 'Âm lịch' : 'Dương lịch' }})</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center py-20 text-gray-400">
                                        Chưa có dữ liệu ngày quan trọng.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Export Loading Overlay (On top of Modal) --}}
            <div x-show="printExporting" class="absolute inset-0 bg-black/60 flex items-center justify-center z-[110] rounded-2xl" @click.stop>
                <div class="bg-white rounded-2xl px-8 py-6 shadow-2xl flex flex-col items-center gap-3">
                    <svg class="animate-spin h-10 w-10 text-[#C41E3A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-bold text-gray-800">Đang xuất file...</p>
                    <p class="text-sm text-gray-500">Vui lòng đợi trong giây lát</p>
                </div>
            </div>
        </div>
        {{-- ====== END EXPORT MODAL ====== --}}

    </div>

    <!-- UI Overlay Controls (Sidebars loaded via livewire) -->
    <div class="absolute inset-0 pointer-events-none flex justify-between z-30" data-print-hide>
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
    <div data-print-hide>
        <livewire:components.sidebar-right />
    </div>

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

    {{-- Important Dates Modal (Root Level) --}}
    <livewire:important-dates />
</div>
</div>
