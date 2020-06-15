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
            $('#tb_departments').bootstrapTable('refreshOptions',{pageNumber:1});
        });
        //enter键盘事件
        $("#pjax-container #search-group #formSearch input").keydown(function(event){
            event = event ? event: window.event;
            if(event.keyCode == 13){
                $('#tb_departments').bootstrapTable('refresh');
            }
        });
        //批量删除
        $("#pjax-container section.content #toolbar #deleteAll").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
            }else{
                Delete(ids);
            }
        });
        //全部已读
        $("#pjax-container #readAll").on('click', function(){
            Swal({
                title: "是否将所有信息标记为已读",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
            }).then(function(res) {
                if(res.value) {
                    $.get('/admin/sys_message_list/readAllMessage', function(res){
                        if(res.code == 200) {
                            Swal('操作成功', '', 'success');
                        }
                    })
                }
            });
        })
    }

    /**
     * 归档
     */
    function change_type(id){
        Swal({
            title: "确认归档?",
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
                        url: '/admin/rpa_customer_funds_search/typeChange',
                        data: {
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
                        url: '/admin/rpa_customer_funds_search/delete',
                        data: {
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
            title : $("#pjax-container #search-group #title").val(),
            from_add_time : $("#pjax-container #search-group #startTime").val(),
            to_add_time : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/sys_message_list/message_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'data',
                    title: '标题',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                       return value?value.title:'-';
                    }

                }, {
                    field: 'data',
                    title: '消息类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(!value) return '-';
                        if(1 == value.typeName){
                            res = '<span class="x-tag x-tag-sm">系统公告</span>';
                        }else if(2 == value.typeName){
                            res = '<span class="x-tag x-tag-sm">RPA通知</span>';
                        }else{
                            res = '<span class="x-tag x-tag-sm">管理员通知</span>';
                        }
                        return res;
                    }
                }, {
                    field: 'read_at',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(value){
                            res = '<span class="x-tag x-tag-sm x-tag-success">已读</span>';
                        }else{
                            res = '<span class="x-tag x-tag-sm x-tag-danger">未读</span>';
                        }
                        return res;
                    }
                },{
                    field: 'created_at',
                    title: '创建时间',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'updated_at',
                    title: '更新时间',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        if(!row.read_at){
                            result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/sys_message_list/view/"+id+"' title='查看'>查看</a>";
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
