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
            reviewPeopName : $("#pjax-container #search-group #name").val(),
            from_openingTime : $("#pjax-container #search-group #startTime").val(),
            to_openingTime : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_cloud_distribution/report_list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'reviewPeopName',
                    title: '回访人',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'must',
                    title: '应回访人数',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'success',
                    title: '已回访人数',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'error',
                    title: '回访失败人数',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'row',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        var d = row.success - row.must;
                        if(d >= 0){
                            result += "<span class='x-tag x-tag-sm x-tag-success'>达标</span>";
                        }else{
                            result += "<span class='x-tag x-tag-sm x-tag-danger'>未达标</span>";}
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
