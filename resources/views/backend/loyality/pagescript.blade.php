<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>

<script>
    $('.chk_box').change(function() {
        if (this.checked) {
            var status = 0;
        } else {
            var status = 1;
        }
        console.log($(this).attr("bid"));
        var bid = $(this).attr("bid");
        $.ajax({
            url: "{{ route('loyalty.changeStatus') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: bid,
                status: status
            },
            success: function(response) {
                console.log(response);
            },
        });
    });

    $('.chk_box1').change(function() {
        if (this.checked) {
            var status = 0;
        } else {
            var status = 1;
        }
        console.log($(this).attr("bid"));
        var bid = $(this).attr("bid");
        $.ajax({
            url: "{{ route('loyalty.setLoyaltyCheck') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: bid,
                status: status
            },
            success: function(response) {
                console.log(response);
                $.NotificationApp.send("Success", "Updated Successfully", "top-right", "#5ba035", "success");
            },
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('loyalty.getRedeemPoints') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log(response.value);
                $(".primaryKey").html("1 " + response.symbol + " =");
                $("#redeem_points_per_primary_currency").val(response.value);
            },
        });
    });

    $(".setredeempoints").click(function(e) {
        var form = document.getElementById('setRedeem');
        var formData = new FormData(form);
        console.log(formData);
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
            url: "{{ route('loyalty.setRedeemPoints') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                $(".redeemPoint").modal("hide");
                $.NotificationApp.send("Success", "Updated Successfully", "top-right", "#5ba035", "success");
            },
        });
    });

    $(".openEditModal").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uid = $(this).attr('loyaltyID');
        if (uid > 0) {
            uri = "<?php echo url('client/loyalty'); ?>" + '/' + uid + '/edit';

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
                $('#edit-loyalty-modal .dropify').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.submitAddForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_loyality_form');
        var formData = new FormData(form);
        var url = "{{route('loyalty.store')}}";
        saveData(formData, 'add', url);

    });

    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
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