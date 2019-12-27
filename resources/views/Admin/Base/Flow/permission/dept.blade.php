<!DOCTYPE HTML>
<html>

<head>

    <title>步骤权限</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" href="{{URL::asset('/include/flowdesign/css/bootstrap.css')}}">
    <!--[if lte IE 6]>
    <link rel="stylesheet" href="{{URL::asset('/include/flowdesign/css/bootstrap-ie6.css?2025')}}">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" href="{{URL::asset('/include/flowdesign/css/ie.css?2025')}}">
    <![endif]-->
    <!--select 2-->
    <link rel="stylesheet" href="{{URL::asset('/include/jquery-multisect2side/css/jquery.multiselect2side.css')}}" media="screen">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/flow/site.css?2025')}}">

    <script src="https://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>
    <script src="{{URL::asset('/include/flowdesign/js/bootstrap.min.js')}}"></script>
    <!--select 2-->
    <script src="{{URL::asset('/include/jquery-multisect2side/js/jquery.multiselect2side.js')}}"></script>

    <style>
        /*自定义 multiselect2side */

        .ms2side__div {
            border: 0px solid #333;
            padding-top: 30px;
            margin-left: 25px;
        }

        .ms2side__div select {
            height: auto;
            height: 320px;
        }

        .ms2side__header {
            margin-left: 3px;
            margin-top: -20px;
            margin-bottom: 5px;
            width: 180px;
            height: 20px;
        }

        .ms2side__div select {
            width: 180px;
            float: left;
        }

        .dialog_main {
            margin: 5px 0 0 5px;
        }
    </style>

</head>

<body>

    <div class="dialog_main">

        <form class="form-search" id="dialog_search">
            <select name="" class="input-small">
                <option value="2">部门</option>
            </select>
            <input type="text">
            <button type="submit" class="btn">搜索</button>
        </form>

        <div class="row">
            <div class="span3">
                <p>部门筛选</p>
                <select id="dept" name="dept_id" multiple="multiple" size="18">
                    @foreach($depts as $v)
                        <option value="{{$v['id']}}">{{$v['html']}}{{$v['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="span6">
                <select name="dialog_searchable" id="dialog_searchable" multiple="multiple" style="display:none;">
                    @foreach($depts as $v)
                        <option value="{{$v['id']}}" @if(in_array($v->id,$select_depts->pluck('id')->toArray())) selected="selected" @endif >{{$v['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row span7">
            <div class="pull-right">
                <button class="btn btn-info" type="button" id="dialog_confirm">确定</button>
                <button class="btn" type="button" id="dialog_close">取消</button>
            </div>
            <div class="pull-left offset2">
                <input type="radio" checked="checked">部门
            </div>
        </div>
    </div>
    <!--end container-->

    <script type="text/javascript">
        $(function(){

            var depts = '{!!$depts_json!!}';
            depts = JSON.parse(depts);

            $('#dialog_searchable').multiselect2side({
                selectedPosition: 'right',
                moveOptions: false,
                labelsx: '备选',
                labeldx: '已选',
                autoSort: true
                //,autoSortAvailable: true
            });

            //搜索用户
            $("#dialog_search").on("submit",function(e){
                let v = e.target[1].value;
                var optionList = [];
                let HTML = '';
                for (var i = 0; i < depts.length; i++) {
                    if (depts[i].name.indexOf(v) > -1) {
                        HTML += `<option value="${depts[i].id}">${depts[i].name}</option>`;
                    }
                }
                $('#dialog_searchablems2side__sx').html(HTML);
                
                return false;
            });

            //左侧菜单点击事件
            $('#dept').change(function () {
                var optionList = [];
                let dept_id = $(this).val()[0];
                let HTML = '';
                for (var i = 0; i < depts.length; i++) {
                    if (depts[i].pid == dept_id) {
                        HTML += `<option value="${depts[i].id}">${depts[i].name}</option>`;
                    }
                }
                $('#dialog_searchablems2side__sx').html(HTML);
            });
            
            $("#dialog_confirm").on("click",function(){
                var nameText = [];
                var idText = [];
                var globalValue = '@leipi@';
                if(!$('#dialog_searchable').val()){
                    //alert("未选择");
                    //这里不提示了，万一要清空呢
                }else{
                    $('#dialog_searchable option').each(function(){
                    if($(this).attr("selected"))
                    {
                        if($(this).val()=='all')//有全部，其它就不要了
                        {
                            nameText = [];
                            idText = [];
                            nameText.push($(this).text());
                            idText.push($(this).val());
                            return false;
                        }
                        nameText.push($(this).text());
                        idText.push($(this).val());
                    }
                    });
                    globalValue = nameText.join(',') + '@leipi@' + idText.join(',');
                }
                if(window.ActiveXObject){ //IE  
                    window.returnValue = globalValue
                }else{ //非IE  
                    if(window.opener) {  
                        window.opener.callbackSuperDialog(globalValue) ;  
                    }
                }  
                window.close();
            });
            $("#dialog_close").on("click",function(){
                window.close();
            });

        });

    </script>

</body>

</html>