<!DOCTYPE HTML>
<html>

<head>
    <title>流程一览</title>
    <meta name="keyword" content="流程一览">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{URL::asset('/include/flowdesign/css/bootstrap.css')}}">

    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="/include/flowdesign/css/bootstrap-ie6.css?2025">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="/include/flowdesign/css/ie.css?2025">
    <![endif]-->

    <link rel="stylesheet" href="{{URL::asset('/include/toastr/toastr.min.css')}}">
    <!-- design flow -->
    <link rel="stylesheet" href="{{URL::asset('/css/admin/flow/design.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/flow/flowDesign.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/jquery-contentMenu/jquery.contextMenu.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/jquery-multisect2side/css/jquery.multiselect2side.css')}}"
          media="screen">
</head>

<body>



<!--end div-->

<div class="container mini-layout" id="flowdesign_canvas"></div>

<!-- jQuery 为最优版本，请勿替换，也不要使用本地低版本，只能使用cdn -->
<script src="https://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>
<script src="{{URL::asset('/include/toastr/toastr.min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-ui/jquery-ui-1.9.2-min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-contentMenu/jquery.contextMenu.r2.js')}}"></script>
<script src="{{URL::asset('/include/flowdesign/js/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-jsPlumb/jquery.jsPlumb-1.3.16-all-min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-multisect2side/js/jquery.multiselect2side.js')}}"></script>
<script src="{{URL::asset('/include/flowdesign/leipi.flowdesign.v3.js')}}"></script>

<script type="text/javascript">

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var nodeData = {!!$info-> jsplumb ?: '{}'!!};
        var flow_obj = $("#flowdesign_canvas");


        function init() {
            bindEvent();
        }

        //事件绑定
        function bindEvent() {

        }

        //初始化流程设计器
        var _canvas = flow_obj.Flowdesign({
            nodeData: nodeData,
            canvasMenus: {},
            nodeMenus: {},
            onlyShow:true,
            fnRepeat: () => {

            },
            fnClick: () => {
                // jsPlumb.repaintEverything()
            },
            fnDbClick: () => {

            }
        });
        init();
    });

</script>

</body>

</html>