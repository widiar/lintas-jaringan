@extends('admin.index')
@section('css')
<style>
    .direct-chat-text {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
</style>
@endsection
@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="text">Subject<span class="text-danger">*</span></label>
                    <input disabled autocomplete="off" type="text" required name="subject"
                        class="form-control @error('subject') is-invalid @enderror"
                        value="{{ old('subject', $data->subject ?? null) }}">
                    @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <hr>
                <div class="pesan">
                    <div class="direct-chat-messages" style="height: auto; width: 100%; overflow-x: hidden">

                        @foreach ($data->detail as $pesan)
                        @if($pesan->user_id == auth()->user()->id)
                        <div class="direct-chat-olive">
                            <div class="direct-chat-msg right">

                                <div class="direct-chat-text float-right">
                                    {{ $pesan->body }}
                                </div>
                                <div class="clearfix"></div>
                                <div class="direct-chat-infos">
                                    <span class="direct-chat-timestamp float-right">{{ $pesan->created_at }}</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="direct-chat-msg">

                            <div class="direct-chat-text float-left">
                                {{ $pesan->body }}
                            </div>
                            <div class="clearfix"></div>
                            <div class="direct-chat-infos">
                                <span class="direct-chat-timestamp float-left">{{ $pesan->created_at }}</span>
                            </div>

                        </div>
                        @endif
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <a href="{{ route('ticket') }}">
                        <button class="btn btn-primary">Kembali</button>
                    </a>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Tiket {{ $data->names }}
@endsection

@section('script')
<script>
    $(document).ready(function(){
        let akses = @json($akses);
        let id = @json($id);
        
        if(akses == 0){
            Swal.fire({
                title: "Maaf",
                text: `Pesan ini sudah di balas oleh admin lain`,
                icon: "warning",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Kembali",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ route('ticket') }}`
                }
            });
        }

        const formatedDate = (date) =>{
            const dateObj = new Date(date);

            const options = { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            };

            return new Intl.DateTimeFormat('en-UK', options).format(dateObj);
        }

        $('.direct-chat-timestamp').each(function(idx, elm){
            let formdate = formatedDate($(elm).text())
            console.log(formdate)
            $(elm).text(formdate);
        })

    })
</script>
@endsection