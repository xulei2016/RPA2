$(function(){
    var totalNumber = 100;
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
            jys : $("#pjax-container #search-group #jys").val(),
            hydm : $("#pjax-container #search-group #hydm").val(),
            type : $("#pjax-container #search-group #type").val()
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
        };
        var param = {
            url: '/admin/rpa_bzj/list',
            columns: [{
                checkbox: true
            },{
                field: 'ID',
                title: 'ID',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'JYS',
                title: '交易所',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'TJBZJ_AJE',
                title: '投机保证金 按金额',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'TBBZJ_AJE',
                title: '保值保证金 按金额',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'TJBZJ_AS',
                title: '投机保证金 按手数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'TBBZJ_AS',
                title: '保值保证金 按手数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'HYDM',
                title: '合约代码',
                align: 'center',
                valign: 'middle'
            }]}

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
