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
        $("#container .inner-content .middle-layer .deletebatch").on('click', function(){
        	var ids = idList.join(',');
            if("" == ids){
                responseTip(1,'您尚未选择要删除的数据！',1500);
        	}else{
	            myConfirmModal("确定要批量删除资讯吗？",function(){
		            $.ajax({
		                url:"/admin/sys_admin_manage/deleteAll",
		                type:"post",
		                data:{"ids":ids},
		                dataType:"json",
		                beforeSend:function(xhr){
		                    $("#loading").modal('show');
		                },
		                complete:function(){
		                    $("#loading").modal('hide');
		                },
		                success:function(json,statusText){
		                    if(json.code == 200){
                                if(currentPage != 1 && (total_count - idList.length) % pageSize == 0){
                                    currentPage = currentPage - 1;
                                }
                                idList = [];//初始化idList的值
                                render(currentPage);
                            }else{
                                responseTip(json.code,json.info,1500);
                            }
		                },
		                error:errorResponse
		            });
	            });
        	}
        });
    }

    /**
     * 删除单条记录
     */
    function DeleteOne(id){
        Swal({
            title: "确认删除?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            showLoaderOnConfirm: true,
            cancelButtonText: "取消",
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        method: 'post',
                        url: '/admin/menu/' + id,
                        data: {
                            _method:'delete',
                            _token:LA.token,
                        },
                        success: function (json) {
                            if(200 == json.code){
                                $.pjax.reload('#pjax-container');
                                toastr.success('删除成功 !');
                                resolve(json);
                            }else{
                                toastr.error(json.info);
                            }
                        }
                    });
                });
            }
        }).then(function(json) {
            var json = json.value;
            if (typeof json === 'object') {
                if (200 == json.code) {
                    Swal(json.info, '', 'success');
                } else {
                    Swal(json.info, '', 'error');
                }
            }
        });
    }

    /**
     * 切换状态
     */
    function changeType(){
        let type = $(this).attr("type");
        let id = $(this).attr("id");
        $.ajax({
            url:'/admin/sys_admin_manage/typeChange',
            data:{id:id,type:type},
            type:'post',
            dataType:'json',
        }).then(function(json){
            if(200 == json.code){
                render(true,currentPage,pageSize);
            }
        },function(e){
            responseTip(1,'操作失败！',1500);
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

            //特殊格式的条件处理
            temp["name"] = $("#pjax-container #search-group #name").val();
            temp["role"] = $("#pjax-container #search-group #role").val();
            temp["status"] = $("#pjax-container #search-group #status").val();

            return temp;
        };

        var param = {
            url: '/admin/role/list',
            columns: [{
                    checkbox: true
                }, {
                    field: 'name',
                    title: '姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'realName',
                    title: '真实姓名',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'sex',
                    title: '性别',
                    formatter: function(res){
                        return (res == 1) ? '男' : ((res == 0) ? '女' : '未知') ;
                    },
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'phone',
                    title: '电话',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'email',
                    title: '邮箱',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'type',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return (1 == res) ? '<span class="text-success">启用</span>' : '<span class="text-danger">禁用</span>' ;
                    }
                }, {
                    field: 'lastIp',
                    title: '最后登录ip',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'lastTime',
                    title: '最后活跃时间',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'created_at',
                    title: '创建时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                },{
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        result += "<a href='javascript:;' class='btn btn-xs btn-info' onclick=\"ViewOne('" + id + "', view='view')\" title='查看'><span class='glyphicon glyphicon-search'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-warning' onclick=\"EditOne('" + id + "')\" title='编辑'><span class='glyphicon glyphicon-pencil'></span></a>";
                        result += " <a href='javascript:;' class='btn btn-xs btn-danger' onclick=\"DeleteOne('" + id + "')\" title='删除'><span class='glyphicon glyphicon-remove'></span></a>";

                        return result;
                    }
                }],
            }
        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
