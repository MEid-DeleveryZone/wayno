var tip_for_past_order = 1; 

$(document).on('change', '#wallet_payment_methods input[name="wallet_payment_method"]', function() {
    $('#wallet_payment_methods_error').html('');
    var method = $(this).val();
    if(method == 'stripe'){
        $("#wallet_payment_methods .stripe_element_wrapper").removeClass('d-none');
    }else{
        $("#wallet_payment_methods .stripe_element_wrapper").addClass('d-none');
    }
});