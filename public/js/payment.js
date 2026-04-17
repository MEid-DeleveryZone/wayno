$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    if ((urlParams.has('gateway')) && (urlParams.get('gateway') == 'paystack')) {
        $('.spinner-overlay').show();
        let tipAmount = 0;
        if (urlParams.has('tip')) {
            tipAmount = urlParams.get('tip');
        }
        paymentSuccessViaPaystack(urlParams.get('amount'), urlParams.get('trxref'), path, tipAmount);
    }

    window.paymentViaPaystack = function paymentViaPaystack() {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = {};
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.tip = tip;
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_paystack_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                        stopLoader('body');
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    stopLoader('body');
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    function paymentSuccessViaPaystack(amount, reference, path, tip = 0) {
        let address_id = 0;
        if (path.indexOf("cart") !== -1) {
            // $('#order_placed_btn').trigger('click');
            // $('#v-pills-paystack-tab').trigger('click');
            $("#order_placed_btn, .proceed_to_pay").attr("disabled", true);
            address_id = $("input:radio[name='address_id']:checked").val();
        } else if (path.indexOf("wallet") !== -1) {
            // $('#topup_wallet_btn').trigger('click');
            // $('#wallet_topup_form #radio-paystack').prop("checked", true);
            $("#topup_wallet_btn, .topup_wallet_confirm").attr("disabled", true);
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_success_paystack_url,
            data: { 'amount': amount, 'reference': reference },
            success: function(response) {
                if (response.status == "Success") {
                    if (path.indexOf("cart") !== -1) {
                        placeOrder(address_id, 5, response.data, tip);
                    } else if (path.indexOf("wallet") !== -1) {
                        creditWallet(amount, 5, response.data);
                    }
                } else {
                    $('.spinner-overlay').hide();
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                        stopLoader('body');
                    } else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $("#topup_wallet_btn, .topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                $('.spinner-overlay').hide();
                var response = $.parseJSON(error.responseText);
                if (path.indexOf("cart") !== -1) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    stopLoader('body');
                } else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $("#topup_wallet_btn, .topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.paymentViaPayfast = function paymentViaPayfast() {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = {};
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.tip = tip;
            ajaxData.address_id = $("input:radio[name='address_id']:checked").val();
            ajaxData.payment_form = 'cart';
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_payfast_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    var res = response.data;
                    if (res.formData != '') {
                        $("#payfast_offsite_form").remove();
                        var form = '';
                        $.each(res.formData, function(key, value) {
                            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
                        });
                        form = $('<form id="payfast_offsite_form" action="' + res.redirectUrl + '" method="post">' + form + '</form>');
                        if (cartElement.length > 0) {
                            $('#proceed_to_pay_modal .modal-body').append(form);
                        } else if (walletElement.length > 0) {
                            $('#topup_wallet .modal-content').append(form);
                        }
                        form.submit();
                    }
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                        stopLoader('body');
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    stopLoader('body');
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.paymentViaMobbex = function paymentViaMobbex(address_id, order) {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = {};
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.tip = tip;
            ajaxData.address_id = address_id;
            ajaxData.payment_form = 'cart';
            ajaxData.order_number = order.order_number;
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_mobbex_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        stopLoader('body');
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                        
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    stopLoader('body');
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.success_error_alert = function success_error_alert(responseClass, message, element) {
        $(element).find(".alert").html('');
        $(element).removeClass('d-none');
        if (responseClass == 'success') {
            if ($(element).find(".alert").length > 0) {
                $(element).find(".alert").html("<div class='alert-success p-1'>" + message + "</div>").show();
            } else {
                $(element).html(message).show();
            }
        } else if (responseClass == 'error') {
            if ($(element).find(".alert").length > 0) {
                $(element).find(".alert").html("<div class='alert-danger p-1'>" + message + "</div>").show();
            } else {
                $(element).html(message).show();
            }
        }
        $('html, body').animate({
            scrollTop: $(element).offset().top - 200
        }, 500);
        setTimeout(function() {
            $(element).addClass('d-none');
            $(element).find(".alert").hide();
        }, 8000);
    }

});