<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">
        <!--begin::Logo-->
        <a href="{{route('admin.dashboard') }}" class="brand-logo">
            <h3 class="text-white">

                {{ config('app.name', 'Puryaideu V2') }}</h3>
        </a>
        <!--end::Logo-->

        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->
    <img src="{{asset('assets/media/logo.png')}}" alt="logo" class="m-auto mt-3"
        style="height:50px !important;width:50px !important;margin-top:15px !important">
    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            {{-- menu-item menu-item-submenu menu-item-here menu-item-open --}}
            <ul class="menu-nav">
                <li class="menu-item {{ request()->is('admin/dashboard') ? 'menu-item-active' : '' }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.dashboard') }}" class="menu-link">
                        <i class="menu-icon fas fa-tachometer-alt"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/role') || request()->is('admin/permission') || request()->is('admin/role/*') || request()->is('admin/permission/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-user-tag"></i>
                        <span class="menu-text">Role & Permission</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <span class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            {{-- @can('role-view') --}}
                            <li class="menu-item {{ request()->is('admin/role') || request()->is('admin/role/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.role.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Role</span></a>
                            </li>
                            {{-- @endcan --}}

                            <li class="menu-item {{ request()->is('admin/permission') || request()->is('admin/permission/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.permission.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Permission</span></a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/vehicle') || request()->is('admin/vehicle/*') || request()->is('admin/vehicle_type/*') || request()->is('admin/vehicle_type')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-car"></i>
                        <span class="menu-text">Vehicle Management</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <span class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            {{-- @can('role-view') --}}
                            <li class="menu-item {{ request()->is('admin/role') || request()->is('admin/role/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.vehicle.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Vehicle list</span></a>
                            </li>
                            <li class="menu-item {{request()->is('admin/vehicle_type') || request()->is('admin/vehicle_type/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.vehicle_type.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Vehicle Type list</span></a>
                            </li>
                            {{-- @endcan --}}

                            {{-- <li class="menu-item {{ request()->is('admin/permission') || request()->is('admin/permission/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.permission.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Permission</span></a>
                            </li> --}}

                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/customer') || request()->is('admin/customer/*')||
                    request()->is('admin/rider') || request()->is('admin/rider/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-users"></i>
                        <span class="menu-text">User Management</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <span class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            {{-- @can('role-view') --}}
                            <li class="menu-item {{ request()->is('admin/customer') || request()->is('admin/customer/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.customer.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Customer list</span></a>
                            </li>
                            <li class="menu-item {{request()->is('admin/rider') || request()->is('admin/rider/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.rider.index')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Rider list</span></a>
                            </li>
                            <li class="menu-item {{request()->is('admin/rider_commission') || request()->is('admin/rider_commission/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.rider.commission')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Rider Commissions</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/booking') || request()->is('admin/booking/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{c
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.booking.index')}}" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-handshake"></i>
                        <span class="menu-text">Booking List</span><i class=""></i>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/sos') || request()->is('admin/sos/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.sos.index')}}" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-volume-up"></i>
                        <span class="menu-text">SOS</span><i class=""></i>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/transaction') || request()->is('admin/transaction/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.transaction.index')}}" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-money-check-alt"></i>
                        <span class="menu-text">Transaction</span><i class=""></i>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/heatmap') || request()->is('admin/heatmap/*')||
                    request()->is('admin/heatmap') || request()->is('admin/heatmap/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="" class="menu-link menu-toggle">
                        <i class="menu-icon fas fa-map-marked-alt"></i>
                        <span class="menu-text">Maps</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <span class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            {{-- @can('role-view') --}}
                            <li class="menu-item {{request()->is('admin/map/dispatcher') || request()->is('admin/map/dispatcher/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.map.dispatcher')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Dispatcher</span></a>
                            </li>
                            <li class="menu-item {{request()->is('admin/heatmap/') || request()->is('admin/heatmap/*')
                                ? " menu-item-active" : "" }}" aria-haspopup="true">
                                <a href="{{route('admin.map.heatmap')}}" class="menu-link"><i
                                        class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i><span class="menu-text">Heatmap</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu {{ request()->is('admin/promotion_voucher') || request()->is('admin/promotion_voucher/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.promotion_voucher.index')}}" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-correct"></i>
                        <span class="menu-text">Promotion Voucher</span><i class=""></i>
                    </a>
                </li>


                <li class="menu-item menu-item-submenu {{ request()->is('admin/setting') || request()->is('admin/setting/*')
                    ? " menu-item-active menu-item-open" : "" }}" {{-- {{
                    str_contains(Route::currentRouteName(), "admin.dashboard" ) ? "menu-item-active" : "" }}" --}}
                    aria-haspopup="true">
                    <a href="{{route('admin.setting.index')}}" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-settings"></i>
                        <span class="menu-text">Settings</span><i class=""></i>
                    </a>
                </li>


            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>