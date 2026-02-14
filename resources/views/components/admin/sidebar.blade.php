<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
            <span class="logo-lg">
                <img class="mt-3" src="{{ publicPath(getSetting('site_logo_light')) }}" alt="" height="65">
            </span>
        </a>
        <button type="button" class="p-0 btn btn-sm fs-3xl header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <!-- ITEMS -->
    <div id="scrollbar">
        <div class="container-fluid"><br>

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Admin Panel</span></li>

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}/" class="nav-link menu-link collapsed">
                        <i class="ri-home-line"></i><span>Dashboard</span>
                    </a>
                </li>

                @role('admin')
                <!-- MEDIA LIBRARY -->
                <li class="nav-item">
                    @php
                    $mediaActive = Route::is('media-library.index');
                    @endphp
                    <a href="{{ route('media-library.index') }}"
                        class="nav-link menu-link @if ($mediaActive) active @endif">
                        <i class="ri-image-line"></i><span>Media Library</span>
                    </a>
                </li>
                <!-- END MEDIA LIBRARY -->

                <!-- PRODUCT -->
                <li class="nav-item">
                    @php
                    $productActive = Route::is('products.create') || Route::is('products.index');
                    @endphp
                    <a class="nav-link menu-link @if ($productActive) ""@else collapsed @endif"
                        href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $productActive ? 'true' : 'false' }}" aria-controls="sidebarProduct">
                        <i class="ri-shopping-bag-3-line"></i>
                        <span>Product</span>
                    </a>
                    <div class="menu-dropdown collapse @if ($productActive) show @endif"
                        id="sidebarProduct">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('products.create') }}"
                                    class="nav-link @if (Route::is('products.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}"
                                    class="nav-link @if (Route::is('products.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- END PRODUCT -->

                <!-- BRANDS -->
                <li class="nav-item">
                    @php
                    $brandsActive = Route::is('brands.create') || Route::is('brands.index');
                    @endphp
                    <a class="nav-link menu-link @if ($brandsActive) ""@else collapsed @endif"
                        href="#sidebarBrands" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $brandsActive ? 'true' : 'false' }}" aria-controls="sidebarBrands">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Brands</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($brandsActive) show @endif"
                        id="sidebarBrands">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('brands.create') }}"
                                    class="nav-link @if (Route::is('brands.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('brands.index') }}"
                                    class="nav-link @if (Route::is('brands.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- END BRANDS -->

                <!-- Product Attribute -->
                {{-- <li class="nav-item">
                    @php
                        $productAttributeActive =
                            Route::is('product_attributes.create') || Route::is('product_attributes.index');
                    @endphp
                    <a class="nav-link menu-link @if ($productAttributeActive) ""@else collapsed @endif"
                        href="#sidebarProductAttribute" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $productAttributeActive ? 'true' : 'false' }}"
                aria-controls="sidebarProductAttribute">
                <i class="ri-list-settings-line"></i>
                <span>Product Attribute</span>
                </a>
                <div class="collapse menu-dropdown @if ($productAttributeActive) show @endif"
                    id="sidebarProductAttribute">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('product_attributes.create') }}"
                                class="nav-link @if (Route::is('product_attributes.create')) active @endif">Create</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('product_attributes.index') }}"
                                class="nav-link @if (Route::is('product_attributes.index')) active @endif">Show</a>
                        </li>
                    </ul>
                </div>
                </li> --}}

                <!-- Category -->
                <li class="nav-item">
                    @php
                    $categoryActive = Route::is('categories.create') || Route::is('categories.index');
                    @endphp
                    <a class="nav-link menu-link @if ($categoryActive) ""@else collapsed @endif"
                        href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $categoryActive ? 'true' : 'false' }}" aria-controls="sidebarCategory">
                        <i class="ri-folder-2-line"></i>
                        <span>Category</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($categoryActive) show @endif"
                        id="sidebarCategory">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('categories.create') }}"
                                    class="nav-link @if (Route::is('categories.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}"
                                    class="nav-link @if (Route::is('categories.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- tags -->

                <li class="nav-item">
                    <a href="#sidebarproduct_tags" class="nav-link menu-link" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarproduct_tags">
                        <i class="ri-hashtag"></i>
                        <span>Tags</span>
                    </a>

                    <div class="collapse menu-dropdown" id="sidebarproduct_tags">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('tags.create') }}" class="nav-link">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tags.index') }}" class="nav-link">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Colors -->
                <li class="nav-item">
                    <a href="#sidebarproduct_color" class="nav-link menu-link" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarproduct_color">
                        <i class="bi bi-palette"></i>
                        <span>Colors</span>
                    </a>

                    <div class="collapse menu-dropdown" id="sidebarproduct_color">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('colors.create') }}" class="nav-link">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('colors.index') }}" class="nav-link">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="#sidebarproduct_stocks" class="nav-link menu-link" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarproduct_stocks">
                        <i class="ri-stack-line"></i>
                        <span>Stocks</span>
                    </a>

                    <div class="collapse menu-dropdown" id="sidebarproduct_stocks">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('stocks.create') }}" class="nav-link">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stocks.index') }}" class="nav-link">List</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    @php
                    $orderActive = Route::is('orders.show');
                    @endphp
                    <a class="nav-link menu-link @if ($orderActive) ""@else collapsed @endif"
                        href="#sidebarorder" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $orderActive ? 'true' : 'false' }}" aria-controls="sidebarorder">
                        <i class="ri-file-list-3-line"></i>
                        <span>Order</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($orderActive) show @endif"
                        id="sidebarorder">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('orders.show') }}"
                                    class="nav-link @if (Route::is('orders.show')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>


                {{--
                <li class="nav-item">
                    @php
                        $activityActive = Route::is('admin.activity.index');
                    @endphp
                    <a class="nav-link menu-link @if ($activityActive) @else collapsed @endif"
                        href="#sidebarActivity" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $activityActive ? 'true' : 'false' }}" aria-controls="sidebarActivity">
                <i class="ri-pulse-line"></i> <span>Activity Monitor</span>
                </a>
                <div class="collapse menu-dropdown @if ($activityActive) show @endif" id="sidebarActivity">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.activity.index') }}"
                                class="nav-link @if (Route::is('admin.activity.index')) active @endif">
                                Live Tracking
                            </a>
                        </li>
                    </ul>
                </div>
                </li> --}}

                <!-- Store visit Management -->
                <li class="nav-item">
                    <a href="#sidebarstore_visit" class="nav-link menu-link" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarstore_visit">
                        <i class="bi bi-shop"></i>
                        <span>Store Visits</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarstore_visit">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('store_visits.index') }}" class="nav-link">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                                <!-- SETTINGS -->
                <li class="nav-item">
                    @if (Route::is('view.settings.*') || Route::is('faqs.*'))
                    <a href="#sidebarSettings" class="nav-link menu-link" data-bs-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="sidebarSettings">
                        <i class="ri-home-gear-line"></i><span>Settings</span>
                    </a>
                    <div class="menu-dropdown" id="sidebarSettings">
                        @else
                        <a href="#sidebarSettings" class="nav-link menu-link collapsed" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="sidebarSettings">
                            <i class="ri-home-gear-line"></i><span>Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSettings">
                            @endif
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('view.settings.general') }}/" class="nav-link">General</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('view.settings.home') }}/" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('view.settings.pages') }}/" class="nav-link">Pages</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('view.settings.ecommerce') }}/" class="nav-link">E-Commerce</a>
                                </li>
                            </ul>
                        </div>
                </li>
                
                <!-- Users -->
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link menu-link collapsed">
                        <i class="ph-user-circle"></i><span>Users</span>
                    </a>
                </li>
                @endrole

                @role('salesman')
                <li class="nav-item">
                    @php
                    $visitActive = Route::is('salesman.visit.create') || Route::is('salesman.visit.store');
                    @endphp
                    <a href="#sidebarVisit"
                        class="nav-link menu-link @if ($visitActive) "" @else collapsed @endif"
                        data-bs-toggle="collapse" role="button" aria-expanded="{{ $visitActive ? 'true' : 'false' }}"
                        aria-controls="sidebarVisit">
                        <i class="ri-briefcase-line"></i><span>Salesman Visits</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($visitActive) show @endif" id="sidebarVisit">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('salesman.visit.create') }}"
                                    class="nav-link @if (Route::is('salesman.visit.create')) active @endif">Create Visit</a>
                            </li>
                        </ul>
                    </div>

                </li>
                @endrole
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>

<div class="vertical-overlay"></div>