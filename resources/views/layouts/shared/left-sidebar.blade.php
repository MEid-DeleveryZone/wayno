<div class="left-side-menu">
    <div class="logo-box m-hide d-lg-block" style="height:0px;">
        @php
            $urlImg = URL::to('/') . '/assets/images/users/user-1.jpg';
            $clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
            if ($clientData) {
                $urlImg = $clientData->logo['image_fit'] . '200/80' . $clientData->logo['image_path'];
            }
            $marketing_permissions = ['banner', 'promocode', 'loyalty_cards'];
            $subscription_permissions = ['subscription_plans_customers', 'subscription_plans_vendors'];
            $extra_permissions = ['celebrity', 'inquiries'];
            $setting_permissions = [
                'profile',
                'customize',
                'app_styling',
                'web_styling',
                'catalog',
                'configurations',
                'tax',
                'payment',
            ];
            $styling_permissions = ['app_styling', 'web_styling'];
            $order_permissions = [
                'dashboard',
                'orders',
                'vendors',
                'accounting_orders',
                'accounting_loyality',
                'accounting_promo_codes',
                'accounting_taxes',
                'accounting_vendors',
                'subscriptions_customers',
                'subscriptions_vendors',
                'customers',
            ];
            $accounting_permissions = [
                'accounting_orders',
                'accounting_loyality',
                'accounting_promo_codes',
                'accounting_taxes',
                'accounting_vendors',
            ];
        @endphp
        <a href="{{ route('client.dashboard') }}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>

        <a href="{{ route('client.dashboard') }}" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="{{ $urlImg }}" alt="" height="30" style="padding-top: 4px;">
            </span>
            <span class="logo-lg">
                <img src="{{ $urlImg }}" alt="" height="50" style="padding-top: 4px;">
            </span>
        </a>
    </div>
    <div class="h-100" data-simplebar>
        <div class="user-box text-center">
            <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="user-img" title="Mat Helme"
                class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-toggle="dropdown">User</a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>
        <div id="sidebar-menu">
            <?php
            $allowed = [];
            if (Auth::user()->is_superadmin == 0) {
                foreach (Auth::user()->getAllPermissions as $value) {
                    array_push($allowed, $value->permission->slug);
                }
            } else {
                array_push($allowed, '99999');
            }
            ?>
            <ul id="side-menu">
                @php
                    $client_preference = \App\Models\ClientPreference::where(['id' => 1])->first();
                @endphp
                @if (count(array_intersect($order_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                    <li>
                        <a class="menu-title pl-1" href="#">
                            <!-- <span class="icon-orders"></span> -->
                            <span>{{ __('ORDERS') }}</span>
                        </a>
                        <ul class="nav-second-level">
                            @if (in_array('dashboard', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('client.dashboard') }}">
                                        <span class="icon-dash"></span>
                                        <span>{{ __('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('orders', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('order.index') }}">
                                        <span class="icon-orders"></span>
                                        <span> {{ __('Orders') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('vendors', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('vendor.index') }}">
                                        <span class="icon-vendor"></span>
                                        @php
                                            $vendormenu = getNomenclatureName('Vendors', true);
                                            $vendormenulabel = $vendormenu == 'Vendors' ? __('Vendors') : $vendormenu;

                                        @endphp
                                        {{-- <span>{{getNomenclatureName('Vendors', true)}}</span> --}}
                                        <span>{{ $vendormenulabel }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (count(array_intersect($accounting_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="#sidebaraccounting" data-toggle="collapse">
                                        <span class="icon-accounting"></span>
                                        <span> {{ __('Accounting') }} </span>
                                    </a>
                                    <div class="collapse" id="sidebaraccounting">
                                        <ul class="nav-second-level">
                                            @if (in_array('accounting_orders', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{ route('account.orders') }}">{{ __('Orders') }}</a>
                                                </li>
                                            @endif
                                            @if (in_array('accounting_loyality', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>

                                                    @php
                                                        $loyaltyCards = getNomenclatureName('Loyalty Cards', true);
                                                        $loyaltyCardsLabel =
                                                            $loyaltyCards == 'Loyalty Cards'
                                                                ? __('Loyalty Cards')
                                                                : $loyaltyCards;
                                                    @endphp
                                                    <a
                                                        href="{{ route('account.loyalty') }}">{{ $loyaltyCardsLabel }}</a>
                                                </li>
                                            @endif
                                            @if (in_array('accounting_promo_codes', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a
                                                        href="{{ route('account.promo.code') }}">{{ __('Promo Codes') }}</a>
                                                </li>
                                            @endif
                                            @if (in_array('accounting_taxes', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a href="{{ route('account.tax') }}">{{ __('Taxes') }}</a>
                                                </li>
                                            @endif
                                            @if (in_array('accounting_vendors', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    @php
                                                        $Vendors = getNomenclatureName('Vendors', true);
                                                        $VendorsTrans =
                                                            $Vendors == 'Vendors' ? __('Vendors') : $Vendors;
                                                    @endphp
                                                    <a href="{{ route('account.vendor') }}">{{ $VendorsTrans }}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if (Auth::user()->is_superadmin == 1)
                                {{-- @if (count(array_intersect($subscription_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                                @if ($client_preference->subscription_mode == 1)
                                    <li>
                                        <a href="#sidebarsubscriptions" data-toggle="collapse">
                                            <span class="icon-subscribe"></span>
                                            <span> {{ __('Subscriptions') }}</span>
                                        </a>
                                        <div class="collapse" id="sidebarsubscriptions">
                                            <ul class="nav-second-level">
                                                @if (in_array('subscription_plans_customers', $allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a
                                                            href="{{ route('subscription.plans.user') }}">{{ __('Customers') }}</a>
                                                    </li>
                                                @endif
                                                @if (in_array('subscription_plans_vendors', $allowed) || Auth::user()->is_superadmin == 1)
                                                    <li>
                                                        <a
                                                            href="{{ route('subscription.plans.vendor') }}">{{ $VendorsTrans }}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            {{-- @if (in_array('customers', $allowed) || Auth::user()->is_superadmin == 1) --}}
                            @if (Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('customer.index') }}">
                                        <span class="icon-customer-2"></span>
                                        <span> {{ __('Customers') }} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->is_superadmin == 1)
                    {{-- @if (count(array_intersect($setting_permissions, $allowed)) || Auth::user()->is_superadmin == 1) --}}
                    <li>
                        <a class="menu-title pl-1" href="#">
                            <!-- <span class="icon-settings-1-1"></span> -->
                            <span>{{ __('SETTINGS') }}</span>
                        </a>
                        <ul class="nav-second-level">
                            @if (in_array('profile', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('client.profile') }}">
                                        <span class="icon-profile"></span>
                                        <span> {{ __('Profile') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->email == env('DEV_EMAIL'))
                                <li>
                                    <a href="{{ route('configure.customize') }}">
                                        <span class="icon-customzie"></span>
                                        <span> {{ __('Customize') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->email == env('DEV_EMAIL'))
                                <li>
                                    <a href="#sidebarstyling" data-toggle="collapse">
                                        <span class="icon-styling"></span>
                                        <span> {{ __('Styling') }} </span>
                                    </a>
                                    <div class="collapse" id="sidebarstyling">
                                        <ul class="nav-second-level">
                                            @if (in_array('app_styling', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a
                                                        href="{{ route('appStyling.index') }}">{{ __('App Styling') }}</a>
                                                </li>
                                            @endif
                                            @if (in_array('web_styling', $allowed) || Auth::user()->is_superadmin == 1)
                                                <li>
                                                    <a
                                                        href="{{ route('webStyling.index') }}">{{ __('Web Styling') }}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <a href="#sidebarcms" data-toggle="collapse">
                                    <span class="icon-cms"></span>
                                    <span>{{ __('CMS') }}</span>
                                </a>
                                <div class="collapse" id="sidebarcms">
                                    <ul class="nav-second-level">
                                        @if (in_array('cms_pages', $allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{ route('cms.pages') }}">{{ __('Pages') }}</a>
                                            </li>
                                        @endif
                                        @if (in_array('cms_emails', $allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a href="{{ route('cms.emails') }}">{{ __('Emails') }}</a>
                                            </li>
                                        @endif
                                        @if (in_array('cms_notifications', $allowed) || Auth::user()->is_superadmin == 1)
                                            <li>
                                                <a
                                                    href="{{ route('cms.notifications') }}">{{ __('Notifications') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                            @if (in_array('catalog', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('category.index') }}">
                                        <span class="icon-catalogue"></span>
                                        <span> {{ __('Catalog') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->email == env('DEV_EMAIL'))
                                <li>
                                    <a href="{{ route('configure.index') }}">
                                        <span class="icon-configuration"></span>
                                        <span> {{ __('Configurations') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (in_array('tax', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('tax.index') }}">
                                        <span class="icon-tax"></span>
                                        <span> {{ __('Tax') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->email == env('DEV_EMAIL'))
                                <li>
                                    <a href="{{ route('payoption.index') }}">
                                        <span class="icon-payment-options"></span>
                                        <span> {{ __('Payment Options') }} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (count(array_intersect($marketing_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                    <li>
                        <a class="menu-title pl-1" href="#">
                            <!-- <span class="icon-marketing"></span> -->
                            <span>{{ __('MARKETING') }}</span>
                        </a>
                        <ul class="nav-second-level">

                            @if (Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="#sidebarbanner" data-toggle="collapse">
                                        <span class="icon-styling"></span>
                                        <span> {{ __('Banner') }} </span>
                                    </a>
                                    <div class="collapse" id="sidebarbanner">
                                        <ul class="nav-second-level">
                                            @if ($client_preference_detail->business_type != 'taxi')
                                                <li>
                                                    <a href="{{ route('banner.index') }}">{{ __('Banner') }}</a>
                                                </li>
                                            @endif
                                            <li>
                                                <a
                                                    href="{{ route('mobilebanner.index') }}">{{ __('Mobile Banner') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif



                            @if (in_array('promocode', $allowed) || Auth::user()->is_superadmin == 1)
                                <li>
                                    <a href="{{ route('promocode.index') }}">
                                        <span class="icon-discount-voucher"></span>
                                        <span> {{ __('Promocode') }} </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->email == env('DEV_EMAIL'))
                                <li>
                                    <a href="{{ route('loyalty.index') }}">
                                        <span class="icon-loyaltycard"></span>
                                        @php
                                            $LoyaltyCards = getNomenclatureName('Loyalty Cards', true);

                                        @endphp
                                        <span>
                                            {{ $LoyaltyCards === 'Loyalty Cards' ? __('Loyalty Cards') : $LoyaltyCards }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (count(array_intersect($extra_permissions, $allowed)) || Auth::user()->is_superadmin == 1)
                    @if ($client_preference->celebrity_check == 1 || $client_preference->enquire_mode == 1)
                        <li>
                            <a class="menu-title pl-1">
                                <!-- <span class="icon-extra"></span> -->
                                <span>{{ __('EXTRA') }}</span>
                            </a>
                            <ul class="nav-second-level">
                                {{-- @if (!empty($client_preference) && $client_preference->celebrity_check == 1) --}}
                                @if (Auth::user()->is_superadmin == 1)
                                    @if (in_array('celebrity', $allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('celebrity.index') }}">
                                                <span class="icon-celebrity"></span>
                                                <span> {{ __('Celebrities') }} </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                @if (!empty($client_preference) && $client_preference->enquire_mode == 1)
                                    @if (in_array('inquiries', $allowed) || Auth::user()->is_superadmin == 1)
                                        <li>
                                            <a href="{{ route('inquiry.index') }}">
                                                <span class="icon-question"></span>
                                                <span> {{ __('Inquiries') }} </span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</div>
