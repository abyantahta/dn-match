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

    <?php
    // dd($interlock);
    ?>
    <div class="container px-0 mt-2 ">
        <div class=" w-full  ">
            {{-- <h3 class="card-header p-3 text-3xl"><i class="fa fa-star"></i> PCC MATCHING</h3> --}}
            <div class="card-body px-0 mt-7">
                <h2 class="text-2xl font-bold  mb-2">Upload PCC</h2>

                @session('success')
                    <div class="alert alert-success" role="alert">
                        {!! session('success') !!}
                        @if (session('filename'))
                            <div class="mt-2">
                                <a href="{{ route('pcc.download', session('filename')) }}"
                                    class="text-blue-500 hover:text-blue-700 underline">
                                    Download modified PCC
                                </a>
                            </div>
                        @endif
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
                        <a class="exportButton btn btn-warning font-bold flex gap-2 items-center justify-center"
                            href="{{ url('export/transactions/sap?date_filter=' . $dateFilter . '$statusFilter=' . $statusFilter) }}"><i
                                class="fa fa-file"></i>Export Transaction</a>
                    </div>

                </form>

            </div>


        </div>
        <div class="flex justify-end gap-3 mb-3">
            <div class="w-28 max-w-sm min-w-[200px]">
                <div class="relative">
                    <input type="date" value="{{ $dateFilter ?? '' }}" placeholder=" Select Delivery Date"
                        class="tracking-widest w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer"
                        id="filter-date">

                </div>
            </div>
            <div class="w-full max-w-sm min-w-[200px]">
                <div class="relative">
                    <select value="{{ $statusFilter ?? '' }}" id="statusFilter"
                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                        <option value="">Select Status</option>
                        <option value='matched'>Matched</option>
                        <option value='unmatched'>Unmatched</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2"
                        stroke="currentColor" class="h-5 w-5 ml-1 absolute top-2.5 right-2.5 text-slate-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </div>
            </div>
            {{-- <input type="date" placeholder="Select Delivery Date" class="ml-auto border-3 text-md tracking-wider border-gray-500 py-2 px-4 rounded-md" id="filter-date"> --}}
        </div>
        <div class="card  " style="margin-top:5px;">
            <div class="card-body overflow-x-scroll">
                <table id="dnTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Slip</th>
                            <th>Slip Seq</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>KD Lot No</th>
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
                                <th>Status</th>
                                <th>Date</th>
                                <th>Slip</th>
                                <th>Slip Seq</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>KD Lot No</th>
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
                ajax: {
                    url: "{{ route('dn.adm.sap.data') }}",
                    data: function(d) {
                        d.created_at = $('#filter-date').val(); // Send selected date to server
                        d.pccStatus = $('#statusFilter').val();
                    }

                },
                columns: [{
                        data: 'isMatch',
                        name: 'isMatch'
                    }, {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'slip_barcode',
                        name: 'slip_barcode'
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
                ]
            });
            $('#filter-date, #statusFilter').change(function() {
                table.draw(); // Reload table when date changes
            });
            document.querySelector('.exportButton').addEventListener('click', function(e) {
                // Update hidden input values with current filter values
                var dateFilter = $('#filter-date').val()
                var statusFilter = $('#statusFilter').val()
                this.href = "{{ url('/export/transactions/sap') }}" + "?date_filter=" + dateFilter +
                    "&status_filter=" + statusFilter;
            });


            // $('#filter-status').change(function() {
            //     table.draw(); // Reload table when date changes
            // });

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
