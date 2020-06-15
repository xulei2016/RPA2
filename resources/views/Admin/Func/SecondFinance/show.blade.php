@component('Admin.widgets.viewForm')
    @slot('title')
        截图查看
    @endslot
    @slot('formContent')
        <link rel="stylesheet" href="{{ URL::asset('/include/swiper/swiper.min.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/include/fancybox/fancybox.css')}}">
        <style>
            .mt10 {
                margin-top: 10px;
            }

            .h400 {
                height: 400px;
            }

            .h500 {
                height: 500px;
            }


            .h800 {
                height: 800px;
            }

            .mt30 {
                margin-top: 30px;
            }

            img {
                width: 100%;
            }

            .panel {
                min-height: 200px;
            }

            .tab-pane {
                height: 400px;
            }

            .swiper-slide {
                background-position: center;
                background-repeat: no-repeat;
            }

            .gallery-thumbs {
                box-sizing: border-box;
            }

            .gallery-thumbs .swiper-slide {
                width: 25%;
                height: 100%;
                opacity: 0.4;
            }

            .gallery-thumbs .swiper-slide-thumb-active {
                opacity: 1;
            }

            .swiper-slide {
                font-size: 18px;
                color: #fff;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                padding: 15px 20px;
            }

            .swiper-slide .title {
                font-size: 30px;
                font-weight: 200;
            }

            label {
                text-align: right;
                line-height: 33px;
            }

        </style>

        <div class="container-fluid">
            <div class="panel panel-default h800">
                <div class="panel-body">

                    <div class="swiper-container h500 mt10" id="swiper">
                        <div class="swiper-wrapper">
                            @foreach($list as $img)
                                <div data-fancybox class="swiper-slide" href="/admin/rpa_second_finance/showImg?url={{ encrypt($img['url']) }}"
                                     style="background:url('/admin/rpa_second_finance/showImg?url={{ encrypt($img['url']) }}');background-size: 100% 600px;">
                                    <div class="title" style="color: red;text-align: center;" data-swiper-parallax="-300">{{ $img['name'] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div><!--左箭头。如果放置在swiper-container外面，需要自定义样式。-->
                        <div class="swiper-button-next"></div><!--右箭头。如果放置在swiper-container外面，需要自定义样式。-->
                    </div>
                    <hr style="margin-top: 40px;">
                    <div class="swiper-container gallery-thumbs mt10" id="thumbs">
                        <div class="swiper-wrapper">
                            @foreach($list as $img)
                                <div class="swiper-slide"><img src="/admin/rpa_second_finance/showImg?url={{ encrypt($img['url']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>



                </div>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src=" {{ URL::asset('/include/swiper/swiper.min.js')}} "></script>
        <script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
        <script>
            $(function () {
                var init = "#swiper"
                new Swiper(init, {
                    pagination: {
                        el: init + ' .swiper-pagination',
                    },
                    // autoplay: false,//可选选项，自动滑动
                    navigation: {
                        nextEl: init + " .swiper-button-next",
                        prevEl: init + " .swiper-button-prev",
                    },
                    loop: true,
                    thumbs: {
                        swiper: {
                            el: '#thumbs',
                            spaceBetween: 20,
                            slidesPerView: 4,
                            watchSlidesVisibility: true,/*避免出现bug*/
                        },
                    }
                });
            })
        </script>

    @endslot
@endcomponent



