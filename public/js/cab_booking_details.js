   ////////   **************  cab details page  *****************  ////////
// get driver details 
var maplat  = 30.7046;
var maplong = 76.7179;
var map = '';
var product_image = '';
var marker='';
var order_status = '';
var order_status_new = '';
var direction_set = 0;
var completed_ride = 0;
themeType = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            { visibility: "off" }
        ]
    }
];

initMap();

function initMap() {
      map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: {
            lat: maplat,
            lng: maplong
        },
        styles: themeType,
    });
   
}


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
                product_image = response.data.product_image;
                getOrderDriverDetails(response.data.dispatch_traking_url,response.data.id,product_image);
            }
        }
    });
}




function getOrderDriverDetails(dispatch_traking_url,order_id,product_image) {
    var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/');
    $.ajax({
        type:"POST",
        dataType: "json",
        url: order_tracking_details_url,
        data:{new_dispatch_traking_url:new_dispatch_traking_url,order_id:order_id},
        success: function( response ) {

            var alltask = response.data.tasks;
            var agent_location = response.data.agent_location;
            var order_status_new = response.data.order_details.dispatcher_status;
                
            showroute(alltask,agent_location,map,product_image);
            if(response.data.agent_location != null){
                $('#searching_main_div').remove();
                $('#driver_details_main_div').show();
                $('#driver_name').html(response.data.order.name).show();
                $('#driver_image').attr('src', response.data.agent_image).show();
                $('#driver_phone_number').html(response.data.order.phone_number).show();
                $("#dispatcher_status_show").html(response.data.order_details.dispatcher_status);
                var avgrating = response.data.avgrating;
                var fillStar = '';
                for (let i = 1; i <= avgrating; i++) {
                    fillStar += "<li><a href='#'><i class='fa fa-star' aria-hidden='true'></i></a></li>";
                }
                var emptystar = 5;
                var emptystarhtml = '';
                for (let i = 1; i <= emptystar-avgrating; i++) {
                    emptystarhtml += "<li><a href='#'><i class='fa fa-star-o' aria-hidden='true'></i></a></li>";
                }

                $(".product-rating-star").html(fillStar);
                $(".product-rating-star").append(emptystarhtml);

                $(".rating-count").html('('+response.data.total_order_by_agent+')');
                
                if(order_status != order_status_new){
                    const options = {
                        style: {
                            main: {
                                'background': 'pink',
                                'color': 'black',
                            },
                        },
                        settings: {
                            duration: 2000,
                        }
                    };
                    order_status = order_status_new;
                    iqwerty.toast.toast(response.data.order_details.dispatcher_status);
                }
               
            }

                      
            if(completed_ride == 0){
                if(response.data.order_details.dispatcher_status_option_id == 5){
                     completed_ride = 1;
                    $('#rating_of_cab').show();
                    $('.add_edit_review').attr("data-dispatch_order_id",response.data.order.id);
                }
            }

        }
    });
}



function showroute(alltask,agent_location,map,product_image){

    var url = window.location.origin;

    if(alltask.length > 0){
        var maplat  = parseFloat(alltask[0]['latitude']);
        var maplong = parseFloat(alltask[0]['longitude']);
    }else{
        var maplat  = 30.7046;
        var maplong = 76.7179;
    }        
    
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
   
    if(direction_set == 0){
        directionsRenderer.setMap(map);
        direction_set = 1; 
   }
    calculateAndDisplayRoute(directionsService, directionsRenderer,map);
    
    addMarker(agent_location,map,product_image);
   
  
    function calculateAndDisplayRoute(directionsService, directionsRenderer,map) {
        const waypts = [];
        const checkboxArray = document.getElementById("waypoints");

        for (let i = 0; i < alltask.length; i++) {
            if (i != alltask.length - 1 && alltask[i].task_status != 4 && alltask[i].task_status != 5 ) {
               console.log(alltask[i]);
                waypts.push({
                    location: new google.maps.LatLng(parseFloat(alltask[i].latitude), parseFloat(alltask[i]
                        .longitude)),
                    stopover: true,
                });

                
            }
            var image = url+'/assets/newicons/'+alltask[i].task_type_id+'.png';

            makeMarker({lat: parseFloat(alltask[i].latitude),lng:  parseFloat(alltask[i]
                        .longitude)},image,map);
        }


        if(agent_location != null){
            agentlat = agent_location.lat;
            agentlong = agent_location.long;
        }else{
            agentlat = alltask[0].latitude;
            agentlong = alltask[0].longitude;
        }
      

        directionsService.route({
                origin: new google.maps.LatLng(parseFloat(agentlat), parseFloat(agentlong)),
                destination: new google.maps.LatLng(parseFloat(alltask[alltask.length - 1].latitude),
                    parseFloat(alltask[alltask.length - 1].longitude)),
                waypoints: waypts,
                optimizeWaypoints: false,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === "OK" && response) {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0];
                    const summaryPanel = document.getElementById("directions-panel");
                   // summaryPanel.innerHTML = "";

                    // For each route, display summary information.
                    // for (let i = 0; i < route.legs.length; i++) {
                    //     const routeSegment = i + 1;
                    //     summaryPanel.innerHTML +=
                    //         "<b>Route Segment: " + routeSegment + "</b><br>";
                    //     summaryPanel.innerHTML += route.legs[i].start_address + " to ";
                    //     summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
                    //     summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
                    // }
                } else {
                    //window.alert("Directions request failed due to " + status);
                }
            }
        );
    }

    // Adds a marker to the map.
    function addMarker(agent_location,map,product_image) {
     // Add the marker at the clicked location, and add the next-available label
     // from the array of alphabetical characters.

     if(agent_location != null){
        agentlat = agent_location.lat;
        agentlong = agent_location.long;
    }else{
        agentlat = alltask[0].latitude;
        agentlong = alltask[0].longitude;
    }


     product_image = url+'/images/cab.svg';
     var image = {
     url: product_image, // url
     scaledSize: new google.maps.Size(30, 30), // scaled size
     origin: new google.maps.Point(0,0), // origin
     anchor: new google.maps.Point(22,22), // anchor
     rotation: 5
    }; 
    if (marker && marker.setMap) {
        marker.setMap(null);
        }

     marker = new google.maps.Marker({
        position: {lat: parseFloat(agentlat),lng:  parseFloat(agentlong)},
        label: null,
        icon: image,
        map: map,
        
     });
     }

     function makeMarker( position,icon,map) {
        new google.maps.Marker({
        position: position,
        map: map,
        icon: icon,
        });
     }


}
    
      
