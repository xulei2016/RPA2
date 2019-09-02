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
                        url: '/admin/rpa_customer/delete',
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
            manager : $("#pjax-container #search-group #manager").val(),
            mediator : $("#pjax-container #search-group #mediator").val(),
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
            url: '/admin/rpa_customer/list',
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
                }, {
                    field: 'customerNum',
                    title: '经理人工号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'customerManagerName',
                    title: '经理人名称',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'jjrName',
                    title: '居间人姓名',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'yybName',
                    title: '营业部',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'message',
                    title: '备注',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'special',
                    title: '特殊',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        if(value){
                            result = value.replace("1",'仅账户激活');
                            result = result.replace("2",'仅账户更新');
                            result = result.replace("3",'仅二次金融');
                            result = result.replace("4",'仅二次能源');
                        }
                        return result;
                    }
                }, {
                    field: 'add_time',
                    title: '添加时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'creater',
                    title: '操作人',
                    align: 'center',
                    valign: 'middle'
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
                        var result = "<a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</a>";
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});