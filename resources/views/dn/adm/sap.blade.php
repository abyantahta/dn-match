<!DOCTYPE html>
<html>

<head>
    <title>DN ADM SAP</title>
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
    <x-navbar-component>PCC MATCHING</x-navbar-component>


    <div class="container px-0 mt-2 ">
        <div class=" w-full  ">
            <h3 class="card-header p-3 text-3xl"><i class="fa fa-star"></i> PCC MATCHING</h3>
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

                <form action="{{ route('pcc.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="pdf" accept=".pdf" class="form-control">

                    <br>
                    <div class="flex gap-4">
                        <button class="btn btn-success"><i class="fa fa-file"></i> Import PCC (PDF)</button>
                        <a class="btn btn-warning font-bold flex gap-2 items-center justify-center" href="{{ url('export/transactions/sap') }}"><i class="fa fa-file"></i>Export Last 2 Days Transaction</a>
                    </div>

                </form>

            </div>


        </div>
        <div class="card  " style="margin-top:5px;">
            <div class="card-body overflow-x-scroll">
                <table id="dnTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Slip</th>
                            <th>Slip Seq</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>KD Lot No</th>
                            {{-- <th>Trip</th>
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
                            <th>Remark</th> --}}
                        </tr>

                        {{-- <tr>
                            <th><input type="text" placeholder="Search Plant Code" /></th>
                            <th><input type="text" placeholder="Search Shop Code" /></th>
                            <th><input type="text" placeholder="Search Part Category" /></th>
                            <th><input type="text" placeholder="Search Route" /></th>
                            <th><input type="text" placeholder="Search LP" /></th>
                            <th><input type="text" placeholder="Search Trip" /></th>
                            <th><input type="text" placeholder="Search Vendor Code" /></th>
                            <th><input type="text" placeholder="Search Vendor Alias" /></th>
                            <th><input type="text" placeholder="Search Vendor Site" /></th>
                            <th><input type="text" placeholder="Search Order No" /></th>
                            <th><input type="text" placeholder="Search PO Number" /></th>
                            <th><input type="text" placeholder="Search Calc. Date" /></th>
                            <th><input type="text" placeholder="Search Order Date" /></th>
                            <th><input type="text" placeholder="Search Order Time" /></th>
                            <th><input type="text" placeholder="Search Del. Date" /></th>
                            <th><input type="text" placeholder="Search Del. Time" /></th>
                            <th><input type="text" placeholder="Search Qty/Kbn" /></th>
                            <th><input type="text" placeholder="Search Order(Kbn)" /></th>
                            <th><input type="text" placeholder="Search Order(Pcs)" /></th>
                            <th><input type="text" placeholder="Search Qty Receive" /></th>
                            <th><input type="text" placeholder="Search Qty Balance" /></th>
                            <th><input type="text" placeholder="Search Cancel Status" /></th>
                            <th><input type="text" placeholder="Search Remark" /></th>
                        </tr> --}}
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                                <th>Date</th>
                                <th>Slip</th>
                                <th>Slip Seq</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>KD Lot No</th>
                                {{-- <th>Plant Code</th>
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
                                <th>Remark</th> --}}
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
                ajax: "{{ route('dn.adm.sap.data') }}",
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'slip_no',
                        name: 'slip_no'
                    },
                    {
                        data: 'pcc_count',
                        name: 'pcc_count'
                    },
                    {
                        data: 'part_no',
                        name: 'part_no'
                    },
                    {
                        data: 'part_name',
                        name: 'part_name'
                    },
                    {
                        data: 'kd_lot_no',
                        name: 'kd_lot_no'
                    },
                    // {
                    //     data: 'vendor_code',
                    //     name: 'vendor_code'
                    // },
                    // {
                    //     data: 'vendor_alias',
                    //     name: 'vendor_alias'
                    // },
                    // {
                    //     data: 'vendor_site',
                    //     name: 'vendor_site'
                    // },
                    // {
                    //     data: 'order_no',
                    //     name: 'order_no'
                    // },
                    // {
                    //     data: 'po_number',
                    //     name: 'po_number'
                    // },
                    // {
                    //     data: 'calc_date',
                    //     name: 'calc_date'
                    // },
                    // {
                    //     data: 'order_date',
                    //     name: 'order_date'
                    // },
                    // {
                    //     data: 'order_time',
                    //     name: 'order_time'
                    // },
                    // {
                    //     data: 'del_date',
                    //     name: 'del_date'
                    // },
                    // {
                    //     data: 'del_time',
                    //     name: 'del_time'
                    // },
                    // {
                    //     data: 'qty_kbn',
                    //     name: 'qty_kbn'
                    // },
                    // {
                    //     data: 'order_kbn',
                    //     name: 'order_kbn'
                    // },
                    // {
                    //     data: 'order_pcs',
                    //     name: 'order_pcs'
                    // },
                    // {
                    //     data: 'qty_receive',
                    //     name: 'qty_receive'
                    // },
                    // {
                    //     data: 'qty_balance',
                    //     name: 'qty_balance'
                    // },
                    // {
                    //     data: 'cancel_status',
                    //     name: 'cancel_status'
                    // },
                    // {
                    //     data: 'remark',
                    //     name: 'remark'
                    // }
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
