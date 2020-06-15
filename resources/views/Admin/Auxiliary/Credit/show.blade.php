@component('Admin.widgets.viewForm')
    @slot('title')
        失信查询
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
            <ul class="nav nav-tabs" id="myTab">
                @foreach($list as $k  => $v)
                    @if($k == 0)
                        <li class="active"><a href="#{{$v['type']}}" data-toggle="tab">{{ $v['name'] }}</a></li>
                    @else
                        <li><a href="#{{$v['type']}}" data-toggle="tab">{{ $v['name'] }}</a></li>
                    @endif
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($list as $k => $v)
                    <div class="tab-pane @if($k == 0) active @endif" id="{{ $v['type'] }}">
                        <div class="swiper-container h500 mt10" id="{{ $v['type'] }}-swiper">
                            <div class="swiper-wrapper">
                                @foreach($v['list'] as $img)
                                    <div data-fancybox class="swiper-slide" href="{{ buildImageUrl($img['url'])  }}"
                                            style="background: url({{ buildImageUrl($img['url']) }});background-size: 100% 600px;">
                                        {{--                                                <div class="title" data-swiper-parallax="-300">{{ $img['name'] }}</div>--}}
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev"></div><!--左箭头。如果放置在swiper-container外面，需要自定义样式。-->
                            <div class="swiper-button-next"></div><!--右箭头。如果放置在swiper-container外面，需要自定义样式。-->
                        </div>
                        <hr style="margin-top: 40px;">
                        <div class="swiper-container gallery-thumbs mt10" id="{{ $v['type'] }}-thumbs">
                            <div class="swiper-wrapper">
                                @foreach($v['list'] as $img)
                                    <div class="swiper-slide"><img src="{{ buildImageUrl($img['url']) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
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
            var data = {
                '#person': false,
                '#company': false,
                '#legalPerson ': false,
                '#agentPerson ': false,
            };

            var list = $(".nav-tabs li a");
            var init = $(list[0]).attr('href');
            new Swiper(init + "-swiper", {
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
                        el: init + '-thumbs',
                        spaceBetween: 20,
                        slidesPerView: 4,
                        watchSlidesVisibility: true,/*避免出现bug*/
                    },
                }
            });
            data[init] = true;
            $(".nav-tabs li a").on('click', function () {
                var type = $(this).attr('href');
                if (!data[type]) {
                    setTimeout(function () {
                        new Swiper(type + "-swiper", {
                            pagination: {
                                el: init + ' .swiper-pagination',
                            },
                            // autoplay: false,//可选选项，自动滑动
                            navigation: {
                                nextEl: type + " .swiper-button-next",
                                prevEl: type + " .swiper-button-prev",
                            },
                            loop: true,
                            thumbs: {
                                swiper: {
                                    el: type + '-thumbs',
                                    spaceBetween: 20,
                                    slidesPerView: 4,
                                    watchSlidesVisibility: true,/*避免出现bug*/
                                },
                            }
                        });
                        data[type] = true;
                    }, 500);
                }
            });
        })
    </script>
 
@endslot
@endcomponent



