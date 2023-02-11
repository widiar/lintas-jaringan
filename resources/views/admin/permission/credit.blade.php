@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.permission.store') }}" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="text">Name<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="name"
                            class="form-control  @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name ?? null) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="single" name="options"
                            id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                            Single Permission
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Permission
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