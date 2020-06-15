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
            customer : $("#pjax-container #search-group #customer").val(),
            tid : $("#pjax-container #search-group #tid").val(),
            state : $("#pjax-container #search-group #state").val(),
            blancenum : $("#pjax-container #search-group #blancenum").val(),
            from_created_at : $("#pjax-container #search-group #startTime").val(),
            to_created_at : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_customer_funds_search/rpa_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'name',
                    title: '姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'khh',
                    title: '客户号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'capital_name',
                    title: '投资类型',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'capital_exfund',
                    title: '达标资金',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'state',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    formatter: function(value, row, index){
                        let res = "";
                        if(1 == value){
                            res = '<span class="x-tag x-tag-sm">已达标</span>';
                        }else if(2 == value){
                            res = '<span class="x-tag x-tag-sm x-tag-success">已归档</span>';
                        }else if(-1 == value){
                            res = '<span class="x-tag x-tag-sm x-tag-danger">客户不存在</span>';
                        }else{
                            res = '<span class="x-tag x-tag-sm x-tag-danger">未达标</span>'
                        }
                        return res;
                    }
                }, {
                    field: 'day_one',
                    title: '第一天',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.day_one+" -<br/>"+row.blance_one
                    }
                }, {
                    field: 'day_two',
                    title: '第二天',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.day_two+" -<br/>"+row.blance_two
                    }
                }, {
                    field: 'day_three',
                    title: '第三天',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.day_three+" -<br/>"+row.blance_three
                    }
                }, {
                    field: 'day_four',
                    title: '第四天',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.day_four+" -<br/>"+row.blance_four
                    }
                }, {
                    field: 'day_five',
                    title: '第五天',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.day_five+" -<br/>"+row.blance_five
                    }
                },{
                    field: 'updatetime',
                    title: '归档时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #change_type":function (e, value, row, index){
                            var id = row.id;
                            change_type(id);

                        },
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        if(row.state == 1){
                            result += '<a class="btn btn-primary btn-sm param" href="javascript:void(0);" id="change_type">归档</a><br/>';
                        }
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
