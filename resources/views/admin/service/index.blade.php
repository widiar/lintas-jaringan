@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @can('create_service')
                <a href="{{ route('admin.service.create') }}">
                    <button class="btn btn-sm btn-primary mb-3">
                        <i class="fa fa-plus"></i> Tambah Layanan
                    </button>
                </a>
                @endcan

                <table class="table table-bordered table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Icon</th>
                            <th>Keterangan</th>
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

@section('content-header', 'Kelola Layanan')

@section('script')
<script>
    // if (user) alert('ok')
    $("#datatables").DataTable({
        responsive: true,
        lengthChange: true,
        processing: true,
        serverSide: true,
        searchDelay: 1200,
        searching: true,
        ajax: {
            url: "",
            data: function (d) {
                // Param Advance Filter Modalbox
                d.name = $('#id_name_filter').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'judul', name: 'judul'},
            {data: 'icon', name: 'icon', orderable: false, searchable: false},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']]
    });
</script>
@endsection