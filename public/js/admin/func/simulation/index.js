
$(function(){
    let selectInfo = [];
    let url_prefix = "/admin/rpa_simulation_account_business/";
    /*
     * 初始化
     */
    function init(){
        bindEvent();

        //1.初始化Table
        var oTable = new RPA.TableInit();
        pageNation(oTable);
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        let nowDate = getFormatDate();
        //定义时间按钮事件
        let st = '#pjax-container #search-group #startTime';
        let et = '#pjax-container #search-group #endTime';
        laydate.render({
            elem: st, type: 'date', max: nowDate, done: function (value, date, endDate) {
                laydate.render({ elem: et, type: 'date', show: true, min: value, max: nowDate });
            }
        });
        laydate.render({ elem: et, type: 'date', max: nowDate });

        
        

        //根据条件查询信息
        $('#pjax-container #search-group #formSearch #search-btn').click(function() {
            $('#tb_departments').bootstrapTable('refreshOptions',{pageNumber:1});
        });
        //enter键盘事件
        $("#pjax-container #search-group #formSearch input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                $('#tb_departments').bootstrapTable('refresh');
            }
        });

        //导出全部
        $("#pjax-container section.content #toolbar #exportAll").on('click', function(){
            location.href= url_prefix + "export?condition=all"
        });

        //导出筛选
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= url_prefix + "export?"+$url+"&condition=where";
        });

        //导出当日
        $("#pjax-container section.content #toolbar #exportCurrent").on('click', function(){
            location.href= url_prefix + "export?condition=current";
        });

        //一键发送短信
        $("#pjax-container section.content #sendAll").on('click', function(){
            $.ajax({
                url:'/admin/rpa_simulation_account_business/sendAll',
                data:{method:'post'},
                type:'post',
                dataType:'json',
                success:function (r) {
                    if(r.code == 200) {
                        toastr.success("短信发送成功,应发短信"+r.data.count+"条,成功发送"+r.data.successCount+"条,失败短信"+r.data.failureCount+"条");
                        location.reload();
                    } else {
                        toastr.error(r.info);
                    }
                }
            });
        });

    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            startTime : $("#pjax-container #search-group #startTime").val(),
            endTime : $("#pjax-container #search-group #endTime").val(),
            name : $("#pjax-container #search-group #name").val(),
            isCtp : $("#pjax-container #search-group #isCtp").val()
        };
        return temp;
    }
    
    function fp(id) {
        $.ajax({
            url:'/admin/rpa_simulation_account_business/ctp',
            data:{id:id},
            type:'post',
            dataType:'json',
            success:function(r) {
                if(r.code != 200) {
                    swal(r.info, '', 'error');
                    return false;
                } else {
                    swal('分配成功', '', 'success');
                    $.pjax.reload('#pjax-container');
                }
            },
            error:function() {
                swal('分配失败', '', 'error');
                return false;
            }
        });
    }

    function sendErr(id) {
        $.ajax({
            url:'/admin/rpa_simulation_account_business/sendErr',
            data:{id:id},
            type:'post',
            dataType:'json',
            success:function(r) {
                if(r.code != 200) {
                    swal(r.info, '', 'error');
                    return false;
                } else {
                    swal('发送成功', '', 'success');
                    $.pjax.reload('#pjax-container');
                }
            },
            error:function() {
                swal('发送失败', '', 'error');
                return false;
            }
        });
    }

    function zjzh(row) {
        swal({
            title: '请为客户('+row.name+')分配资金账号(开发中.....)',
            type: 'info',
            html: '<div class="text-center">'+
            '<input style="width: 70%;display:inline-block;margin-top:6px;" type="text" id="zjzh" class="form-control" placeholder="资金账号">'+
            '</div>',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#3fc3ee',
            cancelButtonColor: 'gray',
            confirmButtonText: '确认',
            cancelButtonText: ' 取消'
        }).then(function (res) {
            if(res.value) {
               var zjzh = $('#zjzh').val();
               if(!zjzh) {
                    swal('资金账号必填','', 'error');
                    return false;
               }  
               $.ajax({
                   url:url_prefix + 'setZjzh',
                   data:{id:row.id, zjzh:zjzh},
                   type:'post',
                   dataType:'json',
                   success:function(r){
                        if(r.code == 200) {
                            swal('成功', '', 'success');
                            $('#tb_departments').bootstrapTable('refresh');
                            
                        } else {
                            swal(r.info, '', 'error');
                            return false;
                        }
                   }
               })
            }

        });
    }


    //分页参数
    function pageNation(oTable){
        oTable.queryParams = function (params) {
            //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
            var temp = $("#pjax-container #search-group").serializeJsonObject();
            temp["rows"] = params.limit;                        //页面大小
            temp["total"] = params.total;                        //页面大小
            temp["page"] = (params.offset / params.limit) + 1;  //页码
            temp["sort"] = params.sort;                         //排序列名
            temp["sortOrder"] = params.order;                   //排位命令（desc，asc）
            //特殊格式的条件处理
            let obj = getSearchGroup();
            for(let i in obj){
                temp[i] = obj[i];
            }
            return temp;
        }



        var param = {
            url: url_prefix+'list',
            columns: [{
                checkbox: true,
            }, {
                field: 'name',
                title: '姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'sfz',
                title: '身份证',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'phone',
                title: '电话',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zjzh',
                title: '资金账号',
                align: 'center',
                valign: 'middle',
                formatter:function(v) {
                    if(!v) {
                        return "<button class='btn btn-primary btn-sm zjzh'>手动添加</button>"
                    } else {
                        return v;
                    }
                },
                events: {
                    "click .zjzh":function (e, value, row, index){
                        zjzh(row);
                    }
                },
            }, {
                field: 'address',
                title: '地址',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'errorMessage',
                title: '错误信息',
                align: 'center',
                valign: 'middle',
                formatter:function(v){
                    if(v){
                        return "<span class='text text-danger'>"+v+"</span>"; 
                    }   
                },
            }, {
                field: 'isSendErr',
                title: '发送错误短信',
                align: 'center',
                valign: 'middle',
                formatter:function(v,row){
                    var result = "";
                    if(v == 2) {
                        result += ' <span class="text text-success">已发送</span>';
                    }else if(v == 1){
                        result += "<button class='btn btn-primary btn-sm sendErr'>发送短信</button>";
                    }else {
                        result += ' <span class="text text-info">无需发送</span>' 
                    }
                    return result;
                },
                events: {
                    "click .sendErr":function (e, value, row, index){
                        var id = row.id;
                        sendErr(id);
                    }
                },
            }, {
                field: 'created_at',
                title: '创建时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'isCtp',
                title: '穿透状态',
                align: 'center',
                valign: 'middle',
                formatter:function(v){
                    if(v == 2) {
                        return "仅CTP穿透(已分配)";
                    } else if(v == 1) {
                        return "仅CTP穿透 <button class='btn btn-primary btn-sm fp'>分配</button>";
                    } else {
                        return "无";
                    }
                },
                events: {
                    "click .fp":function (e, value, row, index){
                        var id = row.id;
                        fp(id);
                    }
                },
            }
        ],
        }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
