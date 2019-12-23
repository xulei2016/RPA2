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
            name : $("#pjax-container #search-group #name").val(),
            zjbh : $("#pjax-container #search-group #zjbh").val(),
            from_update_at : $("#pjax-container #search-group #startTime").val(),
            to_update_at : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_archives/list',
            columns: [{
                checkbox: true,
            }, {
                field: 'name',
                title: '客户名称',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zjbh',
                title: '证件编号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'type',
                title: '客户类型',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    if('个人' == value){
                        res = '<span class="x-tag x-tag-sm">个人</span>';
                    }else{
                        res = '<span class="x-tag x-tag-sm x-tag-danger">法人</span>';
                    }
                    return res;
                }
            }, {
                field: 'btype',
                title: '业务类型',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    res = '<span class="x-tag x-tag-sm">'+value+'</span>';

                    return res;
                }
            }, {
                field: 'step',
                title: '步骤',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    if(value < 5){
                        res = '<span class="x-tag x-tag-sm x-tag-danger">未完成</span>';
                    }else if(value == 5){
                        res = '<span class="x-tag x-tag-sm">待归档</span>';
                    }else{
                        res = '<span class="x-tag x-tag-sm x-tag-success">已归档</span>';
                    }
                    return res;
                }
            }, {
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    var id = value;
                    var result = "";
                    if(row.step < 5){
                        result += "<a href='/admin/rpa_archives/"+id+"/edit' class='btn btn-sm btn-primary' title='继续'>继续</a>";
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
