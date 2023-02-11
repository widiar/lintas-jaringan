@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                    <h4>Role {{ $role->name }}</h4>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Permission</th>
                            <th>Hak Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupPermis as $item)
                        <tr>
                            <td>
                                <h4>{{ $item['name'] }}</h4>
                            </td>
                            <td>
                                @foreach ($item['permission'] as $perm)
                                <li class="list-inline-item mr-2">
                                    <div class="card" style="max-width: 10rem">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item font-weight-bold">{{
                                                ucwords(str_replace("_"," ",explode("_",$perm,2)[0])) }}</li>
                                            <li class="list-group-item text-center">
                                                <span
                                                    class="badge badge-pill{{ in_array($perm, $permissions) ? ' badge-success' : ' badge-danger' }}">
                                                    @if(in_array($perm, $permissions))
                                                    <i class="fa fa-check"></i>
                                                    @else
                                                    <i class="fa fa-times"></i>
                                                    @endif
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                        {{-- <td>
                            <h5>Kelola User</h5>
                        </td>
                        <td>
                            <li class="list-group-item text-center">
                                <span
                                    class="badge badge-pill{{ in_array('view_user', $permissions) ? ' badge-success' : ' badge-danger' }}">
                                    @if(in_array('view_user', $permissions))
                                    <i class="fa fa-check"></i>
                                    @else
                                    <i class="fa fa-times"></i>
                                    @endif
                                </span>
                            </li>
                        </td>
                        <td>
                            <li class="list-group-item text-center">
                                <span
                                    class="badge badge-pill{{ in_array('create_user', $permissions) ? ' badge-success' : ' badge-danger' }}">
                                    @if(in_array('create_user', $permissions))
                                    <i class="fa fa-check"></i>
                                    @else
                                    <i class="fa fa-times"></i>
                                    @endif
                                </span>
                            </li>
                        </td>
                        <td>
                            <li class="list-group-item text-center">
                                <span
                                    class="badge badge-pill{{ in_array('edit_user', $permissions) ? ' badge-success' : ' badge-danger' }}">
                                    @if(in_array('edit_user', $permissions))
                                    <i class="fa fa-check"></i>
                                    @else
                                    <i class="fa fa-times"></i>
                                    @endif
                                </span>
                            </li>
                        </td>
                        <td>
                            <li class="list-group-item text-center">
                                <span
                                    class="badge badge-pill{{ in_array('delete_user', $permissions) ? ' badge-success' : ' badge-danger' }}">
                                    @if(in_array('delete_user', $permissions))
                                    <i class="fa fa-check"></i>
                                    @else
                                    <i class="fa fa-times"></i>
                                    @endif
                                </span>
                            </li>
                        </td> --}}
                    </tbody>
                </table>
                <a href="{{ url()->previous() }}">
                    <button class="btn btn-primary mt-3">Kembali</button>
                </a>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Detail Roles
@endsection