<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>

<script>
   

    $(".openEditModal").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uid = $(this).attr('loyaltyID');
        if (uid > 0) {
            uri = "<?php echo url('client/wallet'); ?>" + '/' + uid + '/edit';

        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-loyalty-modal #editLoyaltyBox').html(data.html);
                $('#edit-loyalty-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('.dropify').dropify();
                $('.select2-multiple').select2();

            },
            error: function(data) {
                console.log('data2');
            }
        });
    });


    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
        console.log("aagya");
        var form = document.getElementById('update_loyality_form');
        var formData = new FormData(form);
        var url = document.getElementById('lc_id').getAttribute('url');
        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, formUri) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: formUri,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(".loader_box").show();
            },
            success: function(response) {

                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                $('.addModal').modal('hide');
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Inpiut input").addClass("is-invalid");
                        $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            },
            complete: function() {
                $('.loader_box').hide();
            }
        });
    }

</script>