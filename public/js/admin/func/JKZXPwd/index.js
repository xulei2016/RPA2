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
            ids = ids.join(",");
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
            }else{
                Delete(ids);
            }
        });

        //一键发送功能
        $("#yjsend").click(function(){
            var ids = RPA.getIdSelections('#tb_departments');
            ids = ids.join(",");
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
            }else{
                yjsend(ids);
            }
        })
    }

    function yjsend(ids){
        Swal({
            title: "确认发送?",
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
                        url: '/admin/rpa_jkzxPwd/yjsend',
                        data: {
                            ids:ids
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
                        url: '/admin/rpa_jkzxPwd/'+id,
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
            customer : $("#pjax-container #search-group #customer").val(),
            type : $("#pjax-container #search-group #type").val(),
            status : $("#pjax-container #search-group #status").val(),
            from_inputtime : $("#pjax-container #search-group #startTime").val(),
            to_inputtime : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_jkzxPwd/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'name',
                    title: '姓名',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let result = value;
                        if(row.has_jybm == '0'){
                            result = "<span class='x-tag x-tag-sm x-tag-danger'>"+value+"</span>"
                        }
                        return result;
                    }
                }, {
                    field: 'type',
                    title: '客户类型',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'tel',
                    title: '电话号码',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return value.substring(0, 3)+"****"+value.substr(value.length-4);
                    }
                }, {
                    field: 'account',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'pwd',
                    title: '账号密码',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return value.substring(0, 3)+"****"+value.substr(value.length-4);
                    }
                }, {
                    field: 'status',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    formatter: function(value, row, index){
                        let res = "";
                        if(1 == value){
                            res = '<span class="x-tag x-tag-sm x-tag-success">已发送</span>';
                        }else{
                            res = '<span class="x-tag x-tag-sm x-tag-danger">未发送</span>'
                        }
                        return res;
                    }
                }, {
                    field: 'inputtime',
                    title: '录入时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    formatter: function(value, row, index){
                        let date = new Date(value*1000);
                        let Y = date.getFullYear();
                        let m = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1);
                        let d = date.getDate();
                        return Y+"-"+m+"-"+d;
                    }
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var result = "";
                        var id = row.id;
                        if(row.status == 1){
                            result += "<a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"operation($(this));\" url='/admin/rpa_jkzxPwd/resend/"+id+"' title='重新发送'>重新发送</a>";
                        }else{
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_jkzxPwd/send/"+id+"' title='发送'>发送</a>";
                        }
                        result += " <a href='javascript:;' class='btn btn-sm btn-info' onclick=\"operation($(this));\" url='/admin/rpa_jkzxPwd/"+id+"/edit' title='编辑'>编辑</a>";
                        result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</a>";
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
