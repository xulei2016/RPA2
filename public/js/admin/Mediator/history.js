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
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            uid : $("#pjax-container #search-group #uid").val(),
            // customer : $("#pjax-container #search-group #customer").val(),
            // revisit : $("#pjax-container #search-group #name").val(),
            // status : $("#pjax-container #search-group #status").val(),
            // from_updatetime : $("#pjax-container #search-group #startTime").val(),
            // to_updatetime : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/mediator/history_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'dept.name',
                    title: '部门',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'type',
                    title: '类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row,index) {
                        var result = "";
                        if(value == 0){
                            result = '<span class="x-tag x-tag-sm x-tag-success">新签</span>'
                        }else if(value == 1){
                            result = '<span class="x-tag x-tag-sm">续签</span>'
                        }else{
                            result = '<span class="x-tag x-tag-sm x-tag-danger">注销</span>'
                        }
                        return result;
                    }
                },{
                    field: 'xy_date_start',
                    title: '协议开始日期',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'xy_date_end',
                    title: '协议到期日',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'part_b_date',
                    title: '申请时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'rate',
                    title: '比例(%)',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'flow_status',
                    title: '流程状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row,index) {
                        var result = "";
                        if(row.is_check == 1){
                            if(row.is_sure == 1){
                                if(row.is_handle == 1){
                                    result = '<span class="x-tag x-tag-sm x-tag-info">办理完成</span>';
                                }else{
                                    result = '<span class="x-tag x-tag-sm">正在办理</span>';
                                }
                            }else{
                                result = '<span class="x-tag x-tag-sm x-tag-success">已审核，未确认比例</span>'
                            }
                        }else{
                            if(row.part_b_date){
                                result = '<span class="x-tag x-tag-sm x-tag-danger">待审核</span>';
                            }else{
                                result = '<span class="x-tag x-tag-sm x-tag-info">未完成</span>';
                            }
                        }
                        return result;
                    }
                }, {
                    field: 'remark',
                    title: '备注',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var id = row.id;
                        var result = "";
                        result += " <a href='javascript:;' class='btn btn-sm btn-info' onclick=\"operation($(this));\" url='/admin/mediator/flow_info/"+id+"' title='详情'>详情</a>";
                        if(row.is_check == 0 && row.part_b_date != ''){
                            result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/mediator/check/"+id+"' title='审核'>审核</a>";
                        }
                        return result;
                    }
                }],
            };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
