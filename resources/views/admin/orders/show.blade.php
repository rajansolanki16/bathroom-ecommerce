<x-admin.header :title="'order Details'" />

@include('components.admin.toast')
<div class="col-xl-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-nowrap">
            <h4 class="mb-0 card-title">Orders & Inquiries</h4>
            <div class="d-flex gap-2 align-items-center ms-3">
                <span class="badge bg-primary">Total: {{ $orders->total() ?? $orders->count() }}</span>
                <span class="badge bg-info">Showing: {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? ($orders->count() ?? 0) }}</span>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted">Here you can view, filter and export orders/inquiries.</p>

            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i> Export
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" data-export="csv" href="{{ route('orders.export', 'csv') }}?{{ http_build_query(request()->query()) }}">
                            <i class="bi bi-filetype-csv me-2"></i>Export CSV (filtered)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" data-export="excel" href="{{ route('orders.export', 'excel') }}?{{ http_build_query(request()->query()) }}">
                            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel (filtered)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <form method="GET" action="{{ route('orders.show') }}">
                    <div class="row g-3 align-items-end">

                        <div class="col-xxl">
                            <div class="search-box">
                                <input type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Search products and User...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl col-sm-6">
                            <select
                                class="form-control"
                                name="status"
                                data-choices
                                data-choices-search="true">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Date range -->
                        <div class="col-xxl col-sm-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>
                        <div class="col-xxl col-sm-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-xxl-auto col-sm-6">
                            <button class="btn btn-primary w-md">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>

                        <div class="col-xxl-auto col-sm-6">
                            <a href="{{ route('orders.show') }}" class="btn btn-light w-md">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
            <br>

            <div class="table-responsive">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Products</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                <div><strong>{{ $order->user->name ?? $order->name }}</strong></div>
                                <div class="text-muted small">{{ $order->user ? 'Registered' : 'Guest' }}</div>
                            </td>
                            <td>
                                <div>{{ $order->email }}</div>
                                <div class="text-muted small">{{ $order->phone }}</div>
                            </td>
                            <td>
                                @if($order->items->count())
                                    @foreach($order->items as $item)
                                        <div><strong>{{ $item->product?->product_title ?? 'N/A' }}</strong> <small class="text-muted">x{{ $item->quantity ?? 1 }}</small></div>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ number_format($order->total, 2) }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select order-status" data-id="{{ $order->id }}" style="width:160px;">
                                        @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" {{ optional($order->status)->value === $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span class="badge bg-secondary status-label-{{ $order->id }}">{{ optional($order->status)->label() }}</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm edit-notes-btn" data-id="{{ $order->id }}" data-notes="{{ e($order->internal_notes) }}" title="Edit internal notes">
                                        <i class="bi bi-chat-left-text{{ $order->internal_notes ? '-fill text-primary' : '' }}"></i>
                                    </button>
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
        </div>
    </div>
</div>


<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="notesForm">
        <div class="modal-header">
          <h5 class="modal-title" id="notesModalLabel">Edit Internal Notes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="notesOrderId" name="order_id" value="">
          <div class="mb-3">
            <label for="notesTextarea" class="form-label">Internal Notes (admin only)</label>
            <textarea id="notesTextarea" name="internal_notes" class="form-control" rows="6"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="saveNotesBtn" class="btn btn-primary">Save Notes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(function () {
    var token = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';

    window.routes = window.routes || {};
    window.routes.orderStatus = '{{ route("orders.status", ["order" => "___ORDER_ID___"]) }}';
    window.routes.orderNotes = '{{ route("orders.notes", ["order" => "___ORDER_ID___"]) }}';
    window.routes.ordersShow = '{{ route("orders.show") }}';
    window.routes.exportCsv = '{{ route("orders.export","csv") }}';
    window.routes.exportExcel = '{{ route("orders.export","excel") }}';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    });

    function bindOrderStatusHandlers() {
        $('.order-status').off('change.orderStatus').on('change.orderStatus', function () {
            var id = $(this).data('id');
            var val = $(this).val();
            var url = window.routes.orderStatus.replace('___ORDER_ID___', id);

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: { status: val },
                success: function (data) {
                    if (data.success) {
                        var $badge = $('.status-label-' + id);
                        if ($badge.length) $badge.text(data.status);
                        if (typeof showToast === 'function') showToast('Status updated to: ' + data.status, 'success');
                    } else {
                        if (typeof showToast === 'function') showToast(data.message || 'Failed to update status', 'danger');
                    }
                },
                error: function (xhr) {
                    var msg = 'Server error while updating status';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    if (typeof showToast === 'function') showToast(msg, 'danger');
                }
            });
        });
    }

    function bindNotesHandlers() {
        // Use delegated handler so it works after AJAX table refreshes
        $(document).off('click.editNotes', '.edit-notes-btn').on('click.editNotes', '.edit-notes-btn', function () {
            var id = $(this).data('id');
            var notes = $(this).data('notes') || '';
            console.log('Opening notes modal for order', id, 'notes:', notes);
            $('#notesOrderId').val(id);
            $('#notesTextarea').val(notes);

            var modalEl = document.getElementById('notesModal');
            if (!modalEl) {
                console.error('notesModal element not found in DOM');
                if (typeof showToast === 'function') showToast('Notes modal not found (contact admin)', 'danger');
                return;
            }
            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                console.error('Bootstrap JS not loaded or bootstrap.Modal undefined');
                if (typeof showToast === 'function') showToast('Bootstrap JS not loaded (modal unavailable)', 'danger');
                return;
            }

            var bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
        });
    }

    $('#saveNotesBtn').off('click.saveNotes').on('click.saveNotes', function () {
        var id = $('#notesOrderId').val();
        var notes = $('#notesTextarea').val();
        var url = window.routes.orderNotes.replace('___ORDER_ID___', id);
        console.log('Saving notes for order', id, 'payload:', { internal_notes: notes, url: url });
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: { internal_notes: notes },
            success: function (data) {
                console.log('Notes save success response', data);
                if (data.success) {
                    var $btn = $('.edit-notes-btn[data-id="' + id + '"]');
                    $btn.data('notes', data.internal_notes).attr('data-notes', data.internal_notes);
                    var $icon = $btn.find('i');
                    if (data.internal_notes) {
                        $icon.removeClass('bi-chat-left-text').addClass('bi-chat-left-text-fill text-primary');
                    } else {
                        $icon.removeClass('bi-chat-left-text-fill text-primary').addClass('bi-chat-left-text');
                    }
                    var modalEl = document.getElementById('notesModal');
                    if (!modalEl) {
                        console.warn('notesModal not found when attempting to hide it');
                    } else if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                        console.warn('bootstrap.Modal not available; cannot hide modal programmatically');
                    } else {
                        var bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                    }
                    if (typeof showToast === 'function') showToast('Notes saved', 'success');
                } else {
                    console.warn('Notes save returned success=false', data);
                    if (typeof showToast === 'function') showToast(data.message || 'Failed to save notes', 'danger');
                }
            },
            error: function (xhr) {
                console.error('Notes save error', xhr);
                var msg = 'Server error while saving notes';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                if (typeof showToast === 'function') showToast(msg, 'danger');
            }
        });
    });

    function updateExportLinks(qs) {
        $('a[data-export="csv"]').attr('href', window.routes.exportCsv + (qs ? '?' + qs : ''));
        $('a[data-export="excel"]').attr('href', window.routes.exportExcel + (qs ? '?' + qs : ''));
    }

    function updateTableFromResponse(html, qs) {
        var $tmp = $('<div>').html(html);
        var $newTable = $tmp.find('#fixed-header');
        if ($newTable.length) {
            $('#fixed-header tbody').replaceWith($newTable.find('tbody'));
        }
        var $newInfo = $tmp.find('.d-flex.justify-content-between.align-items-center.mt-3');
        if ($newInfo.length) {
            $('.d-flex.justify-content-between.align-items-center.mt-3').replaceWith($newInfo);
        }
        updateExportLinks(qs);
        bindOrderStatusHandlers();
        bindNotesHandlers();
    }

    var $filterForm = $('form[action="{{ route('orders.show') }}"]');

    $filterForm.on('submit', function (e) {
        e.preventDefault();
        var qs = $(this).serialize();
        var url = window.routes.ordersShow + (qs ? '?' + qs : '');

        $.get(url).done(function (resp) {
            updateTableFromResponse(resp, qs);
            console.log('Results updated');
        }).fail(function (xhr) {
            var msg = 'Failed to fetch results';
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
            console.warn(msg);
        });
    });

    var debounceTimer;
    $('input[name="search"]').on('keyup', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () { $filterForm.submit(); }, 500);
    });

    $('a[href="{{ route('orders.show') }}"]').on('click', function (e) {
        e.preventDefault();
        $filterForm[0].reset();
        $filterForm.submit();
    });

    bindNotesHandlers();

    bindOrderStatusHandlers();
});
</script>


<x-admin.footer />