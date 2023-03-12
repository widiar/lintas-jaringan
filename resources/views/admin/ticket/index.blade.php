@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @role('Pelanggan')
                @can('create_ticket')
                <a href="{{ route('ticket.create') }}">
                    <button class="btn btn-sm btn-primary mb-3">
                        <i class="fa fa-plus"></i> Tambah Ticket Keluhan
                    </button>
                </a>
                @endcan
                @endrole
                <table class="table table-bordered table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Ticket</th>
                            <th>Subject</th>
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

@section('content-header', 'List Ticket')

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
            {data: 'names', name: 'names'},
            {data: 'subject', name: 'subject'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']]
    });
    $("body").on("click", ".closeData", function (e) {
        e.preventDefault();
        var text = 'Anda akan menutup ticket ini';
        var berhasil = 'Berhasil menutup data';
        var gagal = 'Gagal menutup data';
        Swal.fire({
            title: "Anda Yakin?",
            html: `${text}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yap!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: $(this).attr("href"),
                    success: function (msg) {
                        if (msg == "Sukses") {
                            Swal.fire(
                                "Berhasil!",
                                berhasil,
                                "success"
                            ).then((result) => {
                                if (result.value) {
                                    window.location.href = $(location).attr(
                                        "href"
                                    );
                                }
                            });
                        } else {
                            Swal.fire(
                                "Gagal",
                                gagal,
                                "error"
                            );
                        }
                    },
                });
            }
        });
    });
</script>
@endsection