@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#filterModal">
                    Filter
                </button>
                <table class="table table-bordered table-striped" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Nama Paket</th>
                            <th>Nama Pelanggan</th>
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
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter dari status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="text">Status</label>
                    <select name="status" class="form-control select2" id="status" required>
                        <option @if(request()->get('status') == 'PENDING') selected @endif value="PENDING">PENDING</option>
                        <option @if(request()->get('status') == 'PAID') selected @endif value="PAID">PAID</option>
                        <option @if(request()->get('status') == 'PROSES') selected @endif value="PROSES">PROSES</option>
                        <option @if(request()->get('status') == 'RESCHEDULE') selected @endif value="RESCHEDULE">RESCHEDULE
                        </option>
                        <option @if(request()->get('status') == 'DONE') selected @endif value="DONE">DONE</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-filter">Filter</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-header', 'List Invoice')

@section('script')
<script>
    // if (user) alert('ok')
    $(document).ready(function(){
        $('.btn-filter').click(function(e){
            e.preventDefault()
            let status = $('#status').val()
            window.location.href = `/invoice?status=${status}`
        })
    })
    $("#datatables").DataTable({
        responsive: true,
        lengthChange: true,
        processing: true,
        searchDelay: 1200,
        serverSide: true,
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
            {data: 'inv_number', name: 'inv_number'},
            {data: 'nama_paket', name: 'nama_paket'},
            {data: 'user.pelanggan.nama', name: 'user.pelanggan.nama'},
            {data: 'tanggal_pesan', name: 'tanggal_pesan'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']]
    });
</script>
@endsection