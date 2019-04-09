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
    function change_type(id){
        Swal({
            title: "确认回访?",
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
                        url: '/admin/rpa_jjr_records/typeChange',
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
            dept : $("#pjax-container #search-group #dept").val(),
            manager : $("#pjax-container #search-group #manager").val(),
            customer : $("#pjax-container #search-group #customer").val(),
            revisit : $("#pjax-container #search-group #name").val(),
            status : $("#pjax-container #search-group #status").val(),
            from_completed_date : $("#pjax-container #search-group #startTime").val(),
            to_completed_date : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_jjr_records/rpa_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'mediatorname',
                    title: '姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'sex',
                    title: '性别',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'number',
                    title: '编号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'deptname',
                    title: '部门',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'manager_name',
                    title: '经理',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'managerNo',
                    title: '经理编号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'status',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(1 == value){
                            res = '<span class="text-primary">已完成</span>';
                        }else{
                            res = '<span class="text-danger">未回访</span>'
                        }
                        return res;
                    }
                },{
                    field: 'tel',
                    title: '电话',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'rate',
                    title: '比例(%)',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'revisit',
                    title: '回访人',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'open_date',
                    title: '开户时间',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'updatetime',
                    title: '回访时间',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #change_type":function (e, value, row, index){
                            var id = row.id;
                            change_type(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        if(row.status == 0){
                            result += '<a class="btn btn-primary btn-sm param" href="javascript:void(0);" id="change_type">回访</a>';
                        }else{
                            result += '<a class="btn btn-success btn-sm" href="javascript:void(0);" disabled>已完成</a>';
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
