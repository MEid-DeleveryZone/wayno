<h4 class="mt-4 mb-2"><b>What time would you like us to start?</b></h4>
<div class="booking-time radio-btns long-radio">
    @foreach ($time_slots as $key => $date)
    @if($key+1 < count($time_slots))
    <div>
        <div class="radios">
            <input type="radio" value='{{$date}}' name='booking_time{{$cart_product_id}}' id='time{{$cart_product_id}}{{$key+1}}'/>
            <label for='time{{$cart_product_id}}{{$key+1}}'><span class="customCheckbox selected-time" aria-hidden="true"  data-value='{{$date}}' data-cart_product_id='{{$cart_product_id}}'>{{$date}} - {{@$time_slots[$key+1]}}</span></label>
        </div>
    </div>
    @endif
    @endforeach
</div>
<P id="message_of_time{{$cart_product_id}}"></P>
