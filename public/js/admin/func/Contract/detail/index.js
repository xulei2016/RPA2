$(function(){
    let url_prefix = '/admin/rpa_contract_detail/';

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
            $('#tb_departments').bootstrapTable('refresh');
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

        //更新全部数据
        $('.updateAll').on('click', function(){
            return false;
            swal({
                title: '是否要进行数据更新',
                type: 'info',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3fc3ee',
                cancelButtonColor: 'gray',
                confirmButtonText: '确认',
                cancelButtonText: ' 取消'
            }).then(function (res) {
                if(res.value) {
                    swal.fire({
                        title: '正在更新数据,请稍后',
                        onBeforeOpen:() => {
                            swal.showLoading()
                        }
                    });
                    $.ajax({
                        url:url_prefix + 'updateAll',
                        type:'post',
                        dataType:'json',
                        data:{msg:'更新全部'},
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
        });

        /**
         * 获取数据
         */
        $('.test-email').on('click', function(){
            swal({
                title: '请输入日期',
                type: 'info',
                html: '<input style="width: 50%" type="text" id="date" class="text-center,form-control">',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3fc3ee',
                cancelButtonColor: 'gray',
                confirmButtonText: '确认',
                cancelButtonText: ' 取消'
            }).then(function (res) {
                if(res.value) {
                    var date = $('#date').val();
                    if(!date) {
                        swal('日期必填','', 'error');
                        return false;
                    }
                    swal.fire({
                        title: '正在处理,请稍后',
                        onBeforeOpen:() => {
                            swal.showLoading()
                        }
                    });
                    $.ajax({
                        url:url_prefix + 'testEmail',
                        dataType:'json',
                        data:{date:date},
                        success:function(r){
                            if(r.code == 200) {
                                swal(r.info, '', 'success');
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
            let date = '#date';
            laydate.render({ elem: date, type: 'date'});
        })
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
            name : $("#pjax-container #search-group #name").val(),
            jys_id : $("#pjax-container #search-group #jys_id").val()
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
                field: 'jys',
                title: '交易所',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'pz',
                title: '品种名称',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'pzfy_jysxf',
                title: '交易手续费<br >(品种费用)',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'pzfy_rnfy',
                title: '日内费用<br >(品种费用)',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'hy_month',
                title: '特殊期货合约',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'has_online',
                title: '是否有<br>上市合约',
                align: 'center',
                valign: 'middle',
                formatter: function(res){
                    return (1 == res) ? '<span class="x-tag x-tag-sm x-tag-success">是</span>' : '<span class="x-tag x-tag-sm x-tag-danger">否</span>' ;
                }
            }, {
                field: '',
                title: '上市时间<br>(新合约上市)',
                align: 'left',
                halign:'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    if(row.has_online) {
                        var html = '';
                        var day = row.xhy_day_type == 1 ? "日<br >(自然日遇节假日顺延)":'交易日';
                        html += "交割月前"+row.xhy_month+"个月第"+row.xhy_day+day;
                        if(row.xhy_day_after) {
                            html += "<br />后第"+row.xhy_day_after+"交易日";
                        }
                        return html;
                    } else {
                        return "<div class='text-center'>-</div>";
                    }
                }
            }, {
                field: 'xhy_jysxf',
                title: '交易手续费<br >(新合约上市)',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'xhy_rnfy',
                title: '日内费用<br >(新合约上市)',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'has_change',
                title: '是否有<br>运行中调整',
                align: 'center',
                valign: 'middle',
                formatter: function(res){
                    return (1 == res) ? '<span class="x-tag x-tag-sm x-tag-success">是</span>' : '<span class="x-tag x-tag-sm x-tag-danger">否</span>' ;
                }
            }, {
                field: '',
                title: '调整时间<br>(运行中调整)',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    if(row.has_change) {
                        var html = '';
                        html += "交割月前第"+row.tz_month+"月<br />的第"+row.tz_day+"个交易日起";
                        return html;
                    } else {
                        return "-";
                    }

                }
            }, {
                field: 'tz_jysxf',
                title: '交易手续费<br >(运行中调整)',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'tz_rnfy',
                title: '日内费用<br >(运行中调整)',
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

