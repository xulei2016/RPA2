$(function(){
    let url_prefix = '/admin/sys_call_center_record/';
    let detail_url_prefix = '/admin/sys_call_center_record_detail/';
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

        $("#manager_id").select2({
            "allowClear":true,
            "placeholder":"客服",
        });

        $("#customer_id").select2({
            "allowClear":true,
            "placeholder":"客户",
        });
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

        //导出全部
        $("#pjax-container section.content #toolbar #exportAll").on('click', function(){
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= url_prefix + "export?"+$url;
        });

        //导出选中
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= url_prefix + "export?"+$url+'&id='+ids;
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
                        url: url_prefix + id,
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

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            manager_id : $("#pjax-container #search-group #manager_id").val(),
            customer_id : $("#pjax-container #search-group #customer_id").val()
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
                field: 'created_at',
                title: '开始时间',
                align: 'center',
                valign: 'middle'
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
                    let url = detail_url_prefix+"?record_id="+row.id;
                    result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='"+url+"' title='查看'>查看</a>";
                    return result;
                }
            }],
        }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
