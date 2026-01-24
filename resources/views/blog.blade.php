<x-header :meta="array('title'=> getSetting('page_blog_meta_title'), 'description'=> getSetting('page_blog_meta_description'))" />
<main>
    <!-- banner section start-->
    <section class="ko-banner ko-singlepost-banner"
        style="background-image: url('{{ publicPath('images/cart-banner.webp') }}');">
        <div class="ko-container">
            <div class="ko-banner-content">
                <h2>{{ $blog->title }}</h2>
                <nav>
                    <ol class="ko-banner-list">
                        <li><a href="{{ route('view.home') }}">Home</a></li>
                        <li><a href="{{ route('view.blog') }}">Blogs</a></li>
                        <li class="active">{{ $blog->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!-- banner section end-->

    <!-- singlePost section start -->
    <section class="ko-singlePost-section">
        <div class="ko-container">
            <div class="ko-row">
                <div class="ko-col-8">
                    <div class="ko-singlepost-graphic">
                        <img src="{{ publicPath($blog->image) }}" alt="post-img" />
                    </div>
                    <div class="ko-singlepost-content">
                        <div class="ko-auth-details">
                            <ul class="ko-singlepost-list">

                                {{-- <li>almaris-admin</li> --}}
                                <li><span>•</span>{{ $blog->created_at->format('F d, Y') }}</li>
                                @foreach ($blog->categories as $category)
                                <li>
                                    <a href="">{{ $category->name }}</a>
                                    @if(!$loop->last), @endif
                                </li>
                            @endforeach


                            </ul>
                        </div>
                        <div class="ko-blog-ctn-wrap">
                            <p>{!! $blog->description !!}</p>
                        </div>
                    </div>
                </div>
                <div class="ko-col-4">
                    <aside class="ko-recentpost">
                        <div class="ko-recentpost-cards-wrap">
                            <h3>Recent Blogs</h3>
                            @foreach ($recentBlogs as $recentBlog)
                                <div class="ko-recentpost-card">
                                    <div class="ko-recentpost-graphic">
                                        <a href="{{ route('blog.list', $recentBlog->slug) }}">
                                            <img src="{{ publicPath($recentBlog->image) }}" alt="{{ $recentBlog->title }}">
                                        </a>
                                    </div>
                                    <div class="ko-recentpost-content">
                                        <a
                                            href="{{ route('blog.list', $recentBlog->slug) }}">{{ $recentBlog->title }}</a>
                                        <p>{{ $recentBlog->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </aside>
                </div>

            </div>
        </div>
    </section>
    <!-- singlePost section end -->

</main>

<x-footer />
