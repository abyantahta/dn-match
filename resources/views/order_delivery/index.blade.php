@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Order Delivery Data</h1>

        <!-- Form Upload -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Upload Order Delivery File</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('import.order.delivery') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Choose Excel File</label>
                        <input type="file" name="file" id="file" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card">
            <div class="card-header">
                <h5>Order Delivery List</h5>
            </div>
            <div class="card-body">
                <table id="orderDeliveryTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Plant Code</th>
                            <th>Shop Code</th>
                            <th>Part Category</th>
                            <th>Route</th>
                            <th>LP</th>
                            <th>Trip</th>
                            <th>Vendor Code</th>
                            <th>Vendor Alias</th>
                            <th>Vendor Site</th>
                            <th>Order No</th>
                            <th>PO Number</th>
                            <th>Calc. Date</th>
                            <th>Order Date</th>
                            <th>Order Time</th>
                            <th>Del. Date</th>
                            <th>Del. Time</th>
                            <th>Qty/Kbn</th>
                            <th>Order(Kbn)</th>
                            <th>Order(Pcs)</th>
                            <th>Qty Receive</th>
                            <th>Qty Balance</th>
                            <th>Cancel Status</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Datatables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
                    $(document).ready(function() {
                        $('#orderDeliveryTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('getdn') }}", // Pastikan URL ini benar
                                type: 'GET', // Pastikan method request sesuai
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest' // Pastikan header ini ada
                                },
                                error: function(xhr, error, code) {
                                    console.log(xhr, error, code); // Log error untuk debugging
                                }
                            },
                            columns: [{
                                    data: 'plant_code',
                                    name: 'plant_code'
                                },
                                {
                                    data: 'shop_code',
                                    name: 'shop_code'
                                },
                                {
                                    data: 'part_category',
                                    name: 'part_category'
                                },
                                {
                                    data: 'route',
                                    name: 'route'
                                },
                                {
                                    data: 'lp',
                                    name: 'lp'
                                },
                                {
                                    data: 'trip',
                                    name: 'trip'
                                },
                                {
                                    data: 'vendor_code',
                                    name: 'vendor_code'
                                },
                                {
                                    data: 'vendor_alias',
                                    name: 'vendor_alias'
                                },
                                {
                                    data: 'vendor_site',
                                    name: 'vendor_site'
                                },
                                {
                                    data: 'order_no',
                                    name: 'order_no'
                                },
                                {
                                    data: 'po_number',
                                    name: 'po_number'
                                },
                                {
                                    data: 'calc_date',
                                    name: 'calc_date'
                                },
                                {
                                    data: 'order_date',
                                    name: 'order_date'
                                },
                                {
                                    data: 'order_time',
                                    name: 'order_time'
                                },
                                {
                                    data: 'del_date',
                                    name: 'del_date'
                                },
                                {
                                    data: 'del_time',
                                    name: 'del_time'
                                },
                                {
                                    data: 'qty_kbn',
                                    name: 'qty_kbn'
                                },
                                {
                                    data: 'order_kbn',
                                    name: 'order_kbn'
                                },
                                {
                                    data: 'order_pcs',
                                    name: 'order_pcs'
                                },
                                {
                                    data: 'qty_receive',
                                    name: 'qty_receive'
                                },
                                {
                                    data: 'qty_balance',
                                    name: 'qty_balance'
                                },
                                {
                                    data: 'cancel_status',
                                    name: 'cancel_status'
                                },
                                {
                                    data: 'remark',
                                    name: 'remark'
                                }
                            ],
                            order: [
                                [0, 'asc']
                            ],
                            pageLength: 10,
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            initComplete: function() {
                                this.api().columns().every(function() {
                                    var column = this;
                                    var input = document.createElement("input");
                                    $(input).appendTo($(column.header()).empty())
                                        .on('keyup change clear', function() {
                                            if (column.search() !== this.value) {
                                                column.search(this.value).draw();
                                            }
                                        });
                                });
                            }
                        });
                    });
    </script>
@endpush

@push('styles')
    <!-- Datatables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
