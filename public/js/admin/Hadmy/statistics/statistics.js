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
            tzjh_account : $("#pjax-container #search-group #tzjh_account").val(),
            ip : $("#pjax-container #search-group #ip").val(),
            mac : $("#pjax-container #search-group #mac").val(),
            version : $("#pjax-container #search-group #version").val(),
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
            url: '/admin/rpa_hadmy/single_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'tzjh_account',
                    title: '投资江湖账号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'zjzh',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'ip',
                    title: '本地IP',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'ip2',
                    title: '互联网IP',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'mac',
                    title: 'MAC',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'start_time',
                    title: '开始时间',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'end_time',
                    title: '结束时间',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'count_time',
                    title: '登录时长',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'version',
                    title: '使用版本',
                    align: 'center',
                    valign: 'middle'
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
