//查询视频审核
function reselect(id){
    $.ajax({
        method: 'post',
        url: '/admin/rpa_archives/selectVideo',
        data:{
            vid:id
        },
        dataType:'json',
        success: function (json) {
            if(json.status == 200){
                text = "视频审核通过,请直接点击下一步！";
                $('.checkVideo i').removeClass().addClass('fa fa-check-circle-o');
                $('.checkVideo i').css('color','green');

            }else if(json.status == 501){
                var id = json.msg;
                text = "审批还未审核，请<a href='javascript:;' style='margin-left: 20px;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_customer_video_collect/"+id+"/edit' title='点击审核'>点击审核</a> 或 <button type='button' class='btn btn-sm btn-success' onclick='reselect("+id+");'>重新查询</button>"
                $('.checkVideo i').removeClass().addClass('fa fa-exclamation-circle');
                $('.checkVideo i').css('color','orange');

            }else{
                text = "未上传视频，请先上传!";
                $('.checkVideo i').removeClass().addClass('fa fa-times-circle-o');
                $('.checkVideo i').css('color','red');

            }
            $('.checkVideo span').html(text);
        }
    });
}
$(function(){
    let modal = '#wizard';
    let result = true;
    let user_id = '';
    $("#wizard").steps({
        headerTag: "h2",// 指定头部对应什么HTML标签
        bodyTag: "section",// 指定步骤内容对应的HTML标签
        stepsOrientation: 0, // 指定步骤为水平--vertical（垂直） horizontal（水平）
        transitionEffect: "slideLeft", // 步骤切换动画
        // forceMoveForward: true, // 防止跳转到上一步
        labels: {
            finish: "完成", // 修改按钮得文本
            next: "下一步", // 下一步按钮的文本
            previous: "上一步", // 上一步按钮的文本
            loading: "Loading ...",
        },
        // 下一步切换时的监听
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex) {
                return true;
            }
            if(newIndex == 0){
                result = true;
            }
            //下一步是视频见证
            else if (newIndex == 1) {
                var customer_type = $('#customer_type').val();
                var business_type = $('#business_type').val();
                var customer_zjbh = $('#customer_zjbh').val();
                var customer_name = $('#customer_name').val();
                var customer_zjzh = $('#customer_zjzh').val();

                if(customer_name == '' || customer_zjbh == '' || customer_zjzh == ''){
                    toastr.error('客户名称、证件编号、资金账号不能为空');
                    return false;
                }

                if(customer_type == '个人'){
                    var regIdNo = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
                    if(!regIdNo.test(customer_zjbh)) {
                        toastr.error('证件编号有误！');
                        return false;
                    }
                }

                //1.发送请求，保存客户数据
                $.ajax({
                    method: 'post',
                    url: '/admin/rpa_archives',
                    data:{
                        name:customer_name,
                        zjbh:customer_zjbh,
                        type:customer_type,
                        btype:business_type,
                        zjzh:customer_zjzh,
                        user_id:user_id,
                    },
                    dataType:'json',
                    success: function (json) {
                        user_id = json.data.id;
                    }
                });

                //2.根据不同的业务类型增加删除节点
                var a = $("#wizard").steps("getStep",4);
                if(a.title != '档案归档'){
                    $("#wizard").steps("remove",4);
                }
                if(business_type == '适当性权限申请'){
                    $("#wizard").steps("insert",4, {
                        title: "适当性测评",
                        content: "<h3>适当性测评</h3>" +
                            "     <div class=\"card card-primary card-outline sdx\">" +
                            "         <i class=\"fa fa-refresh\"></i>" +
                            "         <span>正在查询适当性情况。。。</span>" +
                            "     </div>"
                    });
                }else if(business_type == '激活'){
                    $("#wizard").steps("insert",4, {
                        title: "音频上传",
                        content: '<h3>音频上传</h3>' +
                            '     <div class="container my-4">' +
                            '        <form enctype="multipart/form-data">' +
                            '           <div class="file-loading">' +
                            '               <input multiple id="file-0b" name="file" type="file">\n' +
                            '           </div>' +
                            '        <br>' +
                            '        </form>' +
                            '    </div>'
                    });
                }
                //3.发请求，判断视频是否审核
                var text = "";
                $.ajax({
                    method: 'post',
                    url: '/admin/rpa_archives/selectVideo',
                    data:{
                        customer_name:customer_name,
                        customer_sfzh:customer_zjbh,
                        btype:business_type
                    },
                    dataType:'json',
                    success: function (json) {
                        if(json.status == 200){
                            text = "视频审核通过,请直接点击下一步！";
                            $('.checkVideo i').removeClass().addClass('fa fa-check-circle-o');
                            $('.checkVideo i').css('color','green');

                        }else if(json.status == 501){
                            var id = json.msg;
                            text = "审批还未审核，请<a href='javascript:;' style='margin-left: 20px;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_customer_video_collect/"+id+"/edit' title='点击审核'>点击审核</a> 或 <button type='button' class='btn btn-sm btn-success' onclick='reselect("+id+");'>重新查询</button>"
                            $('.checkVideo i').removeClass().addClass('fa fa-exclamation-circle');
                            $('.checkVideo i').css('color','orange');

                        }else{
                            text = "未上传视频，请先上传!";
                            $('.checkVideo i').removeClass().addClass('fa fa-times-circle-o');
                            $('.checkVideo i').css('color','red');

                        }
                        $('.checkVideo span').html(text);
                    }
                });
            }
            //下一步是失信查询
            else if(newIndex == 2){
                //if(!result) return result;

                //视频审核通过，修改step
                $.ajax({
                    url:'/admin/rpa_archives/changeStep',
                    data:{
                        id:user_id,
                        step:2
                    },
                    type:'POST',
                    dataType:"json"
                });

                //判断客户类型
                var customer_zjbh = $('#customer_zjbh').val();
                var customer_name = $('#customer_name').val();
                var customer_type = $('#customer_type').val();

                //失信查询
                var flag = 0;
                $.ajax({
                    url:'/admin/rpa_archives/credit',
                    data:{
                        name:customer_name,
                        idCard:customer_zjbh,
                        customer_type:customer_type,
                        tid:user_id,
                        type:1
                    },
                    type:'POST',
                    dataType:"json",
                    success:function(_data){
                        if(_data.status == 200){
                            if(customer_type == '个人'){
                                $(".credit table tbody tr.selecting td").html('正在查询...');
                                var timer = setInterval(function () {
                                    if(flag >= 10){
                                        clearInterval(timer);
                                    }else{
                                        $.ajax({
                                            url:'/admin/rpa_archives/credit',
                                            data:{
                                                tid:user_id,
                                                type:2
                                            },
                                            type:'POST',
                                            dataType:"json",
                                            success:function(_data){
                                                if(_data.status == 200){
                                                    var html = "";
                                                    for(let i=0;i < _data.msg.length;i++){
                                                        html += '<tr>' +
                                                            '<td>'+_data.msg[i].name+'</td>' +
                                                            '<td>'+_data.msg[i].idCard+'</td>' +
                                                            '<td>'+getSpan(_data.msg[i].sfstate)+'</td>' +
                                                            '<td>'+getSpan(_data.msg[i].cfastate)+'</td>' +
                                                            '<td>'+getSpan(_data.msg[i].hsstate)+'</td>' +
                                                            '<td>'+getSpan(_data.msg[i].xyzgstate)+'</td>' +
                                                            '</tr>';
                                                    }
                                                    console.log(html);
                                                    $(".selecting").after(html);
                                                    $(".selecting").remove();
                                                    flag = 10;
                                                }else{
                                                    flag++;
                                                    toastr.error(_data.msg);
                                                }

                                            },
                                            error: function () {
                                                flag = 10;
                                                toastr.error('网络错误');
                                            }
                                        })
                                    }
                                },3000);
                            }
                        }else{
                            toastr.error(_data.msg);
                        }
                    },
                    error: function () {
                        toastr.error('网络错误');
                    }
                });
            }
            //下一步附件上传
            else if(newIndex == 3){
                //判断是否已经失信查询完成
                var customer_type = $('#customer_type').val();
                var td = $(".credit table tr td").length;
                var div = '';
                if(customer_type == '个人'){
                    if(td < 8){
                        result = false;
                    }else{
                        result = true;
                    }
                    div = '.personal';
                }else{
                    if(td < 12){
                        result = false;
                    }else{
                        result = true;
                    }
                    div = '.company';
                }
                if(!result) return result;

                //失信通过，修改step,同步保存失信记录
                var list = [];
                $(".credit "+div+" table tr").each(function(){
                    if($(this).find('td').length > 0){
                        var name = $(this).find('td:eq(0)').text();
                        var idCard = $(this).find('td:eq(1)').text();
                        var zq = $(this).find('td:eq(2)').text();
                        var qh = $(this).find('td:eq(3)').text();
                        var hh = $(this).find('td:eq(4)').text();

                        list.push({
                            name:name,
                            idCard:idCard,
                            zq:zq,
                            qh:qh,
                            hh:hh
                        })
                    }
                });
                $.ajax({
                    url:'/admin/rpa_archives/changeStep',
                    data:{
                        id:user_id,
                        step:3,
                        list:JSON.stringify(list)
                    },
                    type:'POST',
                    dataType:"json"
                });

                /**
                 * 附件上传
                 */
                $('#file-0a').fileinput({
                    theme: 'fa',
                    language: 'zh',
                    minFileCount: 1,
                    allowedFileExtensions : [
                        'jpg' ,'jpeg', 'gif', 'png', 'bmp', 'pdf', 'zip', 'rar','doc'
                    ],
                    uploadUrl: '/admin/rpa_archives/uploadEnclosure',
                    uploadAsync: true,   //异步上传
                    uploadExtraData: {    //上传额外数据
                        id: user_id,
                    },
                });
            }
            //下一步
            else if(newIndex == 4){
                //附件上传通过，修改step
                $.ajax({
                    url:'/admin/rpa_archives/changeStep',
                    data:{
                        id:user_id,
                        step:4
                    },
                    type:'POST',
                    dataType:"json",
                    success: function () {
                        //下一步判断
                        var business_type = $('#business_type').val();
                        if(business_type == '适当性权限申请'){
                            //下一步是适当性
                            //发送请求，查询适当性情况
                            var customer_type = $('#customer_type').val();
                            var customer_zjbh = $('#customer_zjbh').val();
                            $.ajax({
                                url:'/admin/rpa_archives/selectSdxLevel',
                                data:{
                                    customer_type:customer_type,
                                    customer_zjbh:customer_zjbh
                                },
                                type:'POST',
                                dataType:"json",
                                success:function(json){
                                    if(json.status == 200){
                                        text = "适当性测评开始时间："+json.msg.corpBeginDate+"，适当性测评结束时间："+json.msg.corpEndDate+"，风险等级："+json.msg.corpRiskLevel;
                                        $('.sdx i').removeClass().addClass('fa fa-check-circle-o');
                                        $('.sdx i').css('color','green');

                                    }else{
                                        text = "未进行适当性测评！";
                                        $('.sdx i').removeClass().addClass('fa fa-times-circle-o');
                                        $('.sdx i').css('color','red');

                                    }
                                    $('.sdx span').html(text);
                                }
                            });
                        }else if(business_type == '激活'){
                            /**
                             * 下一步是音频上传
                             * 音频上传
                             */
                            $('#file-0b').fileinput({
                                theme: 'fa',
                                language: 'zh',
                                minFileCount: 1,
                                allowedFileExtensions : [ 'mp3','wav' ],
                                uploadUrl: '/admin/rpa_archives/uploadAudio',
                                uploadAsync: true,   //异步上传
                                uploadExtraData: {    //上传额外数据
                                    id: user_id,
                                },
                            });
                        }else{
                            // 下一步结束
                            //结束，修改step
                            $.ajax({
                                url:'/admin/rpa_archives/changeStep',
                                data:{
                                    id:user_id,
                                    step:5
                                },
                                type:'POST',
                                dataType:"json"
                            });
                        }
                    }
                });
            }
            else{
                //结束，修改step
                $.ajax({
                    url:'/admin/rpa_archives/changeStep',
                    data:{
                        id:user_id,
                        step:5
                    },
                    type:'POST',
                    dataType:"json"
                });

            }

            return result;
        },
        onFinishing: function (event, currentIndex) {// 完成时得监听
            window.location.href = '/admin/rpa_archives';
            return true;
        },
    });

    //查询资金账户
    $(".selectZJZH").click(function(){
        var customer_zjbh = $('#customer_zjbh').val();
        var customer_name = $('#customer_name').val();

        $.ajax({
            url:'/admin/rpa_archives/selectZJZH',
            data:{
                customer_zjbh: customer_zjbh,
                customer_name: customer_name
            },
            type:'POST',
            dataType:"json",
            success: function(json){
                if(json.status == 200){
                    $('#customer_zjzh').val(json.msg);
                }
            }
        });
    });

    //任务类型
    $(modal+' input#customer_type').bootstrapSwitch({
        "onColor":"lightseagreen",
        "offColor":"danger",
        'onText':"个人",
        'offText':"法人",
        onSwitchChange: function(e,state){
            if(state){
                $(this).val('个人');
                $(".company").hide();
                $(".personal").show();
            }else{
                $(this).val('法人');
                $(".company").show();
                $(".personal").hide();
            }
        }
    });

    //增加五类人按钮
    $('.addCustomer').click(function(){
        var fieldset = '<div class="whole">' +
            '               <div class="form-group row">' +
            '                 <label class="col-sm-2 control-label">客户名称</label>' +
            '                 <div class="col-sm-10">' +
            '                     <input type="text" class="form-control customer_name" placeholder="请输入客户名称">' +
            '                 </div>' +
            '               </div>' +
            '               <div class="form-group row">' +
            '                 <label class="col-sm-2 control-label">证件号码</label>' +
            '                 <div class="col-sm-10">' +
            '                     <input type="text" class="form-control customer_zjbh" placeholder="请输入证件号码">' +
            '                 </div>' +
            '               </div>' +
            '               <div class="form-group row">' +
            '                 <label class="col-sm-2 control-label">客户类型</label>' +
            '                 <div class="col-sm-10">' +
            '                     <input type="checkbox" class="fddbr"> <label>法定代表人 &nbsp;&nbsp;</label>' +
            '                     <input type="checkbox" class="khdlr"> <label>开户代理人 &nbsp;&nbsp;</label>' +
            '                     <input type="checkbox" class="zjdbr"> <label>资金调拨人 &nbsp;&nbsp;</label>' +
            '                     <input type="checkbox" class="zlxdr"> <label>指令下达人 &nbsp;&nbsp;</label>' +
            '                     <input type="checkbox" class="zdqrr"> <label>账单确认人 &nbsp;&nbsp;</label>' +
            '                 </div>' +
            '               </div>' +
            '               <div class="form-group row">' +
            '                 <button type="button" class="delCustomer btn btn-danger">删除</button>' +
            '               </div>' +
            '            </div>' ;
       $(this).parent().before(fieldset);
    });

    //删除按钮
    $('.company fieldset').on('click','.delCustomer',function(){
        $(this).parents(".whole").remove();
    });

    //查询失信按钮
    $(".creditSelect").click(function(){
        if($(".creditSelect.disabled").length > 0){
            return false;
        }
        var fddbr=0,khdlr=0,zjdbr=0,zlxdr=0,zdqrr=0;
        var namelist = [];
       $(".company .whole").each(function(){
           if($(this).find('.fddbr:checked').length > 0) fddbr++;
           if($(this).find('.khdlr:checked').length > 0) khdlr++;
           if($(this).find('.zjdbr:checked').length > 0) zjdbr++;
           if($(this).find('.zlxdr:checked').length > 0) zlxdr++;
           if($(this).find('.zdqrr:checked').length > 0) zdqrr++;

           //表单验证
           var name = $(this).find('.customer_name').val();
           var zjbh = $(this).find('.customer_zjbh').val();

           if(name == '' || zjbh == ''){
               toastr.error('客户名称和证件编号不能为空');
               return false;
           }

           var regIdNo = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
           if(!regIdNo.test(zjbh)) {
               toastr.error('证件编号有误！');
               return false;
           }

           namelist.push({
               name:name,
               zjbh:zjbh,
           });
       });

        if(fddbr != 1 || khdlr != 1 || zjdbr != 1 || zlxdr != 1 || zdqrr != 1){
           toastr.error('五种客户类型必须存在，且只能存在一个');
           return false;
       }

       //查询失信
       for (var i=0;i<namelist.length;i++){
           var data = {
               tid:user_id,
               name:namelist[i].name,
               idCard:namelist[i].zjbh,
               customer_type:"个人",
               type:1
           };
           credit(data);
       }
       selectCredit(user_id);
    });

    //获取返回span
    function getSpan(type){
        if(type === '否'){
           return  '<span class="x-tag x-tag-primary x-tag-sm">否</span>';
        }else if(type === '是'){
            return '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
        }else{
            return '<span class="x-tag x-tag-danger x-tag-sm">查询错误</span>';
        }
    }
    //保存5类人
    function credit(data){
        $.ajax({
            url:'/admin/rpa_archives/credit',
            data:data,
            type:'POST',
            dataType:"json",
            success:function(_data){
            },
            error: function () {
                toastr.error('网络错误');
            }
        });
    }

    //查询失信
    function selectCredit(uid){
        var flag = 0;
        var timer = setInterval(function () {
            if(flag >= 10){
                clearInterval(timer);
            }else{
                $.ajax({
                    url:'/admin/rpa_archives/credit',
                    data:{
                        tid:uid,
                        type:2
                    },
                    type:'POST',
                    dataType:"json",
                    success:function(_data){
                        if(_data.status == 200){
                            var html = "";
                            for(let i=0;i < _data.msg.length;i++){
                                html += '<tr>' +
                                    '<td>'+_data.msg[i].name+'</td>' +
                                    '<td>'+_data.msg[i].idCard+'</td>' +
                                    '<td>'+getSpan(_data.msg[i].sfstate)+'</td>' +
                                    '<td>'+getSpan(_data.msg[i].cfastate)+'</td>' +
                                    '<td>'+getSpan(_data.msg[i].hsstate)+'</td>' +
                                    '<td>'+getSpan(_data.msg[i].xyzgstate)+'</td>' +
                                    '</tr>';
                            }
                            console.log(html);
                            $(".selecting").after(html);
                            $(".selecting").remove();
                            flag = 10;
                        }else{
                            flag++;
                            toastr.error(_data.msg);
                        }

                    },
                    error: function () {
                        flag = 10;
                        toastr.error('网络错误');
                    }
                })
            }
        },3000);
    }
});


