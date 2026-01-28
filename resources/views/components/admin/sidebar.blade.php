    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{ route('view.admin.dashboard') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img class="mt-3" src="{{ publicPath(getSetting("site_logo_light")) }}" alt="" height="80">
                </span>
            </a>
            <button type="button" class="p-0 btn btn-sm fs-3xl header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <!-- ITEMS -->
        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title"><span>Admin Panel</span></li>

                    <!-- DASHBOARD -->
                    <li class="nav-item">
                        <a href="{{ route('view.admin.dashboard') }}/" class="nav-link menu-link collapsed">
                            <i class="ri-home-line"></i><span>Dashboard</span>
                        </a>
                    </li>



                    <!-- PRODUCT -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarProduct"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProduct">
                            <i class="ri-shopping-bag-3-line"></i>
                            <span>Product</span>
                        </a>

                        <div class="collapse menu-dropdown" id="sidebarProduct">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('products.create') }}" class="nav-link">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('products.index') }}" class="nav-link">Show</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link">Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('brands.index') }}" class="nav-link">Brands</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- END PRODUCT -->

                    <!-- Product Attribute -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarProductAttribute"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProductAttribute">
                            <i class="ri-list-settings-line"></i>
                            <span>Product Attribute</span>
                        </a>

                        <div class="collapse menu-dropdown" id="sidebarProductAttribute">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('product_attributes.create') }}" class="nav-link">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('product_attributes.index') }}" class="nav-link">Show</a>
                                </li>
                            </ul>
                        </div>
                    </li>



                    <!-- Category -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCategory"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarCategory">
                            <i class="ri-folder-2-line"></i>
                            <span>Category</span>
                        </a>

                        <div class="collapse menu-dropdown" id="sidebarCategory">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('categories.create') }}" class="nav-link">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link">Show</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- tags -->
                    <li class="nav-item">
                        <a href="#sidebarproduct_tags"
                            class="nav-link menu-link"
                            data-bs-toggle="collapse"
                            role="button"
                            aria-expanded="false"
                            aria-controls="sidebarproduct_tags">
                            <i class="ri-price-tag-3-line"></i>
                            <span>Tags</span>
                        </a>

                        <div class="collapse menu-dropdown" id="sidebarproduct_tags">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('tags.create') }}" class="nav-link">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tags.index') }}" class="nav-link">Show</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarorder"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarorder">
                            <i class="ri-folder-2-line"></i>
                            <span>Order</span>
                        </a>

                        <div class="collapse menu-dropdown" id="sidebarorder">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('orders.show') }}" class="nav-link">Show</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                <!-- SETTINGS -->
                <li class="nav-item">
                    @if(Route::is('view.settings.*') || Route::is('faqs.*') )
                    <a href="#sidebarSettings" class="nav-link menu-link" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarSettings">
                        <i class="ri-home-gear-line"></i><span>Settings</span>
                    </a>
                    <div class="menu-dropdown" id="sidebarSettings">
                        @else
                        <a href="#sidebarSettings" class="nav-link menu-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                            <i class="ri-home-gear-line"></i><span>Settings</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSettings">
                            @endif
                            <ul class="nav nav-sm flex-column">
                                {{-- <li class="nav-item">
                                    <a href="{{ route('view.settings.about') }}/" class="nav-link">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('view.settings.env') }}/" class="nav-link">ENV</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('faqs.index') }}/" class="nav-link">FAQs</a>
                                </li> --}}
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

        </ul>
    </div>
    </div>
    <div class="sidebar-background"></div>
    </div>

    <div class="vertical-overlay"></div>