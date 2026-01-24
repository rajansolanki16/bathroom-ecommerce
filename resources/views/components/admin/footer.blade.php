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
                    Design & Develop by Vivid
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
</body>
</html>
