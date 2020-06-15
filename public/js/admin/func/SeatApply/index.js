$(function(){
    let selectInfo = [];
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
            $('#tb_departments').bootstrapTable('refresh');
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
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            //location.href="/admin/rpa_seat_apply/export?"+$url;
            window.open("/admin/rpa_seat_apply/export?"+$url);
        });

        //导出选中
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            //location.href="/admin/rpa_seat_apply/export?id="+ids;
            window.open("/admin/rpa_seat_apply/export?id="+ids);
        });

        //删除选中
        $("#pjax-container section.content #toolbar #delete").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
            }else{
                Delete(ids);
            }
        });
    }


     /**
         * 删除单条记录
         */
        function Delete(id){
            Swal({
                title: "确认删除?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/rpa_seat_apply/'+id,
                            data: {
                                _method:'delete',
                                _token:LA.token,
                                id:id
                            },
                            success: function (json) {
                                if(200 == json.code){
                                    $.pjax.reload('#pjax-container');
                                    resolve(json);
                                }else{
                                    reject(json.info);
                                }
                            }
                        });
                    });
                }
            }).then(function(json) {
                var json = json.value;
                Swal(json.info, '', 'success');
            },function(dismiss){
                Swal(dismiss, '', 'error');
            });
        }



    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            user : $("#pjax-container #search-group #user").val(),
            business_type : $("#pjax-container #search-group #type").val(),
            status : $("#pjax-container #search-group #status").val()
        };
        return temp;
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
            url: '/admin/rpa_seat_apply/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'fundsNum',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'name',
                    title: '客户姓名',
                    align: 'center',
                    valign: 'middle'
                }, 

                {
                    field: 'business_type',
                    title: '业务类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(1 == value){
                            res = '<span class="">开通</span>';
                        }else if(2 == value){
                            res = '<span class="x-tag-warning">取消</span>';
                        }
                        return res;
                    }
                }, 
                 {
                    field: 'counter_type',
                    title: '柜台类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        if(value){
                            result = value.replace("1",'CTP');
                            result = result.replace("2",'易盛');
    
                        }
                        return result;
                    }
                },
 
                {
                    field: 'type',
                    title: '类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        if(value){
                            result = value.replace("1",'张江9.0');
                            result = result.replace("2",'郑州9.0');
    
                        }
                        return result;
                    }
                }, 
                 {
                    field: 'created_at',
                    title: '提交时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                },
                {
                    field: 'status',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    formatter: function(value, row, index){
                        let res = "";
                        if(0 == value){
                            res = '<span class="x-tag x-tag-sm x-tag-success">已办理</span>';
                        }else if(1 == value){
                            res = '<span class="x-tag x-tag-sm">办理中</span>';
                        }else if(2 == value){
                            res = '<span class="x-tag x-tag-sm x-tag-danger">失败</span>';
                        }else{
                            res = '<span class="x-tag x-tag-sm x-tag-info">待办理</span>';
                        }
                        return res;
                    }
                }, 
                {
                    field: 'reason',
                    title: '备注',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(value && value.length){
                            let reasons = value.split(',');
                            reasons.forEach((item,index,array)=>{
                                res += '<span>'+item+'</span><br/>';
                            })
                        }

                        return res;
                    }
                }, {
                    field: 'audit_user',
                    title: '审核人',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'deal_user',
                    title: '办理人',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #doneOne":function (e, value, row, index){
                            var id = row.id;
                            Done(id);
                        },
                        "click #refuseOne":function (e, value, row, index){
                            var id = row.id;
                            Refuse(id);
                        },

                        
                    },
                    formatter: function(value, row, index){
                        var id = row.id;
                        var result = "";
                        let status = row.status;
                        if(status == 1){
                            result += " <a href='javascript:;' class='btn btn-sm btn-success' onclick=\"operation($(this));\" url='/admin/rpa_seat_apply/"+id+"' title='办理结果'>办理结果</a>";
                        }
                        if(status == 3){
                            result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_seat_apply/"+id+"' title='办理'>审核</a>";
                        }
                        
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
