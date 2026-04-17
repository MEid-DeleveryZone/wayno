   ////////   **************  cab details page  *****************  ////////

   $(document).on("click", ".select_cab_payment_method",function() {
       var payment_method = $(this).attr('data-payment_method');
       if(payment_method == 2)
       $('#payment_type').html('<i class="fa fa-money" aria-hidden="true"></i> Wallet');
       else
       $('#payment_type').html('<i class="fa fa-money" aria-hidden="true"></i> Cash');

       $('#pickup_now').attr("data-payment_method",payment_method);
       $('#pickup_later').attr("data-payment_method",payment_method);

       $("#payment_modal").modal('toggle');
   });


   function setOrderDetailsPage() {
    $('.address-form').addClass('d-none');
    $('.cab-detail-box').removeClass('d-none');
     $.ajax({
        type: "POST",
        dataType: 'json',
        url: order_place_driver_details_url,
        success: function(response) {
            $('#pickup_now').attr('disabled', false);
            $('#pickup_later').attr('disabled', false);
            if(response.status == '200'){
                $('#cab_detail_box').html('');
                let order_success_template = _.template($('#order_success_template').html());
                $("#cab_detail_box").append(order_success_template({result: response.data, product_image: response.data.product_image})).show();
                // Remove live tracking - only get driver details once
                getOrderDriverDetails(response.data.dispatch_traking_url,response.data.id);
            }
        }
    });
}




function getOrderDriverDetails(dispatch_traking_url,order_id) {
    var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/');
    $.ajax({
        type:"POST",
        dataType: "json",
        url: order_tracking_details_url,
        data:{new_dispatch_traking_url:new_dispatch_traking_url,order_id:order_id},
        success: function( response ) {
            if(response.data.agent_location != null){
                $('#searching_main_div').remove();
                $('#driver_details_main_div').show();
                $('#driver_name').html(response.data.order.name).show();
                $('#driver_image').attr('src', response.data.agent_image).show();
                $('#driver_phone_number').html(response.data.order.phone_number).show();
                $("#dispatcher_status_show").html(response.data.order_details.dispatcher_status);
            }
        }
    });
}

// get driver details 



$(document).ready(function () {
    $('.cab-booking-main-loader').hide();
    var selected_address = '';
    const styles = [{"stylers":[{"visibility":"on"},{"saturation":-100},{"gamma":0.54}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#4d4946"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"gamma":0.48}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"gamma":7.18}]}];
    $(document).on("click","#show_dir",function() {
        initMap2();
    });
    $(document).on("click", "#pickup_now, #pickup_later",function() {

        var time_zone = (Intl.DateTimeFormat().resolvedOptions().timeZone);
        var schedule_datetime = '';
        if($(this).data('rel') =='pickup_later'){
            let temp_schedule_datetime = $('#schedule_datetime').val();
            if(!temp_schedule_datetime){
                $('#schedule_datetime_main_div').show();
                return false;
            }
            schedule_datetime = moment(temp_schedule_datetime).format('YYYY-MM-DD HH:mm');
        }
        var tasks = [];

        let schedule_datetimeset = $('#schedule_date').val();
        if(schedule_datetimeset != undefined && schedule_datetimeset != 0){
            schedule_datetime = moment(schedule_datetimeset).format('YYYY-MM-DD HH:mm');
            var task_type = 'schedule';
        }
        else{
            var task_type = 'now';
        }
        if(time_zone != undefined || time_zone.length != 0){
            time_zone = time_zone;
        }
        
        $('#pickup_now').attr('disabled', true);
      //  $('#pickup_later').attr('disabled', true);
        var pickup_location_names = $('input[name="pickup_location_name[]"]').map(function(){return this.value;}).get();
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){return this.value;}).get();
        var pickup_location_latitudes = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitudes = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        
        $(pickup_location_latitudes).each(function(index, latitude) {
            var sample_array = {};
            sample_array.barcode = null;
            sample_array.task_type_id = 1;
            sample_array.post_code = null;
            sample_array.short_name = null;
            sample_array.latitude = latitude;
            sample_array.appointment_duration = null;
            sample_array.address = pickup_location_names[index];
            sample_array.longitude = pickup_location_longitudes[index];
            tasks.push(sample_array);
        });
        $(destination_location_latitudes).each(function(index, latitude) {
            var sample_array = {};
            sample_array.barcode = null;
            sample_array.task_type_id = 2;
            sample_array.post_code = null;
            sample_array.short_name = null;
            sample_array.latitude = latitude;
            sample_array.appointment_duration = null;
            sample_array.address = destination_location_names[index];
            sample_array.longitude = destination_location_longitudes[index];
            tasks.push(sample_array);
        });
        let amount = $(this).data('amount');
        let product_image = $(this).data('image');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        let product_id = $(this).data('product_id');
        let payment_option_id = $(this).data('payment_method');
       
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: cab_booking_create_order,
            data: {time_zone:time_zone,payment_option_id: payment_option_id, vendor_id: vendor_id, product_id: product_id,coupon_id: coupon_id, amount: amount, tasks: tasks, task_type:task_type, schedule_datetime:schedule_datetime},
            success: function(response) {
                $('#pickup_now').attr('disabled', false);
                $('#pickup_later').attr('disabled', false);
                if(response.status == '200'){
                    window.location.replace(response.data.route);
                    $('#cab_detail_box').html('');
                    let order_success_template = _.template($('#order_success_template').html());
                    $("#cab_detail_box").append(order_success_template({result: response.data, product_image: product_image})).show();
                    // Remove live tracking - only get driver details once
                    getDriverDetails(response.data.dispatch_traking_url);
                }else{
                    $('#show_error_of_booking').html(response.message);
                }
            }
        });
    });

 
 

    function getDriverDetails(dispatch_traking_url) {
        var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/')
        $.ajax({
            type:"POST",
            dataType: "json",
            url: order_tracking_details_url,
            data:{new_dispatch_traking_url:new_dispatch_traking_url},
            success: function( response ) {
                if(response.data.agent_location != null){
                    $('#searching_main_div').remove();
                    $('#driver_details_main_div').show();
                    $('#driver_name').html(response.data.order.name).show();
                    $('#driver_image').attr('src', response.data.agent_image).show();
                    $('#driver_phone_number').html(response.data.order.phone_number).show();
                }
            }
        });
    }

    $(document).on("click", ".add-more-location",function() {
        let random_id = Date.now();

        var destination_location_names = $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
            if(this.value == ''){
                return "empty";
            }
        }).get();

        if(destination_location_names[0] == 'empty'){
            return false;
        }

        var destination_location_add_temp = $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').length;
        if(destination_location_add_temp == 4){
            $('.add-more-location').hide();
        }
        $('#search_product_main_div').attr("style", "display: none !important");
        $('.location-list').attr("style", "display: block !important");
        // check-dropoff
        let destination_location_template = _.template($('#destination_location_template').html());
        $("#destination_location_add_temp").append(destination_location_template({random_id:random_id})).show();
        initializeNew(random_id);
    });
    // $(document).on("click", ".location-inputs .apremove",function() {
    //     if($('#dots_'+$(this).data('rel')).length != 0){
    //         $('#dots_'+$(this).data('rel')).remove();
    //         var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
    //            return this.value;
    //         }).get();
    //         if(destination_location_names.length < 5){
    //             $('.add-more-location').show();
    //         }else{
    //             $('.add-more-location').hide();
    //         }
    //         initMap2();
    //     }
    // });
    function initializeNew(random_id) {
      var input2 = document.getElementById('destination_location_'+random_id);
      if(input2){
        var autocomplete = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place2 = autocomplete.getPlace();
            $('#destination_location_latitude_'+random_id).val(place2.geometry.location.lat());
            $('#destination_location_longitude_'+random_id).val(place2.geometry.location.lng());
            initMap2(random_id);

            if(random_id != ''){
                $('#search_product_main_div').attr("style", "display: block !important");
                $('.location-list').attr("style", "display: none !important");
                $('#destination_location_'+random_id).attr("style", "display: none !important");

                var currentUrl                   = window.location.href;
                var queryString                  = removeURLParameter(currentUrl, 'destination_location_'+random_id);
                var destination_location         = $("#destination_location_"+random_id).val();
                var destinationLocationLatitude  = $("#destination_location_latitude_"+random_id).val();
                var destinationLocationLongitude = $("#destination_location_longitude_"+random_id).val();
                var perm = "?" + (queryString != '' ? queryString : '') + "&destination_location_"+random_id+"=" + destination_location  + "&destination_location_latitude_"+random_id+"=" + destinationLocationLatitude + "&destination_location_longitude_"+random_id+"=" + destinationLocationLongitude;
                window.history.replaceState(null, null, perm);
            }
        });
      }
    }
    $(document).on("click",".search-location-result",function() {
        var latitude             = $(this).data('latitude');
        var longitude            = $(this).data('longitude');
        var pickup_location      = $('#pickup_location_latitude').val();
        var destination_location = $('#destination_location_latitude').val();
        var pickupAddress        = $(this).data('address');
        if(pickup_location == ''){
            $('#pickup_location').val($(this).data('address'));
            $('#pickup_location_latitude').val(latitude);
            $('#pickup_location_longitude').val(longitude);

            // initMap2();
            var pickupLocationLatitude  = latitude;
            var pickupLocationLongitude = longitude;
            var currentUrl              = window.location.href;
            var queryString             = removeURLParameter(currentUrl, 'pickup_location');
            var perm                    = "?pickup_location=" + $(this).data('address') + "&pickup_location_latitude=" + pickupLocationLatitude +"&pickup_location_longitude=" + pickupLocationLongitude + (queryString != '' ? "&" + queryString : '');
            window.history.replaceState(null, null, perm);

            $(".check-pick-first").css("display", "none");
            $("#pickup-where-from").html(" "+$(this).data('address'));
            $(".check-dropoff-secpond").css("display", "block");
            $('.check-pickup').attr("style", "display: none !important");
            $(".check-dropoff").css("display", "block");

        }else if(destination_location == ''){
            $('#destination_location').val($(this).data('address'));
            $('#destination_location_latitude').val(latitude);
            $('#destination_location_longitude').val(longitude);

            var currentUrl  = window.location.href;
            var queryString = removeURLParameter(currentUrl, 'destination_location');
            var perm        = "?" + (queryString != '' ? queryString : '') + "&destination_location=" + $(this).data('address')  + "&destination_location_latitude=" + latitude +"&destination_location_longitude=" + longitude;
            window.history.replaceState(null, null, perm);
            
            $("#dropoff-where-to").html(" "+$(this).data('address'));
            $('.where-to-first').attr("style", "display: none !important");
            $('.check-dropoff').attr("style", "display: none !important");
            $(".where-to-second").css("display", "block");
            $('.add-more-location').attr("style", "display: block !important");

            $('#search_product_main_div').attr("style", "display: block !important");
            $('.location-list').attr("style", "display: none !important");

        }else{
            $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
                if(this.value == ''){
                    var inputId = this.id;
                    $('#'+inputId).val(pickupAddress);
                    var random_id = $(this).data( "rel" );
                    $('#destination_location_latitude_'+random_id).val(latitude);
                    $('#destination_location_longitude_'+random_id).val(longitude);
                    
                    $('#search_product_main_div').attr("style", "display: block !important");
                    $('.location-list').attr("style", "display: none !important");

                    let destination_location_template_li = _.template($('#destination_location_template_li').html());
                    var destination_location = $('#destination_location_'+random_id).val();            
                    $('#location_input_main_div li:last-child').after(destination_location_template_li({random_id:random_id}));
                    $("#dropoff-where-to-"+random_id).html(" "+ pickupAddress);

                    var currentUrl                   = window.location.href;
                    var queryString                  = removeURLParameter(currentUrl, 'destination_location_'+random_id);
                    var perm = "?" + (queryString != '' ? queryString : '') + "&destination_location_"+random_id+"=" + pickupAddress  + "&destination_location_latitude_"+random_id+"=" + latitude + "&destination_location_longitude_"+random_id+"=" + longitude;
                    window.history.replaceState(null, null, perm);

                    $('#destination_location_'+random_id).attr("style", "display: none !important");

                }
            }).get();
        }

        displayLocationCab(latitude, longitude);
        getVendorList();   
    });
    function getVendorList(){
        if(wallet_balance < 0)
        return false;
        var locations = [];
        let vendor_id = $(this).data('vendor');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        var post_data = JSON.stringify(locations);
        let pickup_location = $('#pickup_location').val();
        let destination_location = $('#destination_location').val();
        if(pickup_location && destination_location){
            $('.location-list').hide();
            $('.cab-booking-main-loader').show();
            $.ajax({
                data: {locations: post_data},
                type: "POST",
                dataType: 'json',
                url: autocomplete_urls,
                success: function(response) {
                    if(response.status == 'Success'){
                        // $('.cab-booking-main-loader').hide();
                        $('#vendor_main_div').html('');
                        if(response.data.length != 0){
                            let vendors_template = _.template($('#vendors_template').html());
                            $("#vendor_main_div").append(vendors_template({results: response.data})).show();
                            if(response.data.length == 1){
                                $('.vendor-list').trigger('click');
                                $('.table-responsive').remove();
                            }else{
                                $('.vendor-list').first().trigger('click');
                            }
                        }else{
                            $("#vendor_main_div").html('<p class="text-center my-3">{{ __("No result found. Please try a new search") }}</p>').show();
                        }
                       // $('.cab-booking-main-loader').hide();
                    }
                }
            });
        }
    }
    $(document).on("click",".vendor-list",function() {
        var locations = [];
        $('.cab-booking-main-loader').show();
        let vendor_id = $(this).data('vendor');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {locations:locations},
            url: get_vehicle_list+'/'+vendor_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('.cab-booking-main-loader').hide();
                    $('#search_product_main_div').html('');
                    if(response.data.length != 0){
                        let products_template = _.template($('#products_template').html());
                        $("#search_product_main_div").append(products_template({results: response.data.products})).show();
                    }else{
                        $("#search_product_main_div ").html('<p class="text-center my-3">{{ __("No result found. Please try a new search") }}</p>').show();
                    }
                }
            }
        });
    });
    $(document).on("click","#promo_code_list_btn_cab_booking",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promo_code_list_url,
            data: {amount:amount, vendor_id:vendor_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_booking_promo_code_list_main_div').html('');
                    if(response.data.length != 0){
                        $('.promo-box').removeClass('d-none');
                        $('.cab-detail-box').addClass('d-none');
                        let cab_booking_promo_code_template = _.template($('#cab_booking_promo_code_template').html());
                        $("#cab_booking_promo_code_list_main_div").append(cab_booking_promo_code_template({promo_codes: response.data, vendor_id:vendor_id, product_id:product_id, amount:amount})).show();
                    }else{
                        $('.promo-box').removeClass('d-none');
                        $('.cab-detail-box').addClass('d-none');
                        let cab_booking_promo_code_template = _.template($('#cab_booking_promo_code_template').html());
                        $("#cab_booking_promo_code_list_main_div").html(no_coupon_available_message).show();
                    }
                }
            }
        });
    });
    $(document).on("click","#remove_promo_code_cab_booking_btn",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: cab_booking_promo_code_remove_url,
            data: {amount:amount, vendor_id:vendor_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('#promo_code_list_btn_cab_booking').show();
                    $('#remove_promo_code_cab_booking_btn').hide();
                    $('.cab-detail-box #discount_amount').text('').hide();
                    $('.cab-detail-box .code-text').text("Select A Promo Code").show();
                    $('.cab-detail-box #real_amount').text(response.data.currency_symbol+' '+amount);
                    $('#pickup_now').attr("data-coupon_id",'');
                    $('#pickup_later').attr("data-coupon_id",'');
                }
            }
        });
    });
    $(document).on("click",".cab_booking_apply_promo_code_btn",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url:  apply_cab_booking_promocode_coupon_url,
            data: {amount:amount, vendor_id:vendor_id, product_id:product_id, coupon_id:coupon_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('.promo-box').addClass('d-none');
                    $('.cab-detail-box').removeClass('d-none');
                    $('#promo_code_list_btn_cab_booking').hide();
                    $('#remove_promo_code_cab_booking_btn').show();
                    let real_amount = $('.cab-detail-box #real_amount').text();
                    $('.cab-detail-box #discount_amount').text(real_amount).show();
                    $('.cab-detail-box .code-text').text('Code '+response.data.name+' applied').show();
                    $('#pickup_now').attr("data-coupon_id",coupon_id);
                    $('#pickup_later').attr("data-coupon_id",coupon_id);
                    var current_amount = amount - response.data.new_amount;
                    $('.cab-detail-box #real_amount').text(response.data.currency_symbol+''+current_amount);
                }
            }
        });
    });
    $(document).on("click",".close-promo-code-detail-box",function() {
        $('.promo-box').addClass('d-none');
        $('.cab-detail-box').removeClass('d-none');
    });
 
    $(document).on("click",".close-cab-detail-box",function() {
        $('.cab-detail-box').addClass('d-none');
        $('.address-form').removeClass('d-none');
    });
    $(document).on("click",".skip-clear",function() {
        $('.cab-detail-box').attr("style", "display: block !important");
        $('.scheduled-ride-list').attr("style", "display: none !important");
    });
    $(document).on("click",".vehical-view-box",function() {
        $('.cab-booking-main-loader').show();
        var locations = [];
        let product_id = $(this).data('product_id');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {locations:locations},
            url: get_product_detail+'/'+product_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_detail_box').html('');
                    $('.cab-booking-main-loader').hide();
                    if(response.data.length != 0){
                        $('.address-form').addClass('d-none');
                        $('.cab-detail-box').removeClass('d-none');
                        let cab_detail_box_template = _.template($('#cab_detail_box_template').html());
                        $("#cab_detail_box").append(cab_detail_box_template({result: response.data})).show();
                        getDistance();
                    }else{
                        $("#cab_detail_box ").html('<p class="text-center my-3">{{ __("No result found. Please try a new search") }}</p>').show();
                    }
                }
            }
        });
    });

    $(document).on("click",".edit-pickup",function() {
        $(".check-pick-first").css("display", "block");
        $(".check-dropoff-secpond").css("display", "none");
        $('.check-pickup').attr("style", "display: block !important");
        $('.check-dropoff').attr("style", "display: none !important");
    });

    $(document).on("click","#get-current-location",function() {
        
        var currentLocation          = $('#address-input').val();
        var currentLocationLatitude  = $('#address-latitude').val();
        var currentLocationLongitude = $('#address-longitude').val();
        if($('.check-pickup').css('display') == 'block')
        {
            $('#pickup_location').val(currentLocation);
            $('#pickup_location_latitude').val(currentLocationLatitude);
            $('#pickup_location_longitude').val(currentLocationLongitude);

            // initMap2();
            var pickupLocationLatitude  = currentLocation;
            var pickupLocationLongitude = currentLocationLatitude;
            var currentUrl              = window.location.href;
            var queryString             = removeURLParameter(currentUrl, 'pickup_location');
            var perm                    = "?pickup_location=" + currentLocation + "&pickup_location_latitude=" + pickupLocationLatitude +"&pickup_location_longitude=" + pickupLocationLongitude + (queryString != '' ? "&" + queryString : '');
            window.history.replaceState(null, null, perm);

            $(".check-pick-first").css("display", "none");
            $("#pickup-where-from").html(" "+currentLocation);
            $(".check-dropoff-secpond").css("display", "block");
            $('.check-pickup').attr("style", "display: none !important");
            $(".check-dropoff").css("display", "block");

            getLocation();
        }else if($('.check-dropoff').css('display') == 'block'){
            $('#destination_location').val(currentLocation);
            $('#destination_location_latitude').val(currentLocationLatitude);
            $('#destination_location_longitude').val(currentLocationLongitude);

            var currentUrl  = window.location.href;
            var queryString = removeURLParameter(currentUrl, 'destination_location');
            var perm        = "?" + (queryString != '' ? queryString : '') + "&destination_location=" + currentLocation  + "&destination_location_latitude=" + currentLocationLatitude +"&destination_location_longitude=" + currentLocationLongitude;
            window.history.replaceState(null, null, perm);
            
            $("#dropoff-where-to").html(" "+currentLocation);
            $('.where-to-first').attr("style", "display: none !important");
            $('.check-dropoff').attr("style", "display: none !important");
            $(".where-to-second").css("display", "block");
            $('.add-more-location').attr("style", "display: block !important");

            $('#search_product_main_div').attr("style", "display: block !important");
            $('.location-list').attr("style", "display: none !important");


            getLocation();
        }else{
            $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
                console.log(this.value);
                if(this.value == ''){
                    var inputId = this.id;
                    $('#'+inputId).val(currentLocation);
                    var random_id = $(this).data( "rel" );
                    $('#destination_location_latitude_'+random_id).val(currentLocationLatitude);
                    $('#destination_location_longitude_'+random_id).val(currentLocationLongitude);
                    
                    $('#search_product_main_div').attr("style", "display: block !important");
                    $('.location-list').attr("style", "display: none !important");

                    let destination_location_template_li = _.template($('#destination_location_template_li').html());
                    var destination_location = $('#destination_location_'+random_id).val();            
                    $('#location_input_main_div li:last-child').after(destination_location_template_li({random_id:random_id}));
                    $("#dropoff-where-to-"+random_id).html(" "+ currentLocation);

                    var currentUrl                   = window.location.href;
                    var queryString                  = removeURLParameter(currentUrl, 'destination_location_'+random_id);
                    var perm = "?" + (queryString != '' ? queryString : '') + "&destination_location_"+random_id+"=" + currentLocation  + "&destination_location_latitude_"+random_id+"=" + currentLocationLatitude + "&destination_location_longitude_"+random_id+"=" + currentLocationLongitude;
                    window.history.replaceState(null, null, perm);
                }
            }).get();
        }
        
        
        
    });
    $(document).on("click",".edit-dropoff",function() {
        $(".check-dropoff-secpond").css("display", "block");
        $('.add-more-location').attr("style", "display: none !important");
        $('.where-to-second').attr("style", "display: none !important");
        $('.where-to-first').attr("style", "display: block !important");
        $('.check-dropoff').attr("style", "display: block !important");
        $('#destination_location_add_temp').attr("style", "display: none !important");

        $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
            if(this.value == ''){
                var inputId = this.id;
                $("#"+inputId).remove();
            }
        }).get();

    });

    $(document).on("click",".scheduled-ride",function() {
        $('.location-list').attr("style", "display: none !important");
        $('.scheduled-ride-list').attr("style", "display: block !important");
        var fromDate = moment();
        var toDate   = moment().add(31, 'days');
        enumerateDaysBetweenDates(fromDate, toDate);
    });

    var enumerateDaysBetweenDates = function(startDate, endDate) {
        var now = startDate, dates = [];
        var i = 1;
        while (now.isSameOrBefore(endDate)) {
            i++;
            if(now.format('MMDDYYYY') == moment().format('MMDDYYYY')){
                var lableText = 'Today';
            }else{
                var lableText = now.format('ddd, D MMM');
            }
            var scheduledDate = '<div class="form-check radio check-active" id="schedule-date-'+now.format('MMDDYYYY')+'" ><input class="form-check-input" id="'+now.format('MMDDYYYY')+'" type="radio"'+ ((now.format('MMDDYYYY') == moment().format('MMDDYYYY')) ? 'checked': '') +' onclick="appendScheduleTime(this)" name="scheduledDate" data-mdi="schedule-date-'+now.format('MMDDYYYY')+'" value="'+now.format('MM-DD-YYYY')+'"><label class="radio-label" id="lable-schedule-date-'+now.format('MMDDYYYY')+'" for="'+now.format('MMDDYYYY')+'">'+lableText+'</label></div>';
            $(".date-radio-list").append(scheduledDate);
            if(now.format('MMDDYYYY') == moment().format('MMDDYYYY')){
                $('.scheduled-ride-list').find('.scheduleTime').remove();
                let scheduleTime_template = _.template($('#scheduleTime_template').html());
                var mainDivId = 'schedule-date-'+now.format('MMDDYYYY');
                $("#"+mainDivId).append(scheduleTime_template).show();
            }
            now.add(1, 'days');
        }
        
        $(".scheduled-footer").append('<button id="check-schedule-date-time" onclick="getScheduleDateTime(this)" disabled>Select</button>');
    };

    

    $(document).on("click",".apremove",function() {
        var destination_location_add_temp = $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').length;
        
        if(destination_location_add_temp == 0){
            return false;
        }else if(destination_location_add_temp == 1){
            var destination_location_names = $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
                if(this.value == ''){
                    return "empty";
                }
            }).get();

            if(destination_location_names[0] == 'empty'){
                return false;
            }

            var destination_location = $("#destination_location").val();
            if(destination_location == undefined){
                return false;
            }
            
        }else if(destination_location_add_temp == 2){
            var destination_location_names = $('#destination_location_add_temp').find('input[name="destination_location_name[]"]').map(function(){
                if(this.value == ''){
                    return "empty";
                }
            }).get();

            var destination_location = $("#destination_location").val();
            if(destination_location_names[0] == 'empty' && destination_location == undefined){
                return false;
            }
            
        }

        var random_id = $(this).data('rel');

        if(random_id == ''){
            $(".where-to-second").remove();
            $("#destination_location").remove();
            $("#destination_location_latitude").remove();
            $("#destination_location_longitude").remove();

            var currentUrl = window.location.href;
            query1 = removeURLParameter(currentUrl, 'destination_location');
            var perm        = (query1 != '' ? "?" + query1 : '');
            window.history.replaceState(null, null, perm);

            var currentUrl2 = window.location.href;
            query2 = removeURLParameter(currentUrl2, 'destination_location_latitude');
            var perm        = (query2 != '' ? "?" + query2 : '');
            window.history.replaceState(null, null, perm);

            var currentUrl3 = window.location.href;
            var query3 = removeURLParameter(currentUrl3, 'destination_location_longitude');
            var perm        = (query3 != '' ? "?" + query3 : '');
            window.history.replaceState(null, null, perm);
        }else{
            
            $("#dots_"+random_id).remove();
            $("#destination_location_"+random_id).remove();
            $("#destination_location_latitude_"+random_id).remove();
            $("#destination_location_longitude_"+random_id).remove();

            var currentUrl = window.location.href;
            query1 = removeURLParameter(currentUrl, 'destination_location_'+random_id);
            var perm        = (query1 != '' ? "?" + query1 : '');
            window.history.replaceState(null, null, perm);

            var currentUrl2 = window.location.href;
            query2 = removeURLParameter(currentUrl2, 'destination_location_latitude_'+random_id);
            var perm        = (query2 != '' ? "?" + query2 : '');
            window.history.replaceState(null, null, perm);

            var currentUrl3 = window.location.href;
            var query3 = removeURLParameter(currentUrl3, 'destination_location_longitude_'+random_id);
            var perm        = (query3 != '' ? "?" + query3 : '');
            window.history.replaceState(null, null, perm);
        }

        const waypts = [];
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
            return this.value;
         }).get();
 
         $(destination_location_names).each(function(index, destination_location_name) {
             waypts.push({
                 location: destination_location_name,
                 stopover: true,
               });
         });
 
        var random_id = $("#destination_location_add_temp input:last").data("rel");

        let origin = $('#pickup_location').val();
        let destination = (random_id != undefined) ? $('#destination_location_'+random_id).val() : $('#destination_location').val();
        if(origin && destination){
            directionsService.route({
                origin: origin,
                waypoints:waypts,
                optimizeWaypoints:true,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            }, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                var point = response.routes[0].legs[0];
                directionsDisplay.setDirections(response);
                getVendorList();
                } else {
                window.alert('Directions request failed due to ' + status);
                return false;
                }
            });
        }
         
        
    });

    $(document).on("click",".edit-other-stop",function() {
        var random_id = $(this).attr("id");
        $("#destination_location_add_temp").attr("style", "display: block !important");
        $("#destination_location_"+random_id).attr("style", "display: block !important");
        initializeNew(random_id);
    });
   
    var query = window.location.search.substring(1);
   // var query = str.replace(query.replace('+', ' ');
  //  var uri_enc = encodeURIComponent(uri);
  //  var uri_dec = decodeURIComponent(uri_enc);
    if(query != ''){
        var vars = query.split('&');
        for(i = 0; i<vars.length; i++){
            vars[i] = decodeURIComponent(vars[i].replace(/\+/g, ' '));
            var perm = vars[i].split('=');
            if(perm[0] == 'pickup_location'){
                var pickup_location = window.unescape(perm[1]);
                $('#pickup_location').val(pickup_location);
            }else if(perm[0] == 'pickup_location_latitude'){
                $('#pickup_location_latitude').val(perm[1]);
            }else if(perm[0] == 'pickup_location_longitude'){
                $('#pickup_location_longitude').val(perm[1]);
            }else if(perm[0] == 'destination_location'){
                var destination_location = window.unescape(perm[1]);
                $('#destination_location').val(destination_location);
            }else if(perm[0] == 'destination_location_latitude'){
                $('#destination_location_latitude').val(perm[1]);
            }else if(perm[0] == 'destination_location_longitude'){
                $('#destination_location_longitude').val(perm[1]);
            }else if(perm[0] == 'schedule_date'){
                $('#schedule_date').val(perm[1]);
                $('#schedule_date_time').val(perm[1]);
            }else if(perm[0] == 'schedule_date_set'){
                $('.scheduleDateTimeApnd').html(perm[1]);
            }
        }

        var pickup_location      = $("#pickup_location").val();
        var destination_location = $("#destination_location").val();
        if(pickup_location != '' && destination_location != ''){
            $(".check-pick-first").css("display", "none");
            $("#pickup-where-from").html(" "+pickup_location);
            $(".check-dropoff-secpond").css("display", "block");
            $('.check-pickup').attr("style", "display: none !important");
            $(".check-dropoff").css("display", "block");
            
            $("#dropoff-where-to").html(" "+destination_location);
            $('.where-to-first').attr("style", "display: none !important");
            $('.check-dropoff').attr("style", "display: none !important");
            $(".where-to-second").css("display", "block");
            $('.add-more-location').attr("style", "display: block !important");
            getVendorList();
            setTimeout(function(){
                initMap2();
            }, 1000);
        }else if(pickup_location != ''){
            $(".check-pick-first").css("display", "none");
            $("#pickup-where-from").html(" "+pickup_location);
            $(".check-dropoff-secpond").css("display", "block");
            $('.check-pickup').attr("style", "display: none !important");
            $(".check-dropoff").css("display", "block");
        }
    }


    function initMap2(random_id = '') {
        var locations = [];
        let pickup_location_latitude  = $('#pickup_location_latitude').val();
        let pickup_location_longitude = $('#pickup_location_longitude').val();
        

        var pointA = new google.maps.LatLng(pickup_location_latitude, pickup_location_longitude);
        map = new google.maps.Map(document.getElementById('booking-map'), {zoom: 7,center: pointA});
        map.setOptions({ styles:  styles});
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer({map: map});
        calculateAndDisplayRoute(directionsService, directionsDisplay, random_id);
    }
    function calculateAndDisplayRoute(directionsService, directionsDisplay, random_id = '') {
        const waypts = [];
        if(random_id != ''){
            if($("#dots_" + random_id).length == 1){
                var destination_location = $('#destination_location_'+random_id).val(); 
                $("#dropoff-where-to-"+random_id).html(" "+destination_location);
            }else{
                let destination_location_template_li = _.template($('#destination_location_template_li').html());
                var destination_location = $('#destination_location_'+random_id).val();            
                $('#location_input_main_div li:last-child').after(destination_location_template_li({random_id:random_id}));
                $("#dropoff-where-to-"+random_id).html(" "+destination_location);
            }
            
        }
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
           return this.value;
        }).get();

        $(destination_location_names).each(function(index, destination_location_name) {
            waypts.push({
                location: destination_location_name,
                stopover: true,
              });
        });

        let origin = $('#pickup_location').val();
        let destination = (random_id != '') ? $('#destination_location_'+random_id).val() : $('#destination_location').val();
        if(origin && destination){
            directionsService.route({
                origin: origin,
                waypoints:waypts,
                optimizeWaypoints:true,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            }, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                  var point = response.routes[0].legs[0];
                  directionsDisplay.setDirections(response);
                  getVendorList();
                } else {
                  window.alert('Directions request failed due to ' + status);
                  return false;
                }
            });
        }
    }
    initialize();
    function initialize() {
      var input = document.getElementById('pickup_location');
      var input2 = document.getElementById('destination_location');
      if(input){
        var autocomplete = new google.maps.places.Autocomplete(input);
        var autocomplete2 = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            $('#pickup_location_latitude').val(place.geometry.location.lat());
            $('#pickup_location_longitude').val(place.geometry.location.lng());
            initMap2();
            
            var pickup_location = $("#pickup_location").val();
            if(pickup_location != ""){
                var pickupLocationLatitude  = place.geometry.location.lat();
                var pickupLocationLongitude = place.geometry.location.lng();
                var currentUrl              = window.location.href;
                var queryString             = removeURLParameter(currentUrl, 'pickup_location');
                var perm                    = "?pickup_location=" + pickup_location + "&pickup_location_latitude=" + pickupLocationLatitude +"&pickup_location_longitude=" + pickupLocationLongitude + (queryString != '' ? "&" + queryString : '');
                window.history.replaceState(null, null, perm);

                $(".check-pick-first").css("display", "none");
                $("#pickup-where-from").html(" "+pickup_location);
                $(".check-dropoff-secpond").css("display", "block");
                $('.check-pickup').attr("style", "display: none !important");
                $(".check-dropoff").css("display", "block");
            }
            
        });
        google.maps.event.addListener(autocomplete2, 'place_changed', function () {
            var place2 = autocomplete2.getPlace();
            $('#destination_location_latitude').val(place2.geometry.location.lat());
            $('#destination_location_longitude').val(place2.geometry.location.lng());
            initMap2();

            var pickup_location      = $("#pickup_location").val();
            var destination_location = $("#destination_location").val();
            if(pickup_location != '' && destination_location != ''){
                
                var currentUrl  = window.location.href;
                var queryString = removeURLParameter(currentUrl, 'destination_location');
                var perm        = "?" + (queryString != '' ? queryString : '') + "&destination_location=" + destination_location  + "&destination_location_latitude=" + place2.geometry.location.lat() +"&destination_location_longitude=" + place2.geometry.location.lng();
                window.history.replaceState(null, null, perm);
                
                $("#dropoff-where-to").html(" "+destination_location);
                $('.where-to-first').attr("style", "display: none !important");
                $('.check-dropoff').attr("style", "display: none !important");
                $(".where-to-second").css("display", "block");
                $('.add-more-location').attr("style", "display: block !important");
            }
            
        });
      }
    }

    

    function removeURLParameter(url, parameter) {
        //prefer to use l.search if you have a location/link object
        var urlparts = url.split('?');   
        if (urlparts.length >= 2) {
    
            var prefix = encodeURIComponent(parameter) + '=';
            var pars = urlparts[1].split(/[&;]/g);
    
            //reverse iteration as may be destructive
            for (var i = pars.length; i-- > 0;) {    
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                    pars.splice(i, 1);
                }
            }
            return (pars.length > 0 ? pars.join('&') : '');
        }
        return false;
    }

    function getQueryStringVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i=0; i<vars.length; i++) {
            var pair = vars[i].split('=');
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    
        return false;
   }

    function getDistance(){
            //Find the distance
            var distanceService = new google.maps.DistanceMatrixService();
            distanceService.getDistanceMatrix({
            origins: [$("#pickup_location").val()],
            destinations: [$("#destination_location").val()],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            durationInTraffic: true,
            avoidHighways: false,
            avoidTolls: false
        },
        function (response, status) {
            if (status !== google.maps.DistanceMatrixStatus.OK) {
                console.log('Error:', status);
            } else {
                console.log('distance is'+response.rows[0].elements[0].distance.text);
                console.log('duration is'+response.rows[0].elements[0].duration.text);
                $("#distance").text(response.rows[0].elements[0].distance.text).show();
                $("#duration").text(response.rows[0].elements[0].duration.text).show();
            }
        });
    }
    
    function getLocation() {
        console.log('navigator.geolocation', navigator.geolocation);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, null);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    function showPosition(position) {
        let lat = position.coords.latitude;
        let long = position.coords.longitude;
        $('#addHeader1-latitude').val(lat);
        $('#addHeader1-longitude').val(long);
        displayLocationCab(lat, long);
    }
    if (!selected_address) {
        getLocation();
    }
    let lat = $("#booking-latitude").val();
    let long = $("#booking-longitude").val();
    displayLocationCab(lat, long);
    function displayLocationCab(latitude, longitude) {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);
        const map = new google.maps.Map(document.getElementById('booking-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });
        map.setOptions({ styles:  styles});
        var icon_set = {
            url: live_location, // url
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            icon : icon_set,
        });
        geocoder.geocode(
            { 'latLng': latlng },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add = results[0].formatted_address;
                        var value = add.split(",");
                        count = value.length;
                        country = value[count - 1];
                        state = value[count - 2];
                        city = value[count - 3];
                        $("#addHeader1-input").val(add);
                        $("#location_search_wrapper .homepage-address span").text(value).attr({ "title": value, "data-original-title": value });
                    }else {
                    }
                }else {
                    $("#address-input").val("Geocoder failed due to: " + status);
                }
            }
        );
    }



     
});




function appendScheduleTime(thisObj){
    $('.scheduled-ride-list').find('.scheduleTime').remove();
    let scheduleTime_template = _.template($('#scheduleTime_template').html());
    var mainDivId = $(thisObj).data('mdi');
    $("#"+mainDivId).append(scheduleTime_template).show();
}

function getScheduleDateTime(thisObj){
    var mainDivId      = $('input[name=scheduledDate]:checked').data('mdi');
    var scheduledDate  = $("#lable-"+mainDivId).text();
    var scheduleHour   = $('.scheduleHour').find(":selected").val();
    var scheduleMinute = $('.scheduleMinute').find(":selected").val();
    var scheduleAmPm   = $('.scheduleAmPm').find(":selected").val();
    var waitTime       = parseFloat(scheduleMinute) + Number(10);
    var scheduleDateTime = scheduledDate + ", " + scheduleHour + ":" + scheduleMinute + " " + scheduleAmPm + " - " + scheduleHour + ":" + waitTime + " " + scheduleAmPm;
    var scheduleDateRadio =  $('input[name=scheduledDate]:checked').val();
    var scheduleHourHtml   = $('.scheduleHour').find(":selected").html();
    var scheduleMinuteHtml = $('.scheduleMinute').find(":selected").html();
    var scheduleDateTimeSet = scheduleDateRadio +" "+ scheduleHourHtml + ":" + scheduleMinuteHtml + " " + scheduleAmPm;
    var currentUrl  = window.location.href;
    var queryString = removeURLParameterNew(currentUrl, 'schedule_date');
    var perm = "?" + (queryString != '' ? queryString : '') + "&schedule_date=" + scheduleDateTimeSet;
    window.history.replaceState(null, null, perm);


    var currentUrl  = window.location.href;
    var queryString = removeURLParameterNew(currentUrl, 'schedule_date_set');
    var perm = "?" + (queryString != '' ? queryString : '') +"&schedule_date_set=" + scheduleDateTime;
    window.history.replaceState(null, null, perm);

    $('#schedule_date').val(scheduleDateTimeSet);
    $('.scheduleDateTimeApnd').text(scheduleDateTime);
    $('#schedule_datetime').val(scheduleDateTimeSet);
  

    $('.cab-detail-box').attr("style", "display: block !important");
    $('.scheduled-ride-list').attr("style", "display: none !important");

}

function removeURLParameterNew(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts = url.split('?');   
    if (urlparts.length >= 2) {

        var prefix = encodeURIComponent(parameter) + '=';
        var pars = urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i = pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }
        return (pars.length > 0 ? pars.join('&') : '');
    }
    return false;
}

function checkScheduleDateTime(thisObj){
    var scheduledDate = $('input[name=scheduledDate]:checked').val();
    var scheduleHour   = $('.scheduleHour').find(":selected").val();
    var scheduleMinute = $('.scheduleMinute').find(":selected").val();
    var scheduleAmPm   = $('.scheduleAmPm').find(":selected").val();
    if(scheduledDate != '' && scheduleHour != '' && scheduleMinute != '' && scheduleAmPm != ''){
        $('#check-schedule-date-time').prop('disabled', false);
    }else{
        $('#check-schedule-date-time').prop('disabled', true);
    }
    
}

function addressInputDisplay(locationWrapper, inputWrapper, input) {
    $(inputWrapper).removeClass("d-none").addClass("d-flex");
    $(locationWrapper).removeClass("d-flex").addClass("d-none");
    var val = $(input).val();
    $(input).focus().val('').val(val);
}

function addressInputHide(locationWrapper, inputWrapper, input) {
    $(inputWrapper).addClass("d-none").removeClass("d-flex");
    $(locationWrapper).addClass("d-flex").removeClass("d-none");
}

function initMap() {
    const autocompletes = [];
    const locationInputs = document.getElementsByClassName("map-input");
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {
        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';
        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;
        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: { lat: latitude, lng: longitude },
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: latitude, lng: longitude }
        });
        marker.setVisible(isEdit);
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({ input: input, map: map, marker: marker, autocomplete: autocomplete });
    }
    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            geocoder.geocode({ 'placeId': place.place_id }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    $(".homepage-address span").text(place.formatted_address);
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });
            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(13);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        });
    }
}
function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}
google.maps.event.addDomListener(window, 'load', initMap);





