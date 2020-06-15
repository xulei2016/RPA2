$(function () {
    let selectInfo = [];

    /*
     * 初始化
     */
    function init() {
        bindEvent();

        //1.初始化Table
        let oTable = new RPA.TableInit();
        pageNation(oTable);
    }

    /*
     * 绑定事件
     */
    function bindEvent() {
        //根据条件查询信息
        $('#pjax-container #search-group #formSearch #search-btn').click(function() {
            $('#tb_departments').bootstrapTable('refreshOptions',{pageNumber:1});
        });
        //enter键盘事件
        $("#pjax-container #search-group #formSearch input").keydown(function (event) {
            event = event ? event : window.event;
            if (event.keyCode == 13) {
                $('#tb_departments').bootstrapTable('refresh');
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
                        url: '/admin/sys_sms/'+id,
                        data: {
                            _method:'delete',
                            _token:LA.token,
                            id:id
                        },
                        success: function (json) {
                            if('200' === json.code){
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
            json = json.value;
            Swal(json.info, '', 'success');
        },function(dismiss){
            Swal(dismiss, '', 'error');
        });
    }

    //分页参数
    function pageNation(oTable) {
        oTable.queryParams = function (params) {
            //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
            var temp = $("#pjax-container #search-group").serializeJsonObject();
            temp["rows"] = params.limit;                        //页面大小
            temp["total"] = params.total;                        //页面大小
            temp["page"] = (params.offset / params.limit) + 1;  //页码
            temp["sort"] = params.sort;                         //排序列名
            temp["sortOrder"] = params.order;                   //排位命令（desc，asc）

            return temp;
        };

        let param = {
            url: '/admin/sys_sms/gatewayPagination',
            columns: [{
                checkbox: true,
            }, {
                field: 'name',
                title: '通道名称',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'unique_name',
                title: '通道代号',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'status',
                title: '列表',
                align: 'center',
                valign: 'middle',
                formatter: function(res){
                    return (1 == res) ? '<span class="x-tag x-tag-sm x-tag-success">启用</span>' : '<span class="x-tag x-tag-sm x-tag-danger">禁用</span>' ;
                }
            }, {
                field: 'created_at',
                title: '发送时间',
                align: 'center',
                valign: 'middle',
                sortable: true
            }, {
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                events: {
                    "click #deleteOne": function (e, value, row, index) {
                        const id = row.id;
                        Delete(id);
                    }
                },
                formatter: function (value, row, index) {
                    let id = value;
                    let result = "";
                    result += " <a href='javascript:;' class='btn btn-sm btn-warning' onclick=\"operation($(this));\" url='/admin/sys_sms/" + id + "/edit' title='编辑'>编辑</a>";
                    result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</span></a>";

                    return result;
                }
            }],
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
