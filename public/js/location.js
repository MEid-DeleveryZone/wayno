jQuery(window).scroll(function () {
    var scroll = jQuery(window).scrollTop();

    if (scroll <= 192) {

        jQuery(".category-btns").removeClass("category-active-btns");

    } else {

        jQuery(".category-btns").addClass("category-active-btns");

    }
});
$(document).ready(function () {
    if (window.location.pathname == '/') {
        let latitude = "";
        let longitude = "";
        if($("#address-latitude").length > 0){
            latitude = $("#address-latitude").val();
        }
        if($("#address-longitude").length > 0){
            longitude = $("#address-longitude").val();
        }
        getHomePage(latitude, longitude);
        $(document).ready(function () {
            $.ajax({
                url: client_preferences_url,
                type: "POST",
                success: function (response) {
                    if ($.cookie("age_restriction") != 1) {
                        if (response.age_restriction == 1) {
                            $('#age_restriction').modal({backdrop: 'static', keyboard: false});
                        }
                    }
                    // if (response.delivery_check == 1) {
                    //     getHomePage("", "", "delivery");
                    // }
                    // else if (response.dinein_check == 1) {
                    //     getHomePage("", "", "dine_in");
                    // }
                    // else {
                    //     getHomePage("", "", "takeaway");
                    // }
                },
            });
        });

    
    }

    $(".age_restriction_no").click(function () {
        window.location.replace("https://google.com");
    });

    $(".age_restriction_yes").click(function () {
        $.cookie('age_restriction', 1);
        $('#age_restriction').modal('hide');
    });

    $( document ).ready(function() {
        $('.date-items').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 7,
            slidesToScroll: 5,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true
                }
            } ]
        });
        
        $('.booking-time').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true
                }
            }]
        });

        $('.hours-slot').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 9,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true
                }
            }]
        });
        
        $('.materials-slide').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true
                }
            }]
        });

        
    });
       
    $(document ).ready(function() {
        $("#number").hide();
        $("#add_btn").click(function(){
            $("#number").show();
            $(this).hide();
        });
        
    });

    if($(".vendor_mods .nav-link").hasClass('active')){
        var tabs = $('.vendor_mods .nav-link.active').parent('.navigation-tab-item').prevAll().length;
        if($('body').attr('dir') == 'rtl'){
            $(".navigation-tab-overlay").css({
                right: tabs * 130 + "px"
            });
        }else{
            $(".navigation-tab-overlay").css({
                left: tabs * 100 + "px"
            });
        }
    }

    $(".navigation-tab-item").click(function() {
        $(".navigation-tab-item").removeClass("active");
        $(this).addClass("active");
        if($('body').attr('dir') == 'rtl'){
            $(".navigation-tab-overlay").css({
                right: $(this).prevAll().length * 130 + "px"
            });
        }else{
            $(".navigation-tab-overlay").css({
                left: $(this).prevAll().length * 100 + "px"
            });
        }

        let latitude = "";
        let longitude = "";
        let type = "";
        var id = $(this).find('.nav-link').attr('id');
        if($("#address-latitude").length > 0){
            latitude = $("#address-latitude").val();
        }
        if($("#address-longitude").length > 0){
            longitude = $("#address-longitude").val();
        }
        if(id == "dinein_tab"){
            type = "dine_in";
        }else if(id == "takeaway_tab"){
            type = "takeaway";
        }else{
            type = "delivery";
        }
        // console.log()
        if(!$.hasAjaxRunning()){
            vendorType(latitude, longitude, type);
        }
    });

    function vendorType(latitude, longitude, type = "delivery"){
        $.ajax({
            type: "get",
            dataType: 'json',
            url: cart_details_url,
            success: function (response) {
                if (response.data != "") {
                    let cartProducts = response.data.products;
                    if (cartProducts != "") {
                        $("#remove_cart_modal").modal('show');
                        $("#remove_cart_modal #remove_cart_button").attr("data-cart_id", response.data.id);
                        $(".nav-tabs.vendor_mods").attr("data-mod", type);
                    } else {
                        getHomePage(latitude, longitude, type);
                    }
                } else {
                    getHomePage(latitude, longitude, type);
                }
            }
        });
    }

    /*$("#dinein_tab").click(function () {
        var url = "dine_in";
        getHomePage("", "", url);
    });

    $("#delivery_tab").click(function () {
        getHomePage();
    });

    $("#takeaway_tab").click(function () {
        var url = "takeaway";
        getHomePage("", "", url);
    });*/

    function getHomePage(latitude, longitude, vtype = "") {
        if(vtype != ''){
            vendor_type = vtype;
        }
        let selected_address = $("#address-input").val();
        let selected_place_id = $("#address-place-id").val();
        $(".homepage-address span").text(selected_address).attr({ "title": selected_address, "data-original-title": selected_address });
        $("#edit-address").modal('hide');
        let ajaxData = { type: vendor_type };
        if ((latitude) && (longitude) && (selected_address)) {
            ajaxData.latitude = latitude;
            ajaxData.longitude = longitude;
            ajaxData.selectedAddress = selected_address;
            ajaxData.selectedPlaceId = selected_place_id;
        }
        $.ajax({
            data: ajaxData,
            type: "POST",
            dataType: 'json',
            url: home_page_data_url,
            success: function (response) {
                if (response.status == "Success") {
                    $('#main-menu').smartmenus('destroy');
                    $("#main-menu").html('');
                    let nav_categories_template = _.template($('#nav_categories_template').html());
                    $("#main-menu").append(nav_categories_template({ nav_categories: response.data.navCategories }));
                    $("#main-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 }), $("#sub-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 });
                    var path = window.location.pathname;
                    if (path == '/') {
                        $(".slide-6").slick('destroy');
                        $(".product-4").slick('destroy');
                        $(".product-5").slick('destroy');
                        $(".brand-slider").slick('destroy');
                        $(".suppliers-slider").slick('destroy');
                        $(".recent-orders").slick('destroy');
                        if ($('.vendor-product').html() != '') {
                            $('.vendor-product').slick('destroy');
                        }
                        $(".slide-6, .product-4, .product-5, .vendor-product, .render_vendors, .render_brands, .recent-orders").html('');
                        $("#new_products").html('');
                        $("#best_sellers").html('');
                        $("#featured_products").html('');
                        $("#on_sale").html('');
                        let vendors = response.data.vendors;
                        let banner_template = _.template($('#banner_template').html());
                        let vendors_template = _.template($('#vendors_template').html());
                        let products_template = _.template($('#products_template').html());
                        let trending_vendors_template = _.template($('#trending_vendors_template').html());
                        let recent_orders_template = _.template($('#recent_orders_template').html());
                        $(".render_brands").append(banner_template({ brands: response.data.brands, type: brand_language }));
                        $(".render_vendors").append(vendors_template({ vendors: response.data.vendors , type: vendor_language}));
                        $(".render_new_products").append(products_template({ products: response.data.new_products, type: new_product_language }));
                        $(".render_best_sellers").append(products_template({ products: response.data.new_products, type: best_seller_product_language}));
                        $(".render_featured_products").append(products_template({ products: response.data.feature_products, type: featured_product_language }));
                        $(".render_on_sale").append(products_template({ products: response.data.on_sale_products, type: on_sale_product_language }));
                        $(".render_trending_vendors").append(trending_vendors_template({ trending_vendors: response.data.trending_vendors , type: vendor_language}));
                        $(".render_recent_orders").append(recent_orders_template({ recent_orders: response.data.active_orders}));
                        
                        if (response.data.new_products.length > 0) {
                            $('.render_full_new_products').removeClass('d-none');
                        } else {
                            $('.render_full_new_products1').addClass('d-none');
                        }
                        if (response.data.new_products.length > 0) {
                            $('.render_full_best_sellers').removeClass('d-none');
                        } else {
                            $('.render_full_best_sellers').addClass('d-none');
                        }
                        if (response.data.on_sale_products.length > 0) {
                            $('.render_full_on_sale').removeClass('d-none');
                        } else {
                            $('.render_full_on_sale').addClass('d-none');
                        }
                        if (response.data.feature_products.length > 0) {
                            $('.render_full_featured_products').removeClass('d-none');
                        } else {
                            $('.render_full_featured_products').addClass('d-none');
                        }
                        if (vendors.length > 0) {
                            $('#our_vendor_main_div').removeClass('d-none');
                        } else {
                            $('#our_vendor_main_div').addClass('d-none');
                        }
                        if (response.data.active_orders.length > 0) {
                            $('.render_full_recent_orders').removeClass('d-none');
                        } else {
                            $('.render_full_recent_orders').addClass('d-none');
                        }
                        initializeSlider();
                    }
                    else {
                        if ((latitude) && (longitude) && (selected_address)) {
                            window.location.href = home_page_url;
                        }
                    }
                } else {
                }
            }
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            var snippet = "reset-password";
            if (!window.location.href.includes(snippet)) {
                navigator.geolocation.getCurrentPosition(showPosition, null);
            }
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        let lat = position.coords.latitude;
        let long = position.coords.longitude;
        displayLocation(lat, long);
    }

    if (is_hyperlocal) {
        if (!selected_address) {
            getLocation();
        }
        let lat = $("#address-latitude").val();
        let long = $("#address-longitude").val();
        let placeId = $("#address-place-id").val();
        displayLocation(lat, long, placeId);
    }

    $(document).delegate(".confirm_address_btn", "click", function () {
        let latitude = $("#address-latitude").val();
        let longitude = $("#address-longitude").val();

        $.ajax({
            type: "get",
            dataType: 'json',
            url: cart_details_url,
            success: function (response) {
                if (response.data != "") {
                    let cartProducts = response.data.products;
                    if (cartProducts != "") {
                        $("#remove_cart_modal").modal('show');
                        $("#remove_cart_modal #remove_cart_button").attr("data-cart_id", response.data.id);
                    } else {
                        getHomePage(latitude, longitude);
                        let selected_address = $("#address-input").val();
                        $(".homepage-address span").text(selected_address).attr({ "title": selected_address, "data-original-title": selected_address });
                    }
                } else {
                    getHomePage(latitude, longitude);
                }
            }
        });
    });

    $(document).delegate("#remove_cart_button", "click", function () {
        let cart_id = $(this).attr("data-cart_id");
        $("#remove_cart_modal").modal('hide');
        removeCartData(cart_id);
    });

    function removeCartData(cart_id) {
        $.ajax({
            type: "post",
            dataType: 'json',
            url: delete_cart_url,
            data: { 'cart_id': cart_id },
            success: function (response) {
                if (response.status == 'success') {
                    let latitude = $("#address-latitude").val();
                    let longitude = $("#address-longitude").val();
                    let vendor_mod = "";
                    if($(".nav-tabs.vendor_mods .nav-link").length > 0){
                        vendor_mod = $(".nav-tabs.vendor_mods").attr("data-mod");
                    }
                    getHomePage(latitude, longitude, vendor_mod);
                }
            }
        });
    }

    function displayLocation(latitude, longitude, placeId='') {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);

        const map = new google.maps.Map(document.getElementById('address-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });

        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
        });

        var geodata = { 'latLng': latlng };
        if(placeId != ''){
            geodata.placeId = placeId;
        }

        geocoder.geocode(geodata,
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add = results[0].formatted_address;
                        var value = add.split(",");

                        count = value.length;
                        country = value[count - 1];
                        state = value[count - 2];
                        city = value[count - 3];
                        if (!selected_address) {
                            $("#address-input").val(add);
                            $(".homepage-address span").text(value).attr({ "title": value, "data-original-title": value });
                            getHomePage(latitude, longitude);
                        }
                    }
                    else {
                        // $("#address-input").val("address not found");
                    }
                }
                else {
                    $("#address-input").val("Geocoder failed due to: " + status);
                }
            }
        );
    }
});

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
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
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
            position: { lat: latitude, lng: longitude },
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
                    $("#address-place-id").val(place.place_id);
                    // $(".homepage-address span").text(place.formatted_address);
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


////   cab booking section 

$(document).on("input",".edit-other-stop",function() {
    var random_id = $(this).attr("id");
    var rel = $(this).attr("data-rel");
     initializeNewCabHome(random_id,rel);
});



  function initializeNewCabHome(random_id,rel) {
    var input = document.getElementById(random_id);
    var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
      var place = autocomplete.getPlace();
      document.getElementById(random_id+'_latitude_home').value = place.geometry.location.lat();
      document.getElementById(random_id+'_longitude_home').value = place.geometry.location.lng();

      
       
     
    });
  }



