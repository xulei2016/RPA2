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

        //批量删除
        $("#pjax-container section.content #toolbar #deleteAll").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
        	}else{
                Delete(ids);
            }
        });

        //导出全部
        $("#pjax-container section.content #toolbar #exportAll").on('click', function(){
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/rpa_botton/export?"+$url;
        });

        //导出全选
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/rpa_botton/export?"+$url+'&id='+ids;
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
                        url: '/admin/rpa_botton/'+id,
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
        }

        var param = {
            url: '/admin/rpa_botton/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'name',
                    title: '任务名称',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'bewrite',
                    title: '任务描述',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'filepath',
                    title: '任务路径',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'isfp',
                    title: '是否占用资源',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(values){
                        return values ? '<small class="label bg-red">是</small>' : '<small class="label bg-primary">否</small>';
                    }
                }, {
                    field: 'failtimes',
                    title: '失败次数限制',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'timeout',
                    title: '超时限制',
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
                        if(1 == id)return result;
                        result += " <a href='javascript:;' class='btn btn-xs btn-warning' onclick=\"operation($(this));\" url='/admin/rpa_botton/"+id+"/edit' title='编辑'><span class='glyphicon glyphicon-pencil'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-danger' id='deleteOne' title='删除'><span class='glyphicon glyphicon-remove'></span></a>";

                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
