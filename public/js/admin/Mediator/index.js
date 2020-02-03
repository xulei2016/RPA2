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
            status : $("#pjax-container #search-group #status").val(),
            flow_status : $("#pjax-container #search-group #flow_status").val(),
            dept_id : $("#pjax-container #search-group #dept_id").val(),
            mediator : $("#pjax-container #search-group #mediator").val(),
            startTime : $("#pjax-container #search-group #startTime").val(),
            endTime : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/mediator/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'name',
                    title: '居间人姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'dept.name',
                    title: '所属部门',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'open_time',
                    title: '开户日期',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'manager_number',
                    title: '客户经理号',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'number',
                    title: '居间人编号',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'status',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row,index) {
                        var result = "";
                        if(row.status == 0){
                            result = '<span class="x-tag x-tag-sm x-tag-info">未完成</span>'
                        }else if(row.status == 1){
                            result = '<span class="x-tag x-tag-sm">正常</span>'
                        }else if(row.status == 2){
                            result = '<span class="x-tag x-tag-sm x-tag-danger">过期</span>'
                        }else if(row.status == 3){
                            result = '<span class="x-tag x-tag-sm x-tag-danger">注销</span>'
                        }
                        return result;
                    }
                }, {
                    field: 'flow.status',
                    title: '流程状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value,row,index) {
                        var result = "";
                        if(row.flow.is_check == 1){
                            if(row.flow.is_sure == 1){
                                if(row.flow.is_handle == 1){
                                    result = '<span class="x-tag x-tag-sm x-tag-info">办理完成</span>';
                                }else{
                                    result = '<span class="x-tag x-tag-sm">正在办理</span>';
                                }
                            }else if(row.flow.is_sure == -1){
                                result = '<span class="x-tag x-tag-sm x-tag-danger">拒绝比例</span>'
                            }else{
                                result = '<span class="x-tag x-tag-sm x-tag-success">已审核，未确认比例</span>'
                            }
                        }else{
                            result = '<span class="x-tag x-tag-sm x-tag-danger">待审核</span>';
                        }
                        return result;
                    }
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var id = row.id;
                        var result = "";
                        result += " <a href='javascript:;' class='btn btn-sm btn-info' onclick=\"operation($(this));\" url='/admin/mediator/info/"+id+"' title='详情'>详情</a> ";
                        result += " <a href='/admin/mediator/history/"+id+"' class='btn btn-sm btn-primary' title='履历查询'>履历查询</a>";
                        return result;
                    }
                }],
            };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
