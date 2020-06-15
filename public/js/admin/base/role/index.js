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
            location.href="/admin/sys_role/export?"+$url;
        });

        //导出全部
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/sys_role/export?"+$url+'&id='+ids;
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
                        url: '/admin/sys_role/'+id,
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
     * 切换状态
     */
    function changeType(id,type){
        $.ajax({
            url:'/admin/sys_role/changeType',
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

    //get searchGroup
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            desc : $("#pjax-container #search-group #desc").val()
        }
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
        
        function stateFormatter(value, row, index) {
            if (row.id == 1)
                return {
                    disabled : true,//设置是否可用
                    checked : false//设置选中
                };
            return value;
        }

        var param = {
            url: '/admin/sys_role/list',
            columns: [{
                    field: "check", 
                    title: "",
                    align: "center", 
                    checkbox: true,
                    formatter:stateFormatter,
                }, {
                    field: 'name',
                    title: '名称',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'guard_name',
                    title: '用户组',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'type',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return (1 == res) ? '<span class="x-tag x-tag-sm x-tag-success">启用</span>' : '<span class="x-tag x-tag-sm x-tag-danger">禁用</span>' ;
                    }
                },{
                    field: 'desc',
                    title: '描述',
                    align: 'center',
                    valign: 'middle'
                },{
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
                    events: {
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        // if(1 != id){
                            result += " <a href='javascript:;' class='btn btn-sm btn-info' onclick=\"operation($(this));\" url='/admin/sys_role/"+id+"/getPermission' title='权限分配'>权限管理</span></a>";
                            result += " <a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"operation($(this));\" url='/admin/sys_role/"+id+"/edit' title='编辑'>编辑</a>";
                            result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</span></a>";
                        // }

                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
