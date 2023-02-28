<div>
    <!--Page Title-->
    <section class="page-title" style="background-image:url({{ asset('images/background/featured-2-bg.jpg') }});">
        <div class="auto-container">
            <h1>Contact</h1>
        </div>

        <!--page-info-->
        <div class="page-info">
            <div class="auto-container">
                <div class="row clearfix">

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <ul class="bread-crumb clearfix">
                            <li><a href="{{ route('clientIndex') }}">@lang('menu.index')</a></li>
                            <li class="active">{{ $metaTitle }}</li>
                        </ul>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <ul class="social-nav clearfix">
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

    </section>

    <!--contact-info-->
    <section class="contact-info-section">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="column col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box wow zoomInStable" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <!--icon-box-->
                        <div class="icon-box">
                            <span class="flaticon-home-1"></span>
                        </div>

                        <h3>@lang('menu.addressTitle')</h3>
                        <div class="text">@lang('menu.address')</div>
                    </div>
                </div>

                <div class="column col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box wow zoomInStable" data-wow-delay="500ms" data-wow-duration="1500ms">
                        <!--icon-box-->
                        <div class="icon-box">
                            <span class="flaticon-mail-3"></span>
                        </div>

                        <h3>@lang('menu.sendUsMail')</h3>
                        <div class="text">geo@jutagroup.ge</div>
                    </div>
                </div>

                <div class="column col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box wow zoomInStable" data-wow-delay="1000ms" data-wow-duration="1500ms">
                        <!--icon-box-->
                        <div class="icon-box">
                            <span class="flaticon-technology"></span>
                        </div>

                        <h3>{{ __('menu.callUs') }}</h3>
                        <div class="text">(+995) 599-555-246</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--contact-section-->
    <section class="contact-form-section">
        <div class="auto-container">
            <div class="sec-title-eight padd-bott-10">
                <h2>@lang('menu.sendUsThoughtWebSite')</h2>
            </div>

            <!-- Contact Form -->
            <div class="default-form contact-form">

                <form wire:submit.prevent="sendMail" id="contact-form">
                    <div class="row clearfix">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <input type="text" wire:model="username" placeholder="@lang('placeholders.first_name') *">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <input type="email" wire:model="email" placeholder="@lang('placeholders.email') *">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <input type="text" wire:model="subject" placeholder="@lang('placeholders.subject') *">
                            @error('subject')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <textarea wire:model="message" placeholder="@lang('placeholders.message') *"></textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12"><button type="submit"
                                class="theme-btn btn-style-three">@lang('placeholders.send')</button></div>
                    </div>
                </form>

            </div>
            <!--End Contact Form -->

        </div>
    </section>

    <!--Map Section-->
    <section class="map-section">
        <div class="map-outer">

            <!--Map Canvas-->
            <div class="map-canvas" data-zoom="10" data-lat="23.815811" data-lng="90.412580" data-type="roadmap"
                data-hue="#fc721e" data-title="Dhaka"
                data-content="Dhaka 1000-1200, Bangladesh<br><a href='mailto:info@youremail.com'>info@youremail.com</a>"
                style="height:480px;">
            </div>

        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/validate.js') }}"></script>
    @endpush
</div>
