@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">

                <table class="table table-bordered table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Nama Paket</th>
                            <th>Kecepatan</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
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

@section('content-header', 'List Invoice')

@section('script')
<script>
    // if (user) alert('ok')
    $("#datatables").DataTable({
        responsive: true,
        lengthChange: true,
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "{{Request::url()}}",
            data: function (d) {
                // Param Advance Filter Modalbox
                d.name = $('#id_name_filter').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'inv_number', name: 'inv_number'},
            {data: 'nama_paket', name: 'nama_paket'},
            {data: 'kecepatan', name: 'kecepatan'},
            {data: 'tanggal_pesan', name: 'tanggal_pesan'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']]
    });
</script>
@endsection