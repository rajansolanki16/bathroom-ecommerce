    <!-- Delete Confirmation Modal -->
<div id="deleteRecordModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="bi bi-trash display-4"></i>
                    </div>

                    <div class="mt-4">
                        <h3 class="mb-2">Are you sure?</h3>
                        <p class="mx-3 mb-0 text-muted fs-lg">
                            Are you sure you want to remove this record <b>permanently</b>?
                        </p>
                    </div>
                </div>

                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2 mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light"
                            data-bs-dismiss="modal">
                            No
                        </button>
                        <button type="submit" class="btn w-sm btn-danger">
                            Yes, Delete
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function setDeleteFormAction(element) {
        const deleteUrl = element.getAttribute('data-delete-url');
        document.getElementById('deleteForm').action = deleteUrl;
    }
</script>
