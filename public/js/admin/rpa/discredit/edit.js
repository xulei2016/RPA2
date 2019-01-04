$(function(){
    var type = $("#modal form #type ").is(':checked');
    var time_type = $("#modal form #implement_type ").is(':checked');

    /**
     * 页面初始化
     */
    function init(){
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#modal form").validate(validateInfo);
    }
    
    //事件绑定
    function bindEvent(){
        //表单提交
        $('#modal form #save').click(function(){
            add($(this).parents('form'));
        });

        //时间
        let nowDate = getFormatDate();
        //定义时间按钮事件
        let jsondate = '#modal #jsondate';
        laydate.render({elem: jsondate, type: 'date'});
        let st = '#modal form #add_time';
        let et = '#modal form #date';
        let implement_starttime = '#modal form #start_time';
        let implement_endtime = '#modal form #end_time';
        laydate.render({elem: implement_starttime, type: 'time'});
        laydate.render({elem: implement_endtime, type: 'time'});
        laydate.render({elem: et, type: 'date'});
        laydate.render({elem: st, type: 'time',done: function(value, date, endDate){
            let time = $('#modal form #time').val();
            let times = time ? time+','+value: value ;
            $('#modal form #time').val(times);
        }});

        //任务类型
        $('#modal form input#type').bootstrapSwitch({"onColor":"lightseagreen","offColor":"danger",'onText':"一次性任务",'offText':"循环任务",onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('.week').removeClass('hidden').prev().addClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('.date').removeClass('hidden').next().addClass('hidden');
            }
            type = state;
        }});

        //执行时间类型
        $('#modal form input#implement_type').bootstrapSwitch({"onColor":"lightseagreen","offColor":"info",'onText':"自定义",'offText':"分割时间段",onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('div.row:last').removeClass('hidden').prev().addClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('div.row:first').removeClass('hidden').next().addClass('hidden');
            }
            time_type = state;
        }});
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
            jsondata.account = $(this).find("input[name='jsonaccount']").val().trim();
            jsondata.date = $(this).find("input[name='jsondate']").val().trim();
            jsondata.pwd = $(this).find("input[name='jsonpwd']").val().trim();
        });
        $('#modal form #jsondata').val(JSON.stringify(jsondata));
    }
    
    //添加
    function add(e){
        serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $('#modal form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_discredit/'+id,
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.form.errorReponse
    };
    
    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//名称
                required:true
            },
            web:{
                required:true
            },
            num:{
                required:true
            },
            bewrite:{
                required:true
            },
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});