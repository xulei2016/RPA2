$(function(){
    //初始化
    function init(){
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#modal form").validate(validateInfo);
    }

    //绑定事件
    function bindEvent(){
        //表单提交
        $('#modal form #save').click(function(){
            add($(this).parents('form'));
        });

        $("#select2_menu").select2({
            "allowClear":true,
            "placeholder":"父级菜单",
        });

        $.post('/admin/sys_permission/getTree', {}, function(json){
            if(200 == json.code){
                html = initTree(json.data);
                $('#select2_menu').append(html);
            }else{
                Swal(json.info, '', 'error');
            }
        });
    }

    function initTree(data){
        var num = data.length;
        let html = '';
        for(let i = 0;i < num; i++){
            let json = data[i];
            html += "<option value ="+json.id+" table="+json.table+">"+ moreString(json['table']) + json.desc +"</option>"
            if(json.hasOwnProperty('child')){
                html += initTree(json.child);
            }
        }
        return html;
    }

    function moreString(n){
        let html = '&nbsp;&nbsp;';
        let i = 0;
        while(i < n){
            i++;
            html += html;
        }
        return html;
    }

    //添加
    function add(e){
        let table = $('#modal #select2-menu option:selected').attr('table');
        $('#modal #table').val(table);
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_permission',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };
        
    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//名称
                required:true
            },
            desc:{
                required:true
            },
            select2_menu:{
                required:true
            },
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});