$(function(){

    let url_prefix = "/admin/rpa_monitor_picture/";

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
        let nowDate = getFormatDate();

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
            location.href= url_prefix + "export?id="+ids;
        });

        //使用说明
        $('.instructions').on('click', function(){
            $.ajax({
                url:'/admin/sys_document/showDoc/37',
                data: {id:37},
                success:function(res){
                    if(res.code != 200) {
                        swal(res.info, '', 'info');
                    } else {
                        swal.fire({
                            title:'使用说明',
                            html: res.data,
                            width:800
                        })
                    }
                }
            });
        });

    }

    /**
     * 添加备注
     * @param row
     */
    function addRemark(row){
        swal({
            title: '添加注释',
            input: 'text',
            inputValue:row.remark,
            showCancelButton: true,
            confirmButtonText: '确认',
            cancelButtonText: ' 取消',
            inputValidator: function(value) {
            }
        }).then(function (res) {
            if(res.value || res.value == '') {
                swal.fire({
                    onBeforeOpen:() => {
                        swal.showLoading()
                    }
                });
                $.ajax({
                    url:url_prefix + 'addRemark',
                    type:'post',
                    dataType:'json',
                    data:{remark:res.value, id:row.id},
                    success:function(r){
                        if(r.code == 200) {
                            swal('成功', '', 'success');
                            $('#tb_departments').bootstrapTable('refresh');
                        } else {
                            swal(r.info, '', 'error');
                            return false;
                        }
                    },
                    error:function () {
                        swal('操作失败', '', 'error');
                        return false;
                    }

                })
            }

        });
    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            zjzh : $("#pjax-container #search-group #zjzh").val(),
            check_status : $("#pjax-container #search-group #check_status").val(),
            start_id : $("#pjax-container #search-group #start_id").val(),
            end_id : $("#pjax-container #search-group #end_id").val(),
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
            // temp["sortOrder"] = params.order;                   //排位命令（desc，asc）
            temp["sortOrder"] = 'asc';                   //排位命令（desc，asc）
            // 特殊格式的条件处理
            let obj = getSearchGroup();
            for(let i in obj){
                temp[i] = obj[i];
            }
            return temp;
        };



        var param = {
            url: url_prefix + 'list',
            columns: [{
                checkbox: true,
            },{
                field: 'id',
                title: 'ID',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'ZJZH',
                title: '资金账号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'KHXM',
                title: '客户姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'ZJBH',
                title: '证件编号',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'statusName',
                title: '类型',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'checkStatusName',
                title: '状态',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    //0 待审核  1 待复核  2 已上报  3 打回 4 上报成功  5 上报失败
                    if(row.check_status == 1) {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">待复核</span>';
                    } else if(row.check_status == 2) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">已上报</span>';
                    } else if(row.check_status == 3) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">打回</span>';
                    } else if(row.check_status == 4) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">上报成功</span>';
                    } else if(row.check_status == 5) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">上报失败</span>';
                    } else {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">待审核</span>';
                    }
                    return res;
                }
            }, {
                field: 'check',
                title: '审核人',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'check_time',
                title: '审核时间',
                align: 'center',
                valign: 'middle',
            },{
                field: 'review',
                title: '复核人',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'review_time',
                title: '复核时间',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'report_remarks',
                title: '上报结果',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'remark',
                title: '备注',
                align: 'center',
                valign: 'middle',
                events:{
                    "dblclick .remark":function (e, value, row, index){

                        addRemark(row);
                    }
                },
                formatter: function(value, row, index){
                    if(!value) value='-';
                    return "<div class='remark' style='width: 100%'>"+value+"</div>"
                }
            },
                {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        var id = row.id;
                        if(row.check_status == 0 || row.check_status == 3) {
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_monitor_picture/"+id+"/edit' title='审核'>审核</a>";
                        } else if(row.check_status == 1) {
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_monitor_picture/"+id+"/review' title='复核'>复核</a>";
                        } else {
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_monitor_picture/"+id+"' title='查看'>查看</a>";
                        }

                        return result;
                    }
                }],
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
