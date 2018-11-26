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
            location.href="/admin/admin/export?"+$url;
        });

        //导出全部
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/sys_mail/export?"+$url+'&id='+ids;
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
                        url: '/admin/sys_mail/'+id,
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

    //get searchGroup
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            type : $("#pjax-container #search-group #type").val()
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
            url: '/admin/sys_mail/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'title',
                    title: '主题',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return res.length > 20 ? res.slice(0,20) : res ;
                    }
                }, {
                    field: 'type',
                    title: '消息类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return '<small class="label bg-primary">'+res+'</small>';
                    }
                }, {
                    field: 'mode',
                    title: '发送类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return '<small class="label bg-primary">'+res+'</small>';
                    }
                }, {
                    field: 'is_read',
                    title: '是否已读',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(res){
                        return (1 == res) ? '<small class="label bg-green">已读</small>' : '<small class="label bg-red">未读</small>' ;
                    }
                }, {
                    field: 'created_at',
                    title: '时间',
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
                        result += " <a href='javascript:;' class='btn btn-xs btn-warning' onclick=\"operation($(this));\" url='/admin/sys_mail/"+id+"/edit' title='查看'><span class='glyphicon glyphicon-pencil'></span></a>";
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