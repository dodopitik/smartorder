@include('admin.layout.__header')

@php
    $authUser = Auth::user();
    $isSuperAdminLayout = $authUser?->role?->role_name === 'super_admin';
    $panelTitle = trim($__env->yieldContent('title')) ?: ($isSuperAdminLayout ? 'Platform Console' : 'Admin Panel');
    $panelSubtitle = $isSuperAdminLayout
        ? 'Kelola tenant, owner, billing, dan performa platform dari satu tempat.'
        : (($currentTenant?->name ? 'Kelola operasional tenant ' . $currentTenant->name . ' dengan tampilan yang lebih rapi.' : 'Kelola operasional tenant dari panel admin.'));
    $userInitial = strtoupper(substr($authUser?->fullname ?? $authUser?->username ?? 'A', 0, 1));
@endphp

<body>
    <script src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>

    <div id="app">
        @include('admin.layout.__sidebar')

        {{-- Backdrop overlay untuk sidebar mobile --}}
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <div id="main">
            {{-- Mobile App Bar (tampil hanya di < xl) --}}
            <header class="admin-mobile-bar d-flex d-xl-none">
                <button type="button" class="admin-burger" id="adminBurger" aria-label="Buka menu navigasi">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="admin-mobile-brand">
                    <span class="admin-mobile-eyebrow">{{ $isSuperAdminLayout ? 'Super Admin' : 'Tenant Admin' }}</span>
                    <span class="admin-mobile-title">{{ $isSuperAdminLayout ? 'Platform Console' : ($currentTenant?->name ?? 'Admin Panel') }}</span>
                </div>

                <div class="admin-mobile-actions">
                    <button type="button" class="admin-mobile-theme" id="themeToggleMobile" aria-label="Ganti mode tampilan">
                        <i class="bi bi-moon-stars" id="themeToggleMobileIcon"></i>
                    </button>
                    <span class="admin-mobile-avatar">{{ $userInitial }}</span>
                </div>
            </header>

            <div class="admin-topbar">
                <div class="admin-topbar-meta">
                    <div>
                        <h1 class="admin-topbar-title">{{ $panelTitle }}</h1>
                        <p class="admin-topbar-subtitle">{{ $panelSubtitle }}</p>
                    </div>
                    <div class="admin-user-chip">
                        <span class="admin-user-avatar">{{ $userInitial }}</span>
                        <span class="admin-user-text">
                            <span class="admin-user-role">{{ $isSuperAdminLayout ? 'Super Admin' : ($authUser?->role?->role_name ?? 'Admin') }}</span>
                            <span class="admin-user-name">{{ $authUser?->fullname ?? $authUser?->username ?? 'Admin User' }}</span>
                        </span>
                    </div>
                </div>
                <button type="button" class="theme-toggle" id="themeToggle" aria-label="Ganti mode tampilan">
                    <i class="bi bi-moon-stars" id="themeToggleIcon"></i>
                    <span class="theme-toggle-label" id="themeToggleLabel">Dark Mode</span>
                </button>
            </div>

            @if (session('success') || session('error'))
                <div class="mb-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                </div>
            @endif

            @yield('content')

            @include('admin.layout.__footer')

        </div>
    </div>

    <script src="{{ asset('assets/admin/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/admin/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/compiled/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const root = document.documentElement;
            const toggleButton = document.getElementById('themeToggle');
            const toggleIcon = document.getElementById('themeToggleIcon');
            const toggleLabel = document.getElementById('themeToggleLabel');
            const toggleButtonMobile = document.getElementById('themeToggleMobile');
            const toggleIconMobile = document.getElementById('themeToggleMobileIcon');

            function applyTheme(theme) {
                root.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);

                const isDark = theme === 'dark';
                toggleButton?.classList.toggle('is-dark', isDark);

                if (toggleIcon) {
                    toggleIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
                }

                if (toggleLabel) {
                    toggleLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
                }

                if (toggleIconMobile) {
                    toggleIconMobile.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars';
                }
            }

            const initialTheme = localStorage.getItem('theme') || root.getAttribute('data-bs-theme') || 'light';
            applyTheme(initialTheme);

            function toggleTheme() {
                const currentTheme = root.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
                applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
            }

            toggleButton?.addEventListener('click', toggleTheme);
            toggleButtonMobile?.addEventListener('click', toggleTheme);

            /* ===== Sidebar Mobile Toggle ===== */
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            const burger = document.getElementById('adminBurger');
            const backdrop = document.getElementById('sidebarBackdrop');
            const sidebarHideBtn = document.querySelector('.sidebar-hide');

            function openSidebar() {
                sidebarWrapper?.classList.add('sidebar-open');
                backdrop?.classList.add('show');
                burger?.classList.add('is-active');
                document.body.classList.add('sidebar-locked');
            }

            function closeSidebar() {
                sidebarWrapper?.classList.remove('sidebar-open');
                backdrop?.classList.remove('show');
                burger?.classList.remove('is-active');
                document.body.classList.remove('sidebar-locked');
            }

            burger?.addEventListener('click', function(e) {
                e.preventDefault();
                if (sidebarWrapper?.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            backdrop?.addEventListener('click', closeSidebar);
            sidebarHideBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                closeSidebar();
            });

            // Tutup sidebar saat klik link navigasi (mobile)
            document.querySelectorAll('.sidebar-wrapper .menu .sidebar-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1200 && !link.hasAttribute('data-bs-toggle')) {
                        closeSidebar();
                    }
                });
            });

            // Tutup sidebar dengan tombol Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });

            // Reset state saat resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1200) closeSidebar();
            });
        });
    </script>

    @yield('scripts')

    {{-- Global: Browser Notification + Sound Alert untuk pesanan baru --}}
    @if ($currentTenant)
    <script>
    (function() {
        // Skip global polling jika halaman order sudah handle sendiri
        if (window.__ORDER_PAGE_ACTIVE) return;

        var POLL_URL = "{{ route('orders.checkNew', ['tenant' => $currentTenant->slug]) }}";
        var POLL_INTERVAL = 5000;
        var lastId = 0;
        var lastStatus = '';
        var audioCtx = null;
        var notificationPermission = typeof Notification !== 'undefined' ? Notification.permission : 'denied';

        if (typeof Notification !== 'undefined' && notificationPermission === 'default') {
            Notification.requestPermission().then(function(p) { notificationPermission = p; });
        }

        function playSound() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                var t = audioCtx.currentTime;
                function beep(freq, start, dur) {
                    var o = audioCtx.createOscillator();
                    var g = audioCtx.createGain();
                    o.connect(g); g.connect(audioCtx.destination);
                    o.type = 'sine'; o.frequency.value = freq;
                    g.gain.setValueAtTime(0.3, t + start);
                    g.gain.exponentialRampToValueAtTime(0.01, t + start + dur);
                    o.start(t + start); o.stop(t + start + dur);
                }
                beep(880, 0, 0.25);
                beep(1174, 0.15, 0.25);
                beep(1318, 0.3, 0.3);
            } catch(e) {}
        }

        function showBrowserNotification(msg) {
            if (notificationPermission === 'granted') {
                try {
                    var n = new Notification('Pesanan Baru!', {
                        body: msg,
                        icon: "{{ asset('assets/logo/archana1.png') }}",
                        tag: 'new-order', requireInteraction: true
                    });
                    n.onclick = function() {
                        window.focus();
                        window.location.href = "{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}";
                        n.close();
                    };
                    setTimeout(function() { n.close(); }, 15000);
                } catch(e) {}
            }
        }

        function showInAppToast(msg) {
            var c = document.getElementById('order-toast-container');
            if (!c) {
                c = document.createElement('div');
                c.id = 'order-toast-container';
                c.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:10px;';
                document.body.appendChild(c);
            }
            var t = document.createElement('div');
            t.style.cssText = 'background:#16a34a;color:#fff;padding:14px 20px;border-radius:12px;font-size:0.9rem;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.2);display:flex;align-items:center;gap:10px;animation:slideIn 0.3s ease;cursor:pointer;max-width:360px;';
            t.innerHTML = '<span style="font-size:1.3rem">🔔</span><span>' + msg + '</span>';
            t.onclick = function() { window.location.href = "{{ route('orders.index', ['tenant' => $currentTenant->slug]) }}"; };
            c.appendChild(t);
            setTimeout(function() {
                t.style.opacity = '0'; t.style.transform = 'translateX(100%)'; t.style.transition = 'all 0.3s ease';
                setTimeout(function() { t.remove(); }, 300);
            }, 8000);
        }

        function checkNewOrders() {
            fetch(POLL_URL + '?last_id=' + lastId + '&last_status=' + encodeURIComponent(lastStatus), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.has_new && lastId > 0) {
                    playSound();
                    showBrowserNotification('Ada pesanan baru masuk! Cek halaman pesanan.');
                    showInAppToast('Pesanan baru masuk! Klik untuk lihat.');
                }
                lastId = d.latest_id || lastId;
                lastStatus = d.latest_status || lastStatus;
            })
            .catch(function() {});
        }

        fetch(POLL_URL + '?last_id=0&last_status=', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            lastId = d.latest_id || 0;
            lastStatus = d.latest_status || '';
            setInterval(checkNewOrders, POLL_INTERVAL);
        })
        .catch(function() { setInterval(checkNewOrders, POLL_INTERVAL); });

        document.addEventListener('click', function unlock() {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            document.removeEventListener('click', unlock);
        }, { once: true });
    })();
    </script>
    <style>
        @keyframes slideIn { from { opacity:0; transform:translateX(100%); } to { opacity:1; transform:translateX(0); } }
    </style>
    @endif
</body>

</html>
