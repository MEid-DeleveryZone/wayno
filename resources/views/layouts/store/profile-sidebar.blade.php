@switch($client_preference_detail->business_type)
    @case('taxi')
        <?php $ordertitle = 'Rides'; ?>
        <?php $hidereturn = 1; ?>
        @break
    @default
    <?php $ordertitle = 'Orders';  ?>
@endswitch
<div class="dashboard-left">
    <div class="collection-mobile-back">
        <span class="filter-back">
            <i class="fa fa-angle-left" aria-hidden="true"></i> back
        </span>
    </div>
    <div class="block-content">
        <ul>
            <li class="{{ (request()->is('user/profile')) ? 'active' : '' }}"><a href="{{route('user.profile')}}">{{ __('Account Info') }}</a></li>
            <li class="{{ (request()->is('user/addressBook')) ? 'active' : '' }}"><a href="{{route('user.addressBook')}}">{{ __('Address Book') }}</a></li>
            <li class="{{ (request()->is('user/orders*')) ? 'active' : '' }}"><a href="{{route('user.orders')}}">{{ __('My '.$ordertitle) }}</a></li>
            <li class="{{ (request()->is('user/wishlists')) ? 'active' : '' }}"><a href="{{route('user.wishlists')}}">{{ __('My Wishlist') }}</a></li>
            <li class="{{ (request()->is('user/loyalty')) ? 'active' : '' }}"><a href="{{route('user.loyalty')}}">{{ __('My Loyalty') }}</a></li>
            <li class="{{ (request()->is('user/wallet')) ? 'active' : '' }}"><a href="{{route('user.wallet')}}">{{ __('My Wallet') }}</a></li>
            @if( (isset($client_preference_detail->subscription_mode)) && ($client_preference_detail->subscription_mode == 1) )
                <li class="{{ (request()->is('user/subscription*')) ? 'active' : '' }}"><a href="{{route('user.subscription.plans')}}">{{ __('My Subscriptions') }}</a></li>
            @endif
            <li class="{{ (request()->is('user/changePassword')) ? 'active' : '' }}"><a href="{{route('user.changePassword')}}">{{ __('Change Password') }}</a></li>
            <li class="last {{ (request()->is('user/logout')) ? 'active' : '' }}"><a href="{{route('user.logout')}}">{{ __('Log Out') }}</a></li>
        </ul>
    </div>
</div>