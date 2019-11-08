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
        laydate.render({ elem: '#pjax-container #search-group #startTime', type: 'datetime' });
        laydate.render({ elem: '#pjax-container #search-group #endTime', type: 'datetime' });

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
            location.href="/admin/rpa_customer_video_collect/export?"+$url;
        });

        //导出选中
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/rpa_customer_video_collect/export?"+$url+'&id='+ids;
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
                $.post('/admin/rpa_customer_video_collect/'+id,{
                    _method:'delete',
                    id:id
                },function (json) {
                    if(200 == json.code){
                        $.pjax.reload('#pjax-container');
                        Swal(json.info, '', 'success');
                    }else{
                        Swal(json.info, '', 'error');
                    }
                });
            }
        });
    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            status : $("#pjax-container #search-group #status").val(),
            from_created_at : $("#pjax-container #search-group #startTime").val(),
            to_created_at : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_customer_video_collect/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'yyb',
                    title: '营业部',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'jlr_name',
                    title: '客户经理',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'jlr_bh',
                    title: '经理工号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'customer_name',
                    title: '客户姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'customer_zjzh',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'status',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(0 == value){
                            res = '<span class="text-warning">未审核</span>'
                        }else if(1 == value){
                            res = '<span class="text-success">已归档</span>';
                        }else{
                            res = '<span class="text-danger">已打回</span>'
                        }
                        return res;
                    }
                },{
                    field: 'created_at',
                    title: '录入时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true
                },{
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #delete_one":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        if(row.status == 0){
                            result += ' <a class="btn btn-primary btn-sm param" onclick="operation($(this));" url="/admin/rpa_customer_video_collect/'+id+'/edit"  href="javascript:void(0);">查阅</a>';
                        }else if(row.status == 1){
                            // result += ' <a class="btn btn-primary btn-sm param" onclick="operation($(this));" url="/admin/rpa_customer_video_collect/edit/'+id+'"  href="javascript:void(0);">查阅</a>';
                        }
                        result += ' <a class="btn btn-danger btn-sm param" href="javascript:void(0);" id="delete_one">删除</a>';

                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
