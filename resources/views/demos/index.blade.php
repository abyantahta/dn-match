<!DOCTYPE html>
<html>

<head>
    <title>Laravel 11 Import Export Excel to Database Example - ItSolutionStuff.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>

<body>

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3"><i class="fa fa-star"></i> Laravel 11 Import Export Excel to Database Example -
                ItSolutionStuff.com</h3>
            <div class="card-body">

                @session('success')
                    <div class="alert alert-success" role="alert">
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

                <form action="{{ route('demos.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="file" class="form-control">

                    <br>
                    <button class="btn btn-success"><i class="fa fa-file"></i> Import User Data</button>
                </form>

                <table id="demoTable" class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>NameEmail</th>
                        </tr>

                        <tr>
                            <th><input type="text" placeholder="Search ID" /></th>
                            <th><input type="text" placeholder="Search Name" /></th>
                            <th><input type="text" placeholder="Search Email" /></th>
                            <th><input type="text" placeholder="Search Name Email" /></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($demosData as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name_email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- Tambahkan jQuery dan DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#demoTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('demos.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'name_email',
                        name: 'name_email'
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
