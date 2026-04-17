$(document).ready(function () {
	 $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
	$('#send_password_reset_link').click(function(){
        var that = $(this);
        that.prop('disabled', true);
        var email = $('#email').val();
        $('.invalid-feedback').html('');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {"email": email},
            url: forgot_password_url,
            success: function(res) {
                if(res.status == "Success"){
                    $('#email').val('');
                    that.prop('disabled', false);
                    $('.alert-success').html(res.message).show();
                	setTimeout(function(){ 
                		$('.alert-success').html('').hide();
                	}, 3000);
                }
            },
            error:function(error){
                that.prop('disabled', false);
            	var response = $.parseJSON(error.responseText);
                let error_messages = response.errors;
                $.each(error_messages, function(key, error_message) {
                    $('#email_validation_error').html(error_message[0]).show();
                });
            }
        });
    });
    $('#reset_password_btn').click(function(){
        var that = $(this);
        $('.invalid-feedback').html('');
        $('#password').removeClass('is-invalid');
        $('#password_confirmation').removeClass('is-invalid');
      	let myForm = document.getElementById('reset_password_form');
        let formData = new FormData(myForm);
        $.ajax({
            type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        url: reset_password_url,
	        headers: {Accept: "application/json"},
            success: function(res) {
                if(res.status == "Success"){
                	//$('.alert-success').html(res.message).show();
                	//setTimeout(function(){ 
                		window.location.href = reset_password_success_url;
                	//}, 3000);
                }else{
                    $('.alert-danger').html(res.message).show();
                }
            },
            error:function(error){
            	var response = $.parseJSON(error.responseText);
                let error_messages = response.errors;
                $.each(error_messages, function(key, error_message) {
                    $('#'+key).addClass('is-invalid');
                    $('#'+key+'_err').html(error_message[0]).show();
                });
            }
        });
    });
});