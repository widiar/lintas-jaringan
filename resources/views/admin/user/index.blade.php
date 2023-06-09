@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @can('create_user')
                <a href="{{ route('admin.user.create') }}">
                    <button class="btn btn-sm btn-primary mb-3">
                        <i class="fa fa-plus"></i> Tambah User
                    </button>
                </a>
                @endcan

                <table class="table table-bordered table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header', 'Kelola User')

@section('script')
<script>
    let user = @json(auth()->user()->can('print_user'));
    // if (user) alert('ok')
    let searchParams = new URLSearchParams(window.location.search);
    let param = searchParams.get('type')
    $("#datatables").DataTable({
        responsive: true,
        lengthChange: true,
        processing: true,
        serverSide: true,
        searching: true,
        searchDelay: 1200,
        ajax: {
            url: "",
            data: function (d) {
                // Param Advance Filter Modalbox
                d.name = $('#id_name_filter').val(),
                d.search = $('input[type="search"]').val(),
                d.type = param
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']],
        dom: 'Bfrtip',
        buttons: user ? [
            {
                extend: 'print',
                customize: function (win) {
                    $(win.document.body).find('table').find('thead th:nth-child(5), tbody td:nth-child(5)').css('display', 'none');
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:nth-child(5))'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(:nth-child(5))'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:nth-child(5))'
                }
            },
            'copy'
        ] : []
    });
</script>
@endsection