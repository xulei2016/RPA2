$(function(){
    /*
     * 初始化
     */
    function init(){

        //1.初始化Table
        var oTable = new RPA.TableInit();
        pageNation(oTable);
        
        bindEvent();
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
        $("#pjax-container #toolbar #exportAll").on('click', function(){
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/sys_admin/export?"+$url;
        });

        //导出选中
        $("#pjax-container #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/sys_admin/export?"+$url+'&id='+ids;
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
                        url: '/admin/sys_admin/'+id,
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
     * 清空错误次数
     * @param id
     */
    function clearCount(id){
        $.ajax({
            url:'/admin/sys_admin/clearCount',
            dataType:'json',
            type:'post',
            data:{id:id,_token:LA.token},
            success:function(r) {
                if(r.code == 200) {
                    swal("成功", '', 'success');
                } else {
                    swal(r.info, '', 'error');
                    return false;
                }
            }
        });
    }

    //get searchGroup
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            realName : $("#pjax-container #search-group #realName").val()
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
            url: '/admin/sys_admin/list',
            columns: [{
                    field: "check", 
                    title: "",
                    align: "center", 
                    checkbox: true,
                    formatter:stateFormatter,
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
                        return (res == 1) ? '男' : ((res == 2) ? '女' : '未知') ;
                    },
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'phone',
                    title: '电话',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'group',
                    title: '分组',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        let html = ' <small class="x-tag x-tag-sm">'+res+'</small> ';
                        return html;
                    }
                }, {
                    field: 'roleLists',
                    title: '角色',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        if(res) res = res.split(',');
                        let html = '';
                        for(let v of res){
                            html += ' <small class="x-tag x-tag-sm">'+v+'</small> ';
                        }
                        return html;
                    }
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
                        return (1 == res) ? '<small class="x-tag x-tag-success x-tag-sm">启用</small>' : '<small class="x-tag x-tag-danger x-tag-sm">禁用</small>' ;
                    }
                }, {
                    field: 'created_at',
                    title: '创建时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                },{
                    field: 'error_count',
                    title: '登录错误次数',
                    align: 'center',
                    valign: 'middle'
                },{
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.id;
                            Delete(id);
                        },
                        'click .clearCount':function(e, value, row, index) {
                            var id = row.id;
                            clearCount(id);
                         }
                    },
                    formatter: function(value, row, index){
                        var id = value;
                        var result = "";
                        if(1 == id)return result;
                        result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/sys_admin/"+id+"/edit' title='编辑'>编辑</a>";
                        result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</a>";
                        if(row.error_count >= 10) result += " <a href='javascript:;' class='btn btn-primary btn-sm clearCount'>清空次数</a>";
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
