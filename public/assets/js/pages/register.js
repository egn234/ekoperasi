$(document).ready(function () {
    $(".password-toggle").click(function () {
        const targetId = $(this).attr("data-target");
        const passwordInput = $("#" + targetId);

        if (passwordInput.attr("type") === "password") {
            passwordInput.attr("type", "text");
            $(this).html('<i class="mdi mdi-eye-off-outline"></i>');
        } else {
            passwordInput.attr("type", "password");
            $(this).html('<i class="mdi mdi-eye-outline"></i>');
        }
    });

    // Disable tombol submit sampai checkbox diklik
    $('#konfirmasi_check').on('change', function () {
        if ($(this).is(':checked')) {
            $('#confirm_button').prop('disabled', false);
        } else {
            $('#confirm_button').prop('disabled', true);
        }
    });

    // Ketika tombol 'Register' diklik, jalankan validasi
    $('#btnRegister').on('click', function () {
        let isValid = validateForm();

        if(isValid) {
            $("#konfirmasi").modal('show');
        } else {
            $(document).scrollTop(0);
        }
    });

    $('#retype_pass').on('input', function() {
        if ($('#password').val() != $('#retype_pass').val()) {
            $('#retype_pass').removeClass('is-valid');
            $('#retype_pass').addClass('is-invalid');
        } else {
            $('#retype_pass').removeClass('is-invalid');
            $('#retype_pass').addClass('is-valid');
        }
    });

    // Event blur untuk interaksi real-time
    $('#register_form input').on('blur', function() {
        validateField($(this));
    });

    function validateForm() {
        let isValid = true;

        // Validasi setiap field dan update class CSS
        isValid = validateField($('#full_name'), isValid);
        isValid = validateField($('#nik_number'), isValid);
        isValid = validateField($('#birthplace'), isValid);
        isValid = validateField($('#birthday'), isValid);
        isValid = validateField($('#institution'), isValid);
        isValid = validateField($('#job_unit'), isValid);
        isValid = validateField($('#staffing'), isValid);
        isValid = validateField($('#address'), isValid);
        isValid = validateField($('#bankname'), isValid);
        isValid = validateField($('#norek'), isValid);
        isValid = validateField($('#phone_number'), isValid);
        isValid = validateField($('#email_addr'), isValid);
        isValid = validateField($('#password'), isValid);
        isValid = validateField($('#profile_pic'), isValid);

        // Periksa apakah semua validasi valid
        return isValid;
    }

    function validateField(input, isValid = true) {
        let selector = input.attr('id');
        let condition = false;
        
        // Cek kondisi validasi berdasarkan input
        switch(selector) {
            case 'full_name':
                condition = input.val().trim() === '';
                break;
            case 'nik_number':
                condition = input.val().trim().length !== 16 || isNaN(input.val().trim());
                break;
            case 'birthplace':
                condition = input.val().trim() === '';
                break;
            case 'birthday':
                condition = input.val().trim() === '';
                break;
            case 'institution':
                condition = input.val() === 'default';
                break;
            case 'job_unit':
                condition = input.val().trim() === '';
                break;
            case 'staffing':
                condition = input.val() === 'default';
                break;
            case 'address':
                condition = input.val().trim() === '';
                break;
            case 'bankname':
                condition = input.val().trim() === '';
                break;
            case 'norek':
                condition = input.val().trim() === '';
                break;
            case 'phone_number':
                condition = input.val().trim() === '';
                break;
            case 'email_addr':
                condition = input.val().trim() === '';
                break;
            case 'password':
                condition = input.val().trim().length < 8;
                break;
            case 'profile_pic':
                const file = input[0].files[0];
                if (!file) {
                    condition = true;
                    break;
                }

                const allowedTypes = ['image/jpeg', 'image/jpg'];
                const fileSizeKB = file.size / 1024;
                const fileSizeMB = fileSizeKB / 1024;

                if (!allowedTypes.includes(file.type)) {
                    input.addClass('is-invalid').removeClass('is-valid');
                    input.next('.invalid-feedback').text('Format file harus jpg atau jpeg.').show();
                    return false;
                }

                if (fileSizeKB < 128) {
                    input.addClass('is-invalid').removeClass('is-valid');
                    input.next('.invalid-feedback').text('Ukuran file terlalu kecil. Minimal 128KB.').show();
                    return false;
                }

                if (fileSizeMB > 2) {
                    input.addClass('is-invalid').removeClass('is-valid');
                    input.next('.invalid-feedback').text('Ukuran file terlalu besar. Maksimal 2MB.').show();
                    return false;
                }

                // Validasi resolusi gambar
                const img = new Image();
                const objectUrl = URL.createObjectURL(file);

                img.onload = function () {
                    if (img.width < 300 || img.height < 300) {
                        input.addClass('is-invalid').removeClass('is-valid');
                        input.next('.invalid-feedback').text('Resolusi gambar minimal 300x300 px.').show();
                    } else {
                        input.removeClass('is-invalid').addClass('is-valid');
                        input.next('.invalid-feedback').hide();
                    }
                    URL.revokeObjectURL(objectUrl);
                };

                img.onerror = function () {
                    input.addClass('is-invalid').removeClass('is-valid');
                    input.next('.invalid-feedback').text('File tidak bisa dibaca sebagai gambar.').show();
                    URL.revokeObjectURL(objectUrl);
                };

                img.src = objectUrl;

                return isValid;
        }

        if (condition) {
            input.addClass('is-invalid').removeClass('is-valid');
            input.siblings('.invalid-feedback').show();
            isValid = false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            input.siblings('.invalid-feedback').hide();
        }

        return isValid;
    }
});
