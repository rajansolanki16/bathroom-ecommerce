<x-admin.header :title="'Transactions'" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" >
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" >

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-4 card-title">Transactions</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="fixed-header" class="table align-middle table-bordered dt-responsive nowrap table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>TRANSACTION ID</th>
                            <th>MODE</th>
                            <th>METHOD</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>Create Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($transactions))
                            @foreach ($transactions as $transaction)
                                @php
                                    $mode = App\Models\Booking::where('transaction_id', $transaction->transaction_id)->value('type');
                                @endphp
                                <tr>
                                    <td>{{ $transaction->transaction_id}}</td>
                                    <td>{{ $mode}}</td>
                                    <td>{{ $transaction->method }}</td>
                                    <td>{{ $transaction->amount}}</td>
                                    <td>
                                        @if($transaction->status == 1)
                                            <span class="badge bg-success-subtle text-success status">PAID</span>
                                        @elseif ($transaction->status == 0)
                                            <span class="badge bg-danger-subtle text-danger status">CANCLED</span>
                                        @elseif ($transaction->status == 2)
                                            @if ($transaction->method == 'CASH')
                                                <span class="badge bg-warning-subtle text-warning status">PENDING</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning status">PROCESSING</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-admin.footer />