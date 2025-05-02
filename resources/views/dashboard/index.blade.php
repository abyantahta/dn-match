<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Matching</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include CSS for DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include JS for DataTables -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

</head>

<body>

    <x-navbar-component>Kanban Matching</x-navbar-component>

    <div class="container flex justify-center items-center overflow-x-scroll mt-4">
        <!-- Transaction Summary Table -->
        {{-- <h5>Transaction Summary</h5> --}}
            <table class="w-[2000px] text-center mx-auto block" style="" id="dashboardTable">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Cycle</th>
                        <th class="text-center">Kanban ADM</th>
                        <th class="text-center">Label SDI</th>
                        <th class="text-center">Kanban Match</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Created At</th>
                    </tr>
                </thead>
            </table>
    </div>


    <!-- Add Bootstrap JS (optional, for Bootstrap components like modals or tooltips) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script>
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
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#dashboardTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                // bAutoWidth: false,
                autoWidth: false,
                ajax: '{{ route('dashboard.data') }}',
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        width: '150px'
                    },
                    {
                        data: 'del_cycle',
                        width: '150px'
                    },
                    {
                        data: 'no_dn',
                        width: '250'
                        // className: 'nowrap',
                        // width: '100px'
                    },
                    {
                        data: 'no_job',
                        orderable: false,
                        width: '250px'
                    },
                    {
                        data: 'kanban_match',
                        orderable: false,
                        width: '15%'
                    },
                    {
                        data: 'dn_status',
                        orderable: true,
                        width: '15%'
                    },
                    {
                        data: 'created_at',
                        orderable: true,
                        width: '15%'
                    },

                ],
                order: [
                    [6, 'asc'],
                    [5, 'desc'],
                    [1,'asc']
                ], // Mengatur kolom yang harus diurutkan
                pageLength: 15,
                lengthMenu: [15, 30, 45, 60, 75],
                searching: true,

                rawColumns: ['dn_status'] // Tambahkan ini
            });
        });
    </script>
</body>

</html>
