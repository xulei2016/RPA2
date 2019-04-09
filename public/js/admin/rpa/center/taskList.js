$(function(){
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
     * 删除单条记录
     */
    function immedtask(id){
        Swal({
            title: "确认立即运行?",
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
                        url: '/admin/rpa_center/immedtask',
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
            return temp;
        }

        var param = {
            url: '/admin/rpa_center/rpa_taskList',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'name',
                    title: '任务名称',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'description',
                    title: '任务描述',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'week',
                    title: '日期',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.date ? row.date : get_week(row.week);
                    }
                },{
                    field: 'jsondata',
                    title: '参数',
                    align: 'center',
                    valign: 'middle'
                },  {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #immedtask":function (e, value, row, index){
                            var id = row.id;
                            immedtask(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        result += " <a href='javascript:;' class='btn btn-xs btn-warning' id='immedtask' title='立即运行'>立即运行</a>";

                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }
    //get_week
    function get_week(week){
        let s = '每周';
        let w = week.split(',');
        for(let i = 0;i<w.length;i++){
            switch(w[i]){
                case "0":
                    s = s+'日,';
                    break;
                case "1":
                    s = s+'一,';
                    break;
                case "2":
                    s = s+'二,';
                    break;
                case "3":
                    s = s+'三,';
                    break;
                case "4":
                    s = s+'四,';
                    break;
                case "5":
                    s = s+'五,';
                    break;
                case "6":
                    s = s+'六,';
                    break;
            }
        }
        return s;
    }
    init();
});
