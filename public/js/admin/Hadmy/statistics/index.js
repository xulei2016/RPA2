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
    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            zjzh : $("#pjax-container #search-group #zjzh").val(),
            tzjh_account : $("#pjax-container #search-group #tzjh_account").val(),
            from_start_time : $("#pjax-container #search-group #startTime").val(),
            to_start_time : $("#pjax-container #search-group #endTime").val(),
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
            url: '/admin/rpa_hadmy/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'tzjh_account',
                    title: '投资江湖账号',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'online',
                    title: '是否在线',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        if(row.online){
                            result = "<span class='x-tag x-tag-success'>是</span> ";
                        }else{
                            result = "<span class='x-tag x-tag-info'>否</span> ";
                        }
                        return result;
                    }
                }, {
                    field: 'zjzh',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'single_time',
                    title: '累计登录时长(分钟)',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'single_login',
                    title: '累计登录次数',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var tzjh = row.tzjh_account;
                        var result = " <a class='btn btn-sm btn-primary'  href='/admin/rpa_hadmy/statistics/"+tzjh+"' title='查看统计'>查看统计</a>";

                        return result;
                    }
                }],
            onLoadSuccess: function(data){
               // 总
               var total_count = $(".total_count").text();
               var total_time = $(".total_time").text();
               var total_login = $(".total_login").text();

               // 搜
               var search_count = data.serach_count;
               var search_time = data.serach_time;
               var search_login = data.serach_login;


               $(".search-count").find("span").html("<b>"+search_count+"</b>/"+total_count);
               $(".search-time").find("span").html("<b>"+search_time+"</b>/"+total_time);
               $(".search-login").find("span").html("<b>"+search_login+"</b>/"+total_login);

               console.log((total_count/search_count) * 100);
               $(".search-count .progress-bar").css("width",(search_count/total_count)*100 + "%");
               $(".search-time .progress-bar").css("width",(search_time/total_time)*100 + "%");
               $(".search-login .progress-bar").css("width",(search_login/total_login)*100 + "%");
            }
            };


        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
