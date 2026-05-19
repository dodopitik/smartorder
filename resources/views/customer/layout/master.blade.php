@include('customer.layout.__header')

<body>
    <div id="spinner"
        class="w-100 vh-100 position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center"
        style="background:#E9E0D7; z-index:9999;">
        <img src="{{ asset('assets/logo/archana1.png') }}" alt="Archana Smart App" class="spinner-logo">
    </div>

    @include('customer.layout.__navbar')

    @yield('content')

    <div class="container-fluid copyright tenant-footer py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span><a href="#"><i
                                class="fas fa-copyright me-2"></i>{{ $currentTenant?->name ?? 'Happy Living' }}</a>
                        <span id="currentYear"></span>. All right reserved.</span>
                </div>
                <div class="col-md-6 my-auto text-center text-md-end">
                    <a href="{{ route('platform.tnc') }}" class="text-decoration-none me-3" style="opacity: 0.85;">Syarat & Ketentuan</a>
                    {{ $currentTenant?->tagline ?? 'Platform multi tenant untuk order dan operasional outlet.' }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/customer/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/customer/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/customer/js/main.js') }}"></script>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        window.addEventListener('load', function() {
            setTimeout(() => {
                const spinner = document.getElementById('spinner');
                if (spinner) {
                    spinner.style.opacity = '0';
                    spinner.style.pointerEvents = 'none';
                }
            }, 600);
        });
    </script>
    @yield('scripts')
</body>

</html>
