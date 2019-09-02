$(function(){
    let url_prefix = '/admin/sys_call_center_record_detail/';
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
        $("#toolbar").hide();
    }
    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            record_id: $('input#record_id').val(),
        };
        return temp;
    }
    //分页参数
    function pageNation(oTable){
        oTable.queryParams = function (params) {
            //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
            var temp = {};
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
            url: url_prefix + 'list',
            columns: [{
                checkbox: true,
            }, {
                field: 'realName',
                title: '客服',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'name',
                title: '客户',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'content',
                title: '内容',
                align: 'center',
                valign: 'middle',
                formatter: function(values){
                    if(values.indexOf('img') > -1) {
                        return '内容过长';
                    }
                    if(values.indexOf('img') > -1) {
                        return "内容过长";
                    }
                    if(values.length > 30) {
                        values = values.substr(0,27) ;
                        values = values.toString()+"...";
                    }
                    return values;
                }
            }, {
                field: 'created_at',
                title: '开始时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'sender',
                title: '发送者',
                align: 'center',
                valign: 'middle'
            }]
        }
        //初始化表格
        oTable.Init('#record_detail', param);
    }

    init();
});
