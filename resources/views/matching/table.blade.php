<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include CSS for DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include JS for DataTables -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    {{-- <title>Document</title> --}}
</head>
<body>
        <table class="w-fit text-center" id="transactionsTable">
            <thead>
                <tr>
                    <th class="table-center">No</th>
                    <th class="table-center">Plant</th>
                    <th class="text-center">Kanban ADM</th>
                    <th class="text-center">Label SDI</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">DN Number</th>
                    <th class="text-center">DN Status</th>
                    <th class="text-center">Order Kbn</th>
                    <th class="text-center">Match Kbn</th>
                    <th class="text-center">Del. Cycle</th>
                    <th class="text-center">Job/Part No</th>
                    <th class="text-center">Seq</th>
                    <th class="text-center">Job/Part No FG</th>
                    <th class="text-center">Seq FG</th>
                    <th class="text-center">Created At</th>
                </tr>
                {{-- {{ dd($transactions) }} --}}
                @foreach ( $transactions as $transaction )
                    <tr>
                        <td class="table-center">{{ $transaction->id }}</td>
                        <td class="table-center">{{ $transaction->plant }}</td>
                        <td class="text-center">{{ $transaction->barcode_cust }}</td>
                        <td class="text-center">{{ $transaction->barcode_fg }}</td>
                        <td class="text-center">{{ $transaction->status }}</td>
                        <td class="text-center">{{ $transaction->no_dn }}</td>
                        <td class="text-center">{{ $transaction->dn_status }}</td>
                        <td class="text-center">{{ $transaction->order_kbn }}</td>
                        <td class="text-center">{{ $transaction->match_kbn }}</td>
                        <td class="text-center">{{ $transaction->del_cycle }}</td>
                        <td class="text-center">{{ $transaction->no_job }}</td>
                        <td class="text-center">{{ $transaction->no_seq }}</td>
                        <td class="text-center">{{ $transaction->no_job_fg }}</td>
                        <td class="text-center">{{ $transaction->no_seq_fg }}</td>
                        <td class="text-center">{{ $transaction->created_at }}</td>
                        
                    </tr>
                @endforeach
                {{-- {{ dd($transactions) }}; --}}
 
            </thead>
        </table>
</body>
</html>