<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ trans('menu.' . $metaTitle) }} JutaGroup.ge</title>
    <!-- Stylesheets -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/revolution-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!--Favicon-->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.js') }}"></script>
    @stack('scripts')
    @livewireStyles
</head>

<body>
    <div class="page-wrapper">

        <!-- Preloader -->
        <div class="preloader">
            <div class="loader">
                <div class="cssload-container">
                    <div class="cssload-speeding-wheel"></div>
                </div>
            </div>
        </div>

        <!-- Main Header-->
        <header class="main-header header-style-one">
            <!-- Header Top -->
            <div class="header-top">
                <div class="auto-container clearfix">
                </div>
            </div>
            <!-- Header Top End -->

            <!--Header-Upper-->
            <div class="header-upper">
                <div class="auto-container">
                    <div class="clearfix">
                        <div class="pull-left logo-outer">
                            <div class="logo"><a href="{{ route('clientIndex') }}"><img
                                        src="{{ asset('images/logo.png') }}" alt="Brighton" title="Brighton"></a></div>
                        </div>

                        <div class="pull-right upper-right clearfix">

                            <!--Info Box-->
                            <div class="upper-column info-box">
                                <div class="icon-box"><span class="flaticon-location-pin"></span></div>
                                <ul>
                                    <li><strong>@lang('menu.workday')</strong></li>
                                    <li>@lang('menu.sundayNotWorking')</li>
                                </ul>
                            </div>

                            <!--Info Box-->
                            <div class="upper-column info-box">
                                <div class="icon-box"><span class="flaticon-technology"></span></div>
                                <ul>
                                    <li><strong>599 - 555 - 246</strong></li>
                                    <li>geo@jutagroup.ge</li>
                                </ul>
                            </div>
                            <div class="upper-column info-box">
                                <div class="icon-box">
                                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <a style="@if ($localeCode == LaravelLocalization::getCurrentLocale()) display:none; @endif color:black;"
                                            rel="alternate" hreflang="{{ $localeCode }}"
                                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            {{ $properties['native'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--Header-Lower-->
            <div class="header-lower">

                <div class="auto-container">
                    <div class="nav-outer clearfix">
                        <!-- Main Menu -->
                        <nav class="main-menu">
                            <div class="navbar-header">
                                <!-- Toggle Button -->
                                <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target=".navbar-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse clearfix">
                                <ul class="navigation clearfix">
                                    <li class="current"><a href="{{ route('clientIndex') }}">@lang('menu.index')</a>
                                    </li>
                                    <li><a href="#">@lang('menu.blog')</a>
                                        {{-- <ul>
                                                <li><a href="blog.html">Blog</a></li>
                                                <li><a href="blog-grid.html">Blog Grid</a></li>
                                                <li><a href="blog-details.html">Blog Details</a></li>
                                            </ul> --}}
                                    </li>

                                    <li><a href="about-us.html">@lang('menu.about')</a></li>
                                    <li><a href="{{ route('clientContact') }}">@lang('menu.contact')</a></li>
                                </ul>
                            </div>
                        </nav>
                        <!-- Main Menu End-->
                        <div class="btn-outer"><a href="contact.html" class="theme-btn quote-btn"><span
                                    class="fa fa-mail-reply-all"></span> Get a Quote</a></div>
                    </div>

                </div>
            </div>

            <!--Sticky Header-->
            <div class="sticky-header">
                <div class="auto-container clearfix">
                    <!--Logo-->
                    <div class="logo pull-left">
                        <a href="{{ route('clientIndex') }}" class="img-responsive"><img
                                src="{{ asset('images/logo-small.png') }}" alt="Brighton" title="Brighton"></a>
                    </div>

                    <!--Right Col-->
                    <div class="right-col pull-right">
                        <!-- Main Menu -->
                        <nav class="main-menu">
                            <div class="navbar-header">
                                <!-- Toggle Button -->
                                <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target=".navbar-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse clearfix">
                                <ul class="navigation clearfix">
                                    <li><a href="{{ route('clientIndex') }}">Home</a>
                                    </li>
                                    <li><a href="#">Blog</a>
                                    </li>

                                    <li><a href="about-us.html">About</a></li>
                                    <li><a href="{{ route('clientContact') }}">Contact</a></li>
                                </ul>
                            </div>
                        </nav><!-- Main Menu End-->
                    </div>

                </div>
            </div>
            <!--End Sticky Header-->

        </header>
        <!--End Main Header -->
        <!-- Content -->
        @yield('content')
        <!-- EndContent -->
        <!--Main Footer-->
        <footer class="main-footer">

            <!--Footer Upper-->
            <div class="footer-upper">
                <div class="auto-container">
                    <div class="row clearfix">

                        <!--Two 4th column-->
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="row clearfix">
                                <div class="col-lg-7 col-sm-6 col-xs-12 column">
                                    <div class="footer-widget logo-widget">
                                        <div class="logo"><a href="{{ route('clientIndex') }}"><img
                                                    src="{{ asset('images/logo-2.png') }}" class="img-responsive"
                                                    alt=""></a></div>
                                        <div class="text">The year is and launches the last of that americas deep
                                            space probes and we will our our way make all come true.</div>

                                        <ul class="contact-info">
                                            <li><span class="icon flaticon-pin"></span> 3A07, Serif St, Orleans,
                                                USA-170A</li>
                                            <li><span class="icon flaticon-technology"></span> +1 - 000 - 8990 - 1560
                                            </li>
                                            <li><span class="icon flaticon-mail-2"></span> support@domain.com</li>
                                        </ul>

                                    </div>
                                </div>

                                <!--Footer Column-->
                                <div class="col-lg-5 col-sm-6 col-xs-12 column">
                                    <div class="sec-title-three">
                                        <h2>Usefull Links</h2>
                                    </div>
                                    <div class="footer-widget links-widget">
                                        <ul>
                                            <li><a href="#">Agriculture Processing</a></li>
                                            <li><a href="#">Chemical Research</a></li>
                                            <li><a href="#">Metal Engineering</a></li>
                                            <li><a href="#">Mechanical Engineering</a></li>
                                            <li><a href="#">Petroleum & Gas</a></li>
                                            <li><a href="#">Power & Energy</a></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Two 4th column End-->

                        <!--Two 4th column-->
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="row clearfix">
                                <!--Footer Column-->
                                <div class="col-lg-6 col-sm-6 col-xs-12 column">
                                    <div class="footer-widget twitter-widget">
                                        <div class="sec-title-three">
                                            <h2>Twitter Feed</h2>
                                        </div>
                                        <div class="widget-content">
                                            <div class="feed">
                                                <div class="text"><span class="icon fa fa-twitter"></span> @ <h4
                                                        class="author-title">Roffell</h4>this year is and launches the
                                                    last of that deep : paragraph.co.in</div>
                                                <span class="month">about a month</span>
                                            </div>
                                            <div class="feed">
                                                <div class="text"><span class="icon fa fa-twitter"></span> @ <h4
                                                        class="author-title">Markel</h4>this year is and launches the
                                                    last of that deep time to light the lights poster : abstract.co.in
                                                </div>
                                                <span class="month">about 5 min ago</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Footer Column-->
                                <div class="col-md-6 col-sm-6 col-xs-12 column">
                                    <div class="footer-widget gallery-widget">
                                        <div class="sec-title-three">
                                            <h2>Flickr Photos</h2>
                                        </div>
                                        <div class="clearfix">
                                            <figure class="image"><a href="images/resource/footer-gallery-1.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-1.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                            <figure class="image"><a href="images/resource/footer-gallery-2.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-2.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                            <figure class="image"><a href="images/resource/footer-gallery-3.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-3.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                            <figure class="image"><a href="images/resource/footer-gallery-4.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-4.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                            <figure class="image"><a href="images/resource/footer-gallery-5.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-5.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                            <figure class="image"><a href="images/resource/footer-gallery-6.jpg"
                                                    class="lightbox-image" title="Caption Here"><img
                                                        src="{{ asset('images/resource/footer-gallery-6.jpg') }}"
                                                        alt=""></a>
                                            </figure>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--Two 4th column End-->

                    </div>

                </div>
            </div>

            <!--Footer Bottom-->
            <div class="footer-bottom">
                <div class="auto-container">
                    <div class="row clearfix">
                        <!--Copyright-->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="copyright">Copyrights &copy; 2023 JUTA. All Rights Reserved.</div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <ul class="footer-bottom-social">
                                <li><a href="#"><span class="fa fa-facebook-f"></span></a></li>
                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                <li><a href="#"><span class="fa fa-google-plus"></span></a></li>
                                <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                                <li><a href="#"><span class="fa fa-flickr"></span></a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </footer>

    </div>
    <!--End pagewrapper-->

    <!--Scroll to top-->
    <div class="scroll-to-top scroll-to-target" data-target=".main-header"><span
            class="icon fa fa-long-arrow-up"></span></div>


    {{-- Scripts --}}
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/revolution.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.pack.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox-media.js') }}"></script>
    <script src="{{ asset('js/owl.js') }}"></script>
    <script src="{{ asset('js/wow.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    @livewireScripts
</body>

</html>
