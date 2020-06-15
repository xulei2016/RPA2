@component('Admin.widgets.viewForm')
    @slot('title')
        操作图片查看
    @endslot
    @slot('formContent')
    <link rel="stylesheet" href="{{ URL::asset('/include/swiper/swiper.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/include/fancybox/fancybox.css')}}">
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="swiper-container" id="swiper" style="height:700px;">
                    <div class="swiper-wrapper">
                        @foreach($list as $img)
                            <div data-fancybox class="swiper-slide" href="/admin/rpa_bank_relation/showImg?url={{ $img }}" >
                                <img style="width:780px;" src="/admin/rpa_bank_relation/showImg?url={{ $img }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div><!--左箭头。如果放置在swiper-container外面，需要自定义样式。-->
                    <div class="swiper-button-next"></div><!--右箭头。如果放置在swiper-container外面，需要自定义样式。-->
                </div>
            </div>
        </div>
        
    
    </div>
    
    @endslot

    @slot('formScript')
    <script src=" {{ URL::asset('/include/swiper/swiper.min.js')}} "></script>
    <script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
    <script>
        $(function(){
            new Swiper("#swiper", {
            pagination: {
                el: '.swiper-pagination',
            },
            // autoplay: false,//可选选项，自动滑动
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            loop:true,
            
        });
        })
    </script>
    @endslot
@endcomponent