<!DOCTYPE html>
<html>

<head>
    <title>DN ADM KEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
        {{-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="icon" href="{{ asset('logo.png') }}">

    <style>
        .maxWidth {
            max-width: 100%;
        }
    </style>
    {{-- Menu top nav --}}

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: black;
            /* display: block */
        }

        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 3px 3px;
            text-decoration: none;
            font-size: 14px;
            background-color: black
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }

        .topnav .icon {
            display: none;
        }

        @media screen and (max-width: 600px) {
            .topnav a:not(:first-child) {
                display: none;
            }

            .topnav a.icon {
                float: right;
                display: block;
            }
        }

        @media screen and (max-width: 600px) {
            .topnav.responsive {
                position: relative;
            }

            .topnav.responsive .icon {
                position: absolute;
                right: 0;
                top: 0;
            }

            .topnav.responsive a {
                float: none;
                display: block;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    {{-- <h1>haloo</h1> --}}
    <x-navbar-component>DN ADM KEP</x-navbar-component>


    <div class="container px-0 mt-2 ">
        <div class=" w-full  ">
            <h3 class="card-header p-3 text-3xl"><i class="fa fa-star"></i> DN ADM KEP</h3>
            <div class="card-body">

                @session('success')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}
                    </div>
                @endsession
                @session('error')
                    <div class="alert alert-danger" role="alert">
                        {{ $value }}
                    </div>
                @endsession

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dn.adm.kep.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <div class="flex gap-4">
                        <button class="btn btn-success"><i class="fa fa-file"></i> Import DN ADM KEP</button>
                        <a class="btn btn-warning font-bold flex gap-2 items-center justify-center" href="{{ url('export/transactions/kep') }}"><i class="fa fa-file"></i>Export Last 2 Days Transaction</a>
                    </div>

                </form>

                {{-- <form id="importForm" action="{{ route('dn.adm.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button type="button" class="btn btn-success" id="previewButton"><i class="fa fa-file"></i> Import
                        DN Data</button>
                </form> --}}

            </div>


        </div>
        <div class="card  " style="margin-top:5px;">
            <div class="card-body overflow-x-scroll">
                <table id="dnTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Dn No</th>
                            <th>Vendor No</th>
                            <th>Vendor Alias</th>
                            <th>Vendor Site</th>
                            <th>Vendor Site_alias</th>
                            {{-- <th>Trans Code</th>
                            <th>Trans Route</th>
                            <th>Anbunka Date</th>
                            <th>Anbunka Shift</th> --}}
                            <th>Order No</th>
                            <th>Order Date</th>
                            <th>Order Time</th>
                            <th>Order Cycle</th>
                            <th>Shop Code</th>
                            <th>Plant Code</th>
                            <th>Part Cat</th>
                            <th>WH No</th>
                            <th>Del Type</th>
                            <th>Del Date</th>
                            <th>Del Time</th>
                            <th>Del Cycle</th>
                            {{-- <th>Dock No</th>
                            <th>Park No</th> --}}
                            <th>Total Kanban</th>
                            {{-- <th>Printed</th> --}}
                            <th>Receive Status</th>
                            <th>DN Type</th>
                            <th>Receive Method</th>
                            <th>Receive Date</th>
                            <th>Receive by</th>
                            <th>Status</th>
                            {{-- <th>No</th>
                            <th>Ext Core</th> --}}
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Job No</th>
                            <th>Qty Box</th>
                            <th>Qty Kanban</th>
                            <th>Qty Order</th>
                            <th>Qty Recieve</th>
                            <th>Qty Balance</th>
                            {{-- <th>Partial Status</th>
                            <th>Updated Date</th>
                            <th>Disable Date</th> --}}
                            <th>Order Status</th>
                            <th>DN - Part No</th>
                        </tr>

                        <tr>
            <th><input type="text" placeholder="Search Dn No"/></th>
            <th><input type="text" placeholder="Search Vendor No"/></th>
            <th><input type="text" placeholder="Search Vendor Alias"/></th>
            <th><input type="text" placeholder="Search Vendor Site"/></th>
            <th><input type="text" placeholder="Search Vendor Site_alias"/></th>
            {{-- <th><input type="text" placeholder="Search Trans Code"/></th>
            <th><input type="text" placeholder="Search Trans Route"/></th>
            <th><input type="text" placeholder="Search Anbunka Date"/></th>
            <th><input type="text" placeholder="Search Anbunka Shift"/></th> --}}
            <th><input type="text" placeholder="Search Order No"/></th>
            <th><input type="text" placeholder="Search Order Date"/></th>
            <th><input type="text" placeholder="Search Order Time"/></th>
            <th><input type="text" placeholder="Search Order Cycle"/></th>
            <th><input type="text" placeholder="Search Shop Code"/></th>
            <th><input type="text" placeholder="Search Plant Code"/></th>
            <th><input type="text" placeholder="Search Part Cat"/></th>
            <th><input type="text" placeholder="Search WH No"/></th>
            <th><input type="text" placeholder="Search Del Type"/></th>
            <th><input type="text" placeholder="Search Del Date"/></th>
            <th><input type="text" placeholder="Search Del Time"/></th>
            <th><input type="text" placeholder="Search Del Cycle"/></th>
            {{-- <th><input type="text" placeholder="Search Dock No"/></th>
            <th><input type="text" placeholder="Search Park No"/></th> --}}
            <th><input type="text" placeholder="Search Total Kanban"/></th>
            {{-- <th><input type="text" placeholder="Search Printed"/></th> --}}
            <th><input type="text" placeholder="Search Receive Status"/></th>
            <th><input type="text" placeholder="Search DN Type"/></th>
            <th><input type="text" placeholder="Search Receive Method"/></th>
            <th><input type="text" placeholder="Search Receive Date"/></th>
            <th><input type="text" placeholder="Search Receive by"/></th>
            <th><input type="text" placeholder="Search Status"/></th>
            {{-- <th><input type="text" placeholder="Search No"/></th>
            <th><input type="text" placeholder="Search Ext Core"/></th> --}}
            <th><input type="text" placeholder="Search Part No"/></th>
            <th><input type="text" placeholder="Search Part Name"/></th>
            <th><input type="text" placeholder="Search Job No"/></th>
            <th><input type="text" placeholder="Search Qty Box"/></th>
            <th><input type="text" placeholder="Search Qty Kanban"/></th>
            <th><input type="text" placeholder="Search Qty Order"/></th>
            <th><input type="text" placeholder="Search Qty Recieve"/></th>
            <th><input type="text" placeholder="Search Qty Balance"/></th>
            {{-- <th><input type="text" placeholder="Search Partial Status"/></th>
            <th><input type="text" placeholder="Search Updated Date"/></th>
            <th><input type="text" placeholder="Search Disable Date"/></th> --}}
            <th><input type="text" placeholder="Search Order Status"/></th>
            <th><input type="text" placeholder="Search DN - Part No"/></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <!-- Modal for Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Data DN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="previewTable">
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
                                <!-- Tambahkan kolom lain sesuai kebutuhan -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi menggunakan JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="importForm" action="{{ route('dn.adm.save') }}" method="POST">
                        @csrf
                        <input type="hidden" name="file" id="fileInput">
                        <button type="submit" class="btn btn-success">Simpan Data ke Database</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Modal for Preview -->
    <div class="modal fade " id="previewModalX" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Data DN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="previewTable">
                        <thead>
                            <tr>
                                <th>Dn No</th>;
                                <th>Vendor No</th>;
                                <th>Vendor Alias</th>;
                                <th>Vendor Site</th>;
                                <th>Vendor Site_alias</th>;
                                {{-- <th>Trans Code</th>;
                                <th>Trans Route</th>;
                                <th>Anbunka Date</th>;
                                <th>Anbunka Shift</th>; --}}
                                <th>Order No</th>;
                                <th>Order Date</th>;
                                <th>Order Time</th>;
                                <th>Order Cycle</th>;
                                <th>Shop Code</th>;
                                <th>Plant Code</th>;
                                <th>Part Cat</th>;
                                <th>WH No</th>;
                                <th>Del Type</th>;
                                <th>Del Date</th>;
                                <th>Del Time</th>;
                                <th>Del Cycle</th>;
                                {{-- <th>Dock No</th>;
                                <th>Park No</th>; --}}
                                <th>Total Kanban</th>;
                                {{-- <th>Printed</th>; --}}
                                <th>Receive Status</th>;
                                <th>DN Type</th>;
                                <th>Receive Method</th>;
                                <th>Receive Date</th>;
                                <th>Receive by</th>;
                                <th>Status</th>;
                                {{-- <th>No</th>;
                                <th>Ext Core</th>; --}}
                                <th>Part No</th>;
                                <th>Part Name</th>;
                                <th>Job No</th>;
                                <th>Qty Box</th>;
                                <th>Qty Kanban</th>;
                                <th>Qty Order</th>;
                                <th>Qty Recieve</th>;
                                <th>Qty Balance</th>;
                                {{-- <th>Partial Status</th>;
                                <th>Updated Date</th>;
                                <th>Disable Date</th>; --}}
                                <th>Order Status</th>;
                                <th>DN - Part No</th>
                                <!-- Tambahkan kolom lain sesuai kebutuhan -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi menggunakan JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirmImport">Simpan Data ke
                        Database</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Set focus on the barcode input field when the page loads
            $('#barcode').focus();

            // Reset focus to barcode input field after form submission or other actions
            $('form').on('submit', function() {
                setTimeout(function() {
                    $('#barcode').focus();
                }, 100); // Delay slightly to ensure focus resets
            });
        });
    </script>
    <!-- Tambahkan jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#dnTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dn.adm.kep.data') }}",
                columns : [
                    {
                        data : 'dn_no',
                        name : 'dn_no'
                    },
                    {
                        data : 'vendor_no',
                        name : 'vendor_no'
                    },
                    {
                        data : 'vendor_alias',
                        name : 'vendor_alias'
                    },
                    {
                        data : 'vendor_site',
                        name : 'vendor_site'
                    },
                    {
                        data : 'vendor_site_alias',
                        name : 'vendor_site_alias'
                    },
                    // {
                    //     data : 'trans_code',
                    //     name : 'trans_code'
                    // },
                    // {
                    //     data : 'trans_route',
                    //     name : 'trans_route'
                    // },
                    // {
                    //     data : 'anbunka_date',
                    //     name : 'anbunka_date'
                    // },
                    // {
                    //     data : 'anbunka_shift',
                    //     name : 'anbunka_shift'
                    // },
                    {
                        data : 'order_no',
                        name : 'order_no'
                    },
                    {
                        data : 'order_date',
                        name : 'order_date'
                    },
                    {
                        data : 'order_time',
                        name : 'order_time'
                    },
                    {
                        data : 'order_cycle',
                        name : 'order_cycle'
                    },
                    {
                        data : 'shop_code',
                        name : 'shop_code'
                    },
                    {
                        data : 'plant_code',
                        name : 'plant_code'
                    },
                    {
                        data : 'part_cat',
                        name : 'part_cat'
                    },
                    {
                        data : 'wh_no',
                        name : 'wh_no'
                    },
                    {
                        data : 'del_type',
                        name : 'del_type'
                    },
                    {
                        data : 'del_date',
                        name : 'del_date'
                    },
                    {
                        data : 'del_time',
                        name : 'del_time'
                    },
                    {
                        data : 'del_cycle',
                        name : 'del_cycle'
                    },
                    // {
                    //     data : 'dock_no',
                    //     name : 'dock_no'
                    // },
                    // {
                    //     data : 'park_no',
                    //     name : 'park_no'
                    // },
                    {
                        data : 'total_kanban',
                        name : 'total_kanban'
                    },
                    // {
                    //     data : 'printed',
                    //     name : 'printed'
                    // },
                    {
                        data : 'receive_status',
                        name : 'receive_status'
                    },
                    {
                        data : 'dn_type',
                        name : 'dn_type'
                    },
                    {
                        data : 'receive_method',
                        name : 'receive_method'
                    },
                    {
                        data : 'receive_date',
                        name : 'receive_date'
                    },
                    {
                        data : 'receive_by',
                        name : 'receive_by'
                    },
                    {
                        data : 'status',
                        name : 'status'
                    },
                    // {
                    //     data : 'no',
                    //     name : 'no'
                    // },
                    // {
                    //     data : 'ext_core',
                    //     name : 'ext_core'
                    // },
                    {
                        data : 'part_no',
                        name : 'part_no'
                    },
                    {
                        data : 'part_name',
                        name : 'part_name'
                    },
                    {
                        data : 'job_no',
                        name : 'job_no'
                    },
                    {
                        data : 'qty_box',
                        name : 'qty_box'
                    },
                    {
                        data : 'qty_kanban',
                        name : 'qty_kanban'
                    },
                    {
                        data : 'qty_order',
                        name : 'qty_order'
                    },
                    {
                        data : 'qty_recieve',
                        name : 'qty_recieve'
                    },
                    {
                        data : 'qty_balance',
                        name : 'qty_balance'
                    },
                    // {
                    //     data : 'partial_status',
                    //     name : 'partial_status'
                    // },
                    // {
                    //     data : 'updated_date',
                    //     name : 'updated_date'
                    // },
                    // {
                    //     data : 'disable_date',
                    //     name : 'disable_date'
                    // },
                    {
                        data : 'order_status',
                        name : 'order_status'
                    },
                    {
                        data : 'dn_job_no',
                        name : 'dn_job_no'
                    }
                        ]
            });

            // Setup - add a text input to each footer cell
            $('#demoTable thead tr:eq(1) th').each(function(i) {
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });
        });
    </script>
</body>

</html>
