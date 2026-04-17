<!-- bundle -->
<!-- Vendor js -->
<?php
if (Session::has('toaster')) {
    $toast = Session::get('toaster');
    echo '<script>
            $(document).ready(function(){
                $.NotificationApp.send("' . $toast["title"] . '", "' . $toast["body"] . '", "top-right", "' . $toast["color"] . '", "' . $toast["type"] . '");
            });
        </script>';
}
?>


<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/js/pages/my-form-advanced.init.js')}}"></script>
<script src="{{asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js')}}"></script>
<script src="{{asset('assets/js/pages/toastr.init.js')}}"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

<script>
    let stripe_publishable_key = "{{ $stripe_publishable_key }}";
    let is_hyperlocal = 0;
    @if(Session::has('preferences'))
    @if((isset(Session::get('preferences')['is_hyperlocal'])) && (Session::get('preferences')['is_hyperlocal'] == 1))
    is_hyperlocal = 1;
    @endif;
    @endif;
    var base_url = "{{ url('/')}}";

    function gm_authFailure() {
        $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        $('.displaySettingsError').show();
    };

    const startLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = "body";
        }
        // set the wait me loader
        $(element).waitMe({
            effect: 'bounce',
            text: 'Please Wait..',
            bg: 'rgba(255,255,255,0.7)',
            //color : 'rgb(66,35,53)',
            color: '#EFA91F',
            sizeW: '20px',
            sizeH: '20px',
            source: ''
        });
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;
        }
        return true;
    }

    const stopLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = 'body';
        }
        // close the loader
        $(element).waitMe("hide");
    }
</script>
@if(!str_contains(url()->current(), '/godpanel'))
@if((!empty(Auth::user())))
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    // var ip_address = window.location.host;
    // var host_arr = ip_address.split(".");
    // let socket = io(constants.socket_domain, {
    //     query: {
    //         "user_id": host_arr[0] + "_" + "{{ Auth::user()->id }}",
    //         "subdomain": host_arr[0]
    //     }
    // });
    // socket.on('createOrderByCustomer_' + host_arr[0] + "_" + "{{ (!empty(Auth::user()))?Auth::user()->id:0 }}", (message) => {
    //     get_latest_order_socket(message.order_number);
    // });

    function get_latest_order_socket(order_number){
        Audio.prototype.play = (function(play) {
            return function() {
                var audio = this,
                    args = arguments,
                    promise = play.apply(audio, args);
                if (promise !== undefined) {
                    promise.catch(_ => {
                        // Autoplay was prevented. This is optional, but add a button to start playing.
                        var el = document.createElement("button");
                        el.innerHTML = "Play";
                        el.addEventListener("click", function() {
                            play.apply(audio, args);
                        });
                        this.parentNode.insertBefore(el, this.nextSibling)
                    });
                }
            };
        })(Audio.prototype.play);
        var x = document.getElementById("orderAudio");
        x.play();
        $.ajax({
            url: "{{ route('orders.filter') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                filter_order_status: "pending_orders",
                search_keyword: order_number
            },
            success: function(response) {
                if (response.status == 'Success') {
                    if (response.data.orders.data.length != 0) {
                        let latest_order_template = _.template($('#latest_order_template').html());
                        $("#received_new_orders").find(".modal-body").append(latest_order_template({
                            orders: response.data.orders.data
                        }));
                        $("#received_new_orders").modal('show');
                    }
                }
            },
            error: function(data) {

            },
        });
    }
</script>
@if(Session::has('preferences') && !empty(Session::get('preferences')['fcm_api_key']))
<script>
    var firebaseCredentials = {!!json_encode(Session::get('preferences')) !!};
    var firebaseConfig = {
        apiKey: firebaseCredentials.fcm_api_key,
        authDomain: firebaseCredentials.fcm_auth_domain,
        projectId: firebaseCredentials.fcm_project_id,
        storageBucket: firebaseCredentials.fcm_storage_bucket,
        messagingSenderId: firebaseCredentials.fcm_messaging_sender_id,
        appId: firebaseCredentials.fcm_app_id,
        measurementId: firebaseCredentials.fcm_measurement_id
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();
    function initFirebaseMessagingRegistration() {
        @if(empty(Session::get('current_fcm_token')))
        messaging.requestPermission().then(function() {
            return messaging.getToken()
        }).then(function(token) {
            $.ajax({
                url: "{{ route('client.save_fcm') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    fcm_token: token,
                },
                success: function(response) {

                },
            });
            console.log(token);

        }).catch(function(err) {
            console.log(`Token Error :: ${err}`);
        });
        @endif
    }

    initFirebaseMessagingRegistration();
    messaging.onMessage(function(payload) {
        if (!("Notification" in window)) {
            console.log("This browser does not support system notifications.");
        } else if (Notification.permission === "granted") {
            if(payload && payload.data && payload.data.data){
                if(payload.data.type && payload.data.type=="order_created"){
                    var payload_data = JSON.parse(payload.data.data);
                    get_latest_order_socket(payload_data.order_number);
                }
            }
        }
    });
</script>
@endif
@endif
<script>
    $(document).on("click", ".update_order_status", function() {
        if (confirm("Are you Sure?")) {
            let that = $(this);
            var count = that.data("count");
            var full_div = that.data("full_div");
            var single_div = that.data("single_div");
            var status_option_id = that.data("status_option_id");
            var status_option_id_next = status_option_id + 1;
            var order_vendor_id = that.data("order_vendor_id");
            var order_id = that.data("order_id");
            var vendor_id = that.data("vendor_id");
            var count = that.data("count");

            $.ajax({
                url: "{{ route('order.changeStatus') }}",
                type: "POST",
                data: {
                    order_id: order_id,
                    vendor_id: vendor_id,
                    "_token": "{{ csrf_token() }}",
                    status_option_id: status_option_id,
                    order_vendor_id: order_vendor_id,
                },
                success: function(response) {

                    if (status_option_id == 4 || status_option_id == 5) {
                        if (status_option_id == 4)
                            var next_status = 'Out For Delivery';
                        else
                            var next_status = 'Delivered';
                        that.replaceWith("<button class='update-status btn-warning' data-full_div='" + full_div + "' data-single_div='" + single_div + "'  data-count='" + count + "'  data-order_id='" + order_id + "'  data-vendor_id='" + vendor_id + "'  data-status_option_id='" + status_option_id_next + "' data-order_vendor_id=" + order_vendor_id + ">" + next_status + "</button>");
                        return false;
                    } else {
                        $(that).parents(single_div).slideUp(1000, function() {
                            $(this).remove();
                        });
                        setTimeout(function() {
                            if ($("#received_new_orders").find(".update_order_status").length == 0) {
                                $("#received_new_orders").modal('hide');
                            }
                        }, 2000);
                    }

                    if (status_option_id == 2)
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    // location.reload();
                    if (typeof init === 'function') {
                        init("pending_orders", "{{ route('orders.filter') }}", '', false);
                    }
                },
            });
        }
    });
</script>
@endif
@yield('script-bottom')