$(function(){
    let modal = '#wizard';
    let result = true;
    let user_id = (typeof uid != 'undefined') ? uid : '';
    $("#wizard").steps({
        headerTag: "h2",// 指定头部对应什么HTML标签
        bodyTag: "section",// 指定步骤内容对应的HTML标签
        stepsOrientation: 0, // 指定步骤为水平--vertical（垂直） horizontal（水平）
        transitionEffect: "slideLeft", // 步骤切换动画
        // forceMoveForward: true, // 防止跳转到上一步
        startIndex: step-1,
        labels: {
            finish: "完成", // 修改按钮得文本
            next: "下一步", // 下一步按钮的文本
            previous: "上一步", // 上一步按钮的文本
            loading: "Loading ...",
        },
        onStepChanging: function (event, currentIndex, newIndex) {// 下一步切换时的监听
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

                if(customer_name == '' || customer_zjbh == ''){
                    toastr.error('客户名称和证件编号不能为空');
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
                        business_type:business_type
                    },
                    dataType:'json',
                    success: function (json) {
                        if(json.status == 200){
                            text = "视频审核通过,请直接点击下一步！";
                            $('.checkVideo i').removeClass().addClass('fa fa-check-circle-o');
                            $('.checkVideo i').css('color','green');
                            result = true;
                        }else if(json.status == 501){
                            var id = json.msg;
                            text = "审批还未审核，请<a href='javascript:;' style='margin-left: 20px;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_customer_video_collect/"+id+"/edit' title='点击审核'>点击审核</a>"
                            $('.checkVideo i').removeClass().addClass('fa fa-exclamation-circle');
                            $('.checkVideo i').css('color','orange');
                            result = false;
                        }else{
                            text = "未上传视频，请先上传!";
                            $('.checkVideo i').removeClass().addClass('fa fa-times-circle-o');
                            $('.checkVideo i').css('color','red');
                            result = false;
                        }
                        $('.checkVideo span').html(text);
                    }
                });
            }
            //下一步是失信查询
            else if(newIndex == 2){
                // if(!result) return result;

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

                //失信查询
                var flag = false;
                $.ajax({
                    url:'/admin/rpa_archives/credit',
                    data:{
                        name:customer_name,
                        idCard:customer_zjbh,
                        type:1
                    },
                    type:'POST',
                    dataType:"json",
                    success:function(_data){

                        $(".credit table tbody tr.selecting td").html('正在查询...');

                        if(_data.status == 200){
                            var timer = setInterval(function () {
                                if(flag){
                                    clearInterval(timer);
                                }else{
                                    $.ajax({
                                        url:'/admin/rpa_archives/credit',
                                        data:{
                                            name:customer_name,
                                            idCard:customer_zjbh,
                                            type:2
                                        },
                                        type:'POST',
                                        dataType:"json",
                                        success:function(_data){
                                            if(_data.status == 200){
                                                var zq = '';
                                                var qh = '';
                                                var hs = '';
                                                //证券
                                                if(_data.zq == 1){
                                                    result = false;
                                                    zq = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                                }else{
                                                    zq = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                                }
                                                //期货
                                                if(_data.qh == 1){
                                                    result = false;
                                                    qh = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                                }else{
                                                    qh = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                                }
                                                //恒生黑名单
                                                if(_data.hs == 1){
                                                    result = false;
                                                    hs = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                                }else{
                                                    hs = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                                }

                                                var html = '<tr>' +
                                                    '<td>'+customer_name+'</td>' +
                                                    '<td>'+customer_zjbh+'</td>' +
                                                    '<td>'+zq+'</td>' +
                                                    '<td>'+qh+'</td>' +
                                                    '<td>'+hs+'</td>' +
                                                    '</tr>';
                                                $(".selecting").after(html);
                                                $(".selecting").remove();
                                                flag = true
                                            }else{
                                                toastr.error(_data.msg);
                                            }
                                        },
                                        error: function () {
                                            flag = true;
                                            toastr.error('网络错误');
                                        }
                                    })
                                }
                            },3000);
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
                    dataType:"json"
                });

                //下一步判断
                var business_type = $('#business_type').val();
                if(business_type == '适当性权限申请'){
                    //下一步是适当性
                    //发送请求，查询适当性情况
                }else if(business_type == '激活'){
                    // 下一步是音频上传
                    // 音频上传
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
            }else{
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


    // 附件上传
    $('#file-0a').fileinput({
        theme: 'fa',
        language: 'zh',
        minFileCount: 1,
        allowedFileExtensions : [ 'jpg' ,'jpeg', 'gif', 'png', 'bmp' ],
        uploadUrl: '/admin/rpa_archives/uploadEnclosure',
        uploadAsync: true,   //异步上传
        uploadExtraData: {    //上传额外数据
            id: user_id,
        },
    });

    // 音频上传
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

            namelist.push({
                name:$(this).find('.customer_name').val(),
                zjbh:$(this).find('.customer_zjbh').val(),
            });
        });

        console.log(namelist);
        if(fddbr != 1 || khdlr != 1 || zjdbr != 1 || zlxdr != 1 || zdqrr != 1){
            toastr.error('五种客户类型必须存在，且只能存在一个');
            return false;
        }

        //查询失信
        for (var i=0;i<namelist.length;i++){

            var data1 = {
                name:namelist[i].name,
                idCard:namelist[i].zjbh,
                type:1
            };
            var data2 = {
                name:namelist[i].name,
                idCard:namelist[i].zjbh,
                type:2
            };

            credit(data1,data2);
        }
    });

    //失信查询
    function credit(data1,data2){
        var flag = false;
        $.ajax({
            url:'/admin/rpa_archives/credit',
            data:data1,
            type:'POST',
            dataType:"json",
            success:function(_data){

                $(".creditSelect").text('正在查询...');
                $(".creditSelect").addClass('disabled');

                if(_data.status == 200){
                    var timer = setInterval(function () {
                        if(flag){
                            clearInterval(timer);
                        }else{
                            $.ajax({
                                url:'/admin/rpa_archives/credit',
                                data:data2,
                                type:'POST',
                                dataType:"json",
                                success:function(_data){
                                    if(_data.status == 200){
                                        var zq = '';
                                        var qh = '';
                                        var hs = '';
                                        //证券
                                        if(_data.zq == 1){
                                            zq = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            zq = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }
                                        //期货
                                        if(_data.qh == 1){
                                            qh = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            qh = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }
                                        //恒生黑名单
                                        if(_data.hs == 1){
                                            hs = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            hs = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }

                                        var html = '<tr>' +
                                            '<td>'+data1.name+'</td>' +
                                            '<td>'+data1.idCard+'</td>' +
                                            '<td>'+zq+'</td>' +
                                            '<td>'+qh+'</td>' +
                                            '<td>'+hs+'</td>' +
                                            '</tr>';
                                        $(".company table tbody").append(html);
                                        $(".creditSelect").text('查询失信记录');
                                        $(".creditSelect").removeClass('disabled');

                                        flag = true
                                    }else{
                                        toastr.error(_data.msg);
                                    }
                                },
                                error: function () {
                                    flag = true;
                                    toastr.error('网络错误');
                                }
                            })
                        }
                    },3000);
                }else{
                    toastr.error(_data.msg);
                }
            },
            error: function () {
                toastr.error('网络错误');
            }
        });
    }
});


