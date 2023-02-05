
$(function () {
    bsCustomFileInput.init();
});
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
}

let alertSucces = $('.alert-success').text()
if (alertSucces != '') {
    toastr.success(alertSucces)
}
let alertError = $('.alert-error').text()
if (alertError != '') {
    toastr.error(alertError)
}

jQuery.validator.setDefaults({
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Please select'
    })
    $("body").on("click", ".deleteData", function (e) {
        e.preventDefault();
        var text = 'Anda akan menghapus data ini';
        var berhasil = 'Berhasil menghapus data';
        var gagal = 'Gagal menghapus data';
        let extra = $(this).data("extra");
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
});