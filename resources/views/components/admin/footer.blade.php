<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © E-Commerce .
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Core JS -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/admin-script.js') }}"></script>
<script src="{{ asset('admin/js/app.js') }}"></script>


<!-- Plugins -->
<script src="{{ asset('admin/libs/list.js/list.min.js') }}"></script>
<script src="{{ asset('admin/libs/list.pagination.js/list.pagination.min.js') }}"></script>
<script src="{{ asset('admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('admin/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('admin/libs/flatpickr/flatpickr.min.js') }}"></script>


<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>


@stack('scripts')

</div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.getElementById('topnav-hamburger-icon');
    const html = document.documentElement;
    const body = document.body;

    if (hamburger) {
        hamburger.addEventListener('click', function () {
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                // Mobile: toggle the sidebar open/close via body class
                body.classList.toggle('vertical-sidebar-enable');
            } else {
                // Desktop: toggle between full (lg) and icon-only (sm)
                const currentSize = html.getAttribute('data-sidebar-size');
                if (currentSize === 'lg') {
                    html.setAttribute('data-sidebar-size', 'sm');
                } else {
                    html.setAttribute('data-sidebar-size', 'lg');
                }
            }
        });
    }

    // Close sidebar when overlay is clicked (mobile)
    const overlay = document.querySelector('.vertical-overlay');
    if (overlay) {
        overlay.addEventListener('click', function () {
            body.classList.remove('vertical-sidebar-enable');
        });
    }

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            body.classList.remove('vertical-sidebar-enable');
        }
    });
});

    </script>
</body>
</html>
