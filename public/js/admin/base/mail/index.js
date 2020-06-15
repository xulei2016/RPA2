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

        //批量删除
        $("#pjax-container section.content #toolbar #deleteAll").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            console.log(ids);
            if("" == ids){
                swal('提示','你还没有选择需要操作的行！！！','warning');
        	}else{
                Delete(ids);
            }
        });

        //侧边栏点击事件
        $('#pjax-container section.content .mail-box ul li').on('click', function(){
            let _this = $(this);
            if(!_this.hasClass('active')){
                _this.siblings('.active').removeClass('active');
                _this.addClass('active');
            }
            $('#tb_departments').bootstrapTable('refresh');
        });

        //导出全部
        $("#pjax-container section.content #toolbar #exportAll").on('click', function(){
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href="/admin/admin/export?"+$url;
        });

        //导出选中
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
            title : $("#pjax-container #search-group #title").val(),
            type : $("#pjax-container section.content .mail-box ul li.active").attr('data-value')
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

        var param = {
            url: '/admin/sys_mail/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'title',
                    title: '主题',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value,row){
                        let title = row.mails.title;
                        return title.length > 20 ? title.slice(0,20) : title ;
                    }
                }, {
                    field: 'type',
                    title: '消息类型',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value,row){
                        let type = "";
                        if(row.mails.tid == 1){
                            type = '<small class="x-tag x-tag-sm">系统公告</small>';
                        }else if(row.mails.tid == 2){
                            type = '<small class="x-tag x-tag-sm">RPA通知</small>';
                        }else{
                            type = '<small class="x-tag x-tag-sm">管理员通知</small>';
                        }
                        return type;
                    }
                }, {
                    field: 'read_at',
                    title: '是否已读',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value){
                        return (value) ? '<span class="x-tag x-tag-sm x-tag-success">已读</span>' : '<span class="x-tag x-tag-sm x-tag-danger">未读</span>' ;
                    }
                }, {
                    field: 'created_at',
                    title: '时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    formatter: function(value,row){
                        let created_at = row.mails.created_at;
                        return created_at;
                    }
                },{
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    events: {
                        "click #deleteOne":function (e, value, row, index){
                            var id = row.mid;
                            Delete(id);
                        }
                    },
                    formatter: function(value, row, index){
                        var id = row.mid;
                        var result = "";
                        if(row.type == 3){
                            result += " <a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"operation($(this));\" url='/admin/sys_mail/"+id+"/edit' title='重新发送'>重新发送</a>";
                        }
                        result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/sys_mail/"+id+"' title='查看'>查看</a>";
                        result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</a>";

                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
