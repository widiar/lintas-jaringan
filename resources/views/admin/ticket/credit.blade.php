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
                <form action="@if(Route::current()->getName() == 'ticket.create'){{ route('ticket.store') }}
                    @else{{ route('ticket.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'ticket.edit')
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="text">Subject<span class="text-danger">*</span></label>
                        <input @if(Route::current()->getName() == 'ticket.edit') readonly @endif autocomplete="off"
                        type="text" required name="subject"
                        class="form-control @error('subject') is-invalid @enderror"
                        value="{{ old('subject', $data->subject ?? null) }}">
                        @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    @if(Route::current()->getName() == 'ticket.edit')
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
                    @endif

                    <div class="card-footer">
                        <div class="input-group">
                            <input type="text" {{ $akses==0 ? 'disabled' : '' }} autocomplete="off" name="message"
                                placeholder="Pesan anda ..." class="form-control msg">
                            <span class="input-group-append">
                                <button type="{{ $akses == 0 ? 'button' : 'submit' }}"
                                    class="btn btn-primary btn-send">Send</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@vite('resources/js/app.js')
@endsection

@section('content-header')
@if(Route::current()->getName() == 'ticket.edit')
Tiket {{ $data->names }}
@else
Buat Tiket
@endif
@endsection

@section('script')
<script>
    $(document).ready(function(){
        let akses = @json($akses);
        let id = @json($id);
        let ticket = @json($data ?? null);
        
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

        if(ticket != null){
            if(ticket.is_closed == 1){
                $('.card-footer').html('')
                Swal.fire({
                    title: "Maaf",
                    text: `Pesan ini sudah di closed`,
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

        const listen = (idRoom) => {
            window.Echo.private(`chatPosted.${idRoom}`).listen('ChatSend', (res) => {
                let datetime = formatedDate(res.ticket.created_at)
                const html = `
                <div class="direct-chat-msg">
                    <div class="direct-chat-text float-left">
                        ${res.ticket.body}
                    </div>
                    <div class="clearfix"></div>
                    <div class="direct-chat-infos">
                        <span class="direct-chat-timestamp float-left">${datetime}</span>
                    </div>
                </div>
                `
                $('.direct-chat-messages').append(html)
                // console.log(res)
            })
        }

        if(id != null) {
            listen(id)
        }

        $('.direct-chat-timestamp').each(function(idx, elm){
            let formdate = formatedDate($(elm).text())
            console.log(formdate)
            $(elm).text(formdate);
        })

        let route = `{{ Route::current()->getName() }}`
        $('#form').validate({
            rules: {
                subject: {
                    required: true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    }
                },
                message: {
                    required: true,
                    normalizer: function( value ) {
                        return $.trim( value );
                    }
                },
            },
            submitHandler: function(form, e) {
                e.preventDefault()
                $('.btn-send').attr('disabled', 'disabled')
                $('.btn-send').html(`<i class="fa fa-spinner fa-spin"></i> Sending`)
                
                $.ajax({
                    url: $(form).attr('action'),
                    data: $(form).serialize(),
                    type: 'post',
                    success: (res) => {
                        if(res.status == 'success'){
                            if(route == 'ticket.edit'){
                                let msg = $('.msg').val()
                                let datetime = formatedDate(res.data.created_at)

                                $('.direct-chat-messages').append(`
                                <div class="direct-chat-olive">
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-text float-right">
                                            ${res.data.body}
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="direct-chat-infos">
                                            <span class="direct-chat-timestamp float-right">${datetime}</span>
                                        </div>
                                    </div>
                                </div>
                                `)
                
                                $('.msg').val('')
                                $('.msg').focus()
                                $('.btn-send').removeAttr('disabled')
                                $('.btn-send').html(`Send`)
                            }else
                            window.location.href = res.data
                        }
                        else console.log(res)
                    },
                    error: (err) => {
                        console.log(err)
                    }
                })
            },

        })
    })
</script>
@endsection