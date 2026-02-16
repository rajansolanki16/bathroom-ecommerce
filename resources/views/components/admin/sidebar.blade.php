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
                    @php $dashboardActive = Route::is('admin.dashboard'); @endphp
                    <a href="{{ route('admin.dashboard') }}/"
                        class="nav-link menu-link @if ($dashboardActive) active @endif">
                        <i class="ri-home-line"></i><span>Dashboard</span>
                    </a>
                </li>

                @role('admin')
                <!-- CORE -->
                <li class="menu-title"><span>Core</span></li>

                <!-- MEDIA LIBRARY -->
                <li class="nav-item">
                    @php $mediaActive = Route::is('media-library.index'); @endphp
                    <a href="{{ route('media-library.index') }}"
                        class="nav-link menu-link @if ($mediaActive) active @endif">
                        <i class="ri-image-line"></i><span>Media Library</span>
                    </a>
                </li>
                <!-- CATALOG -->
                <li class="menu-title"><span>Catalog</span></li>

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
                    <div class="menu-dropdown collapse @if ($productActive) show @endif" id="sidebarProduct">
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
                    <div class="collapse menu-dropdown @if ($brandsActive) show @endif" id="sidebarBrands">
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

                <!-- CATEGORY -->
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
                    <div class="collapse menu-dropdown @if ($categoryActive) show @endif" id="sidebarCategory">
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

                <!-- TAGS -->
                <li class="nav-item">
                    @php
                        $tagsActive = Route::is('tags.create') || Route::is('tags.index');
                    @endphp
                    <a href="#sidebarproduct_tags"
                        class="nav-link menu-link @if ($tagsActive) active @endif"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $tagsActive ? 'true' : 'false' }}"
                        aria-controls="sidebarproduct_tags">
                        <i class="ri-hashtag"></i>
                        <span>Tags</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($tagsActive) show @endif" id="sidebarproduct_tags">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('tags.create') }}"
                                    class="nav-link @if (Route::is('tags.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tags.index') }}"
                                    class="nav-link @if (Route::is('tags.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- COLORS -->
                <li class="nav-item">
                    @php
                        $colorsActive = Route::is('colors.create') || Route::is('colors.index');
                    @endphp
                    <a href="#sidebarproduct_color"
                        class="nav-link menu-link @if ($colorsActive) active @endif"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $colorsActive ? 'true' : 'false' }}"
                        aria-controls="sidebarproduct_color">
                        <i class="bi bi-palette"></i>
                        <span>Colors</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($colorsActive) show @endif" id="sidebarproduct_color">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('colors.create') }}"
                                    class="nav-link @if (Route::is('colors.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('colors.index') }}"
                                    class="nav-link @if (Route::is('colors.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- STOCKS -->
                <li class="nav-item">
                    @php
                        $stocksActive = Route::is('stocks.create') || Route::is('stocks.index');
                    @endphp
                    <a href="#sidebarproduct_stocks"
                        class="nav-link menu-link @if ($stocksActive) active @endif"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $stocksActive ? 'true' : 'false' }}"
                        aria-controls="sidebarproduct_stocks">
                        <i class="ri-stack-line"></i>
                        <span>Stocks</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($stocksActive) show @endif" id="sidebarproduct_stocks">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('stocks.create') }}"
                                    class="nav-link @if (Route::is('stocks.create')) active @endif">Create</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stocks.index') }}"
                                    class="nav-link @if (Route::is('stocks.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- SALES -->
                <li class="menu-title"><span>Sales</span></li>

                <!-- ORDERS -->
                <li class="nav-item">
                    @php $orderActive = Route::is('orders.show'); @endphp
                    <a class="nav-link menu-link @if ($orderActive) ""@else collapsed @endif"
                        href="#sidebarorder" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $orderActive ? 'true' : 'false' }}"
                        aria-controls="sidebarorder">
                        <i class="ri-file-list-3-line"></i>
                        <span>Orders</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($orderActive) show @endif" id="sidebarorder">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('orders.show') }}"
                                    class="nav-link @if (Route::is('orders.show')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- STORE VISITS -->
                <li class="nav-item">
                    @php $storeVisitActive = Route::is('store_visits.index'); @endphp
                    <a href="#sidebarstore_visit"
                        class="nav-link menu-link @if ($storeVisitActive) active @endif"
                        data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $storeVisitActive ? 'true' : 'false' }}"
                        aria-controls="sidebarstore_visit">
                        <i class="bi bi-shop"></i>
                        <span>Store Visits</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($storeVisitActive) show @endif"
                        id="sidebarstore_visit">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('store_visits.index') }}"
                                    class="nav-link @if (Route::is('store_visits.index')) active @endif">List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- CONFIGURATION -->
                <li class="menu-title"><span>Configuration</span></li>

                <!-- SETTINGS -->
                <li class="nav-item">
                    @php
                        $settingsActive =
                            Route::is('view.settings.general') ||
                            Route::is('view.settings.home') ||
                            Route::is('view.settings.pages') ||
                            Route::is('view.settings.ecommerce') ||
                            Route::is('faqs.*');
                    @endphp

                    <a class="nav-link menu-link @if ($settingsActive) "" @else collapsed @endif"
                        href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $settingsActive ? 'true' : 'false' }}"
                        aria-controls="sidebarSettings">
                        <i class="ri-home-gear-line"></i>
                        <span>Settings</span>
                    </a>
                    <div class="collapse menu-dropdown @if ($settingsActive) show @endif"
                        id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('view.settings.general') }}"
                                    class="nav-link @if (Route::is('view.settings.general')) active @endif">
                                    General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.settings.home') }}"
                                    class="nav-link @if (Route::is('view.settings.home')) active @endif">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.settings.pages') }}"
                                    class="nav-link @if (Route::is('view.settings.pages')) active @endif">
                                    Pages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('view.settings.ecommerce') }}"
                                    class="nav-link @if (Route::is('view.settings.ecommerce')) active @endif">
                                    E-Commerce
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- ADMINISTRATION -->
                <li class="menu-title"><span>Administration</span></li>

                <!-- USERS -->
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link menu-link collapsed">
                        <i class="ph-user-circle"></i><span>Users</span>
                    </a>
                </li>
                @endrole

                @role('salesman')
                <li class="menu-title"><span>Salesman</span></li>
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