@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="@if(Route::current()->getName() == 'admin.roles.create'){{ route('admin.roles.store') }}
                    @else{{ route('admin.roles.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.roles.edit')
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="text">Name<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="name"
                            class="form-control  @error('name') is-invalid @enderror"
                            value="{{ old('name', $role->name ?? null) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Lihat</th>
                                <th>Tambah</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <td>
                                <h5>Kelola User</h5>
                            </td>
                            <td>
                                <li class="list-group-item text-center">
                                    <input type="checkbox" {{ in_array('view_user', $permissions) ? 'checked' : '' }}
                                        name="permission[]" value="view_user">
                                </li>
                            </td>
                            <td>
                                <li class="list-group-item text-center">
                                    <input type="checkbox" {{ in_array('create_user', $permissions) ? 'checked' : '' }}
                                        name="permission[]" value="create_user">
                                </li>
                            </td>
                            <td>
                                <li class="list-group-item text-center">
                                    <input type="checkbox" {{ in_array('edit_user', $permissions) ? 'checked' : '' }}
                                        name="permission[]" value="edit_user">
                                </li>
                            </td>
                            <td>
                                <li class="list-group-item text-center">
                                    <input type="checkbox" {{ in_array('delete_user', $permissions) ? 'checked' : '' }}
                                        name="permission[]" value="delete_user">
                                </li>
                            </td>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.roles') }}">
                        <button type="button" class="btn btn-primary float-right">Kembali</button>
                    </a>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Tambah Roles
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#form').validate({
            rules: {
                name: 'required',
            },
        })
    })
</script>
@endsection