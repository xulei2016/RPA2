$(function(){
    let url_prefix = '/admin/sys_version_update/';
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
            desc : $("#pjax-container #search-group #name").val(),
            type : $("#pjax-container #search-group #type").val()
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
            columns: [
                // {checkbox: true},
                {
                field: 'type',
                title: '类型',
                align: 'center',
                valign: 'middle',
                formatter: function (res) { // 1正常更新 2版本升级  3紧急维护
                    if(res == 3) {
                        return '<span class="x-tag x-tag-sm x-tag-danger">紧急维护</span>';
                    } else if(res == 2 ) {
                        return '<span class="x-tag x-tag-sm x-tag-primary">版本升级</span>';
                    } else {
                        return '<span class="x-tag x-tag-sm x-tag-success">正常更新</span>';
                    }
                }
            }, {
                field: 'created_at',
                title: '发布时间',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'created_by',
                title: '发布人',
                align: 'center',
                valign: 'middle'

            }, {
                field: 'desc',
                title: '描述',
                align: 'center',
                valign: 'middle',
                formatter:function(val){
                    var num = val.substr(50, 1);
                    var t = val.substr(0,50);
                    if(num) {
                        t += '...'
                    }
                    return t
                }
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
                    result += " <a href='javascript:;' class='btn btn-sm btn-success' onclick=\"operation($(this));\" url='"+url_prefix + id+"' title='查看'>查看</a>";
                    result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='"+url_prefix + id+"/edit' title='编辑'>编辑</a>";
                    result += " <a href='javascript:;' class='btn btn-sm btn-danger' id='deleteOne' title='删除'>删除</a>";
                    return result;
                }
            }]
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
