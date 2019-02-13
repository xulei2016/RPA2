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
        //批量删除
        $("#pjax-container section.content #toolbar #deleteAll").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
        	}else{
                Delete(ids);
            }
        });
    }

    /**
     * 删除单条记录
     */
    function Delete(id){
        Swal({
            title: "确认删除?",
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
                        url: '/admin/rpa_rtc_collect/'+id,
                        data: {
                            _method:'delete',
                            _token:LA.token,
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
            url: '/admin/rpa_rtc_collect/list',
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
                }, {
                    field: 'date',
                    title: '类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        return row.date ? '<span class="text-primary">一次性任务</span>' : '<span class="text-success">循环执行</span>' ;
                    }
                }, {
                    field: 'time',
                    title: '执行时间',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'created_at',
                    title: '创建时间',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        result += " <a href='javascript:;' class='btn btn-xs btn-warning' onclick=\"operation($(this));\" url='/admin/rpa_rtc_collect/immedtasks/"+id+"' title='添加立即任务'><span class='glyphicon glyphicon-plus'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-info' onclick=\"operation($(this));\" url='/admin/rpa_rtc_collect/"+id+"' title='查看参数'><span class='glyphicon glyphicon-search'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_rtc_collect/"+id+"/edit' title='编辑'><span class='glyphicon glyphicon-pencil'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-danger' id='deleteOne' title='删除'><span class='glyphicon glyphicon-remove'></span></a>";

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
