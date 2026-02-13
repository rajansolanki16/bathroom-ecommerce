<div wire:ignore.self class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-md-5">
                <div class="text-center">
                    <div class="text-danger">
                        <i class="ph-trash display-4"></i>
                    </div>

                    <div class="mt-4">
                        <h3 class="mb-2">Are you sure?</h3>
                        <p class="mx-3 mb-0 text-muted fs-lg">
                            Are you sure you want to remove this <b>{{ ucfirst($entityName) }}</b> <b>permanently</b>?
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4 mb-2">
                    <button type="button"
                            class="btn w-sm btn-light"
                            data-bs-dismiss="modal">
                        No
                    </button>

                    <button type="button"
                            class="btn w-sm btn-danger"
                            wire:click="delete">
                        Yes, Delete
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
