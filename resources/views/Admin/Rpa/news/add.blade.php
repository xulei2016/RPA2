@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="zwtx" placeholder="任务名称" required disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-sm-2 control-label"><span class="must-tag">*</span>任务类型</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="type" name="type" value="1">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>日期设定</label>
            <div class="col-sm-10">
                <div class="date">
                    <input type="text" class="form-control" id="date" name="date" placeholder="日期设定">
                </div>
                <div class="week hidden">
                    <label><input type="checkbox" class="select-single" name="week[]" value="0">星期日</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="1">星期一</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="2">星期二</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="3">星期三</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="4">星期四</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="5">星期五</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="6">星期六</label>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>目标站点</label>
            <div class="col-sm-10">
                <div class="target_web">
                    <div class="row">
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="web" name="web" placeholder="例如：https://wallstreetcn.com/" required>
                        </div>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="num" name="num" placeholder="文章数量" required>
                        </div>
                        <div class="col-xs-2">
                            <a href="javascript:void(0);" id="add_web" class="btn btn-sm btn-primary">增加</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>执行时间</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="implement_type" value="1">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-xs-9">
                        <input type="text" class="form-control" id="time" name="time" placeholder="点击按钮添加时间">
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:void(0);" id="add_time" class="btn btn-sm btn-primary">00:00:00</a>
                    </div>
                </div>
                <div class="row hidden">
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="start_time" name="start_time" placeholder="开始时间">
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="end_time" name="end_time" placeholder="结束时间">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" id="mins" name="mins" placeholder="分割间隔(分钟)">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="bewrite" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="bewrite" id="bewrite" placeholder="任务描述" required></textarea>
            </div>
        </div>
        <input type="hidden" class="form-control" id="jsondata" name="jsondata">

    @endslot
@endcomponent

<script>
$(function(){
    var type = false;
    var time_type = false;

    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
    }
    
    //事件绑定
    function bindEvent(){
        //时间
        let nowDate = getFormatDate();
        //定义时间按钮事件
        let st = '#modal form #add_time';
        let et = '#modal form #date';
        let implement_starttime = '#modal form #start_time';
        let implement_endtime = '#modal form #end_time';
        laydate.render({elem: implement_starttime, type: 'datetime'});
        laydate.render({elem: implement_endtime, type: 'datetime'});
        laydate.render({elem: et, type: 'date'});
        laydate.render({elem: st, type: 'time',done: function(value, date, endDate){
            let time = $('#modal form #time').val();
            let times = time ? time+','+value: value ;
            $('#modal form #time').val(times);
        }});

        //任务类型
        $('#modal form input#type').bootstrapSwitch({"onColor":"lightseagreen","offColor":"danger",'onText':"一次性任务",'offText':"循环任务","state":true,onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('.week').removeClass('hidden').prev().addClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('.date').removeClass('hidden').next().addClass('hidden');
            }
            type = state;
        }});

        //执行时间类型
        $('#modal form input#implement_type').bootstrapSwitch({"onColor":"lightseagreen","offColor":"info",'onText':"自定义",'offText':"分割时间段","state":true,onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('div.row:last').removeClass('hidden').prev().addClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('div.row:first').removeClass('hidden').next().addClass('hidden');
            }
            time_type = state;
        }});

        //添加站点
        $("#modal form #add_web").on('click',function(){
            let _this = $(this);
            let html = '<div class="row"><div class="col-xs-7"><input type="text" class="form-control" name="web" placeholder="站点名称" required></div>'
                    +' <div class="col-xs-3"><input type="text" class="form-control" name="num" placeholder="文章数量"></div>'
                    +' <div class="col-xs-2"><a href="javascript:void(0);" class="btn btn-sm btn-danger del_web">删除</a></div>'
                    +'</div>';
            _this.parents('.target_web').append(html);
    
            $("#modal form a.del_web").unbind().on('click',function(e){
                $(this).parents('div.row').remove();
            });
        });
    }

    //序列化
    function serializeForm(){
        //处理站点
        if(!type){
            $('#modal form #date').val('');
        }else{
            $('#modal form input[name="week[]"]:checked').each(function(){
                $(this).prop("checked",false);
            });
        }
        if(!time_type){
            $('#modal form #time').val('');
        }else{
            $('#modal form #start_time').val('');
            $('#modal form #end_time').val('');
            $('#modal form #mins').val('');
        }
        let jsondata = {};
        $('#modal form .target_web .row').each(function(){
            let web = $(this).find("input[name='web']").val().trim();
            let num = $(this).find("input[name='num']").val().trim();
            jsondata[web] = num;
        });
        $('#modal form #jsondata').val(JSON.stringify(jsondata));
    }

    //添加
    function add(e){
        serializeForm();
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_news',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };

    init();
});
</script>