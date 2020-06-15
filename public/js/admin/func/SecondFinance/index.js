$(function(){

    let url_prefix = "/admin/rpa_second_finance/";

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
        //定义时间按钮事件
        let st = '#pjax-container #search-group #startTime';
        let et = '#pjax-container #search-group #endTime';
        laydate.render({
            elem: st, type: 'date', max: nowDate, done: function (value, date, endDate) {
                laydate.render({ elem: et, type: 'date', show: true, min: value, max: nowDate });
            }
        });
        laydate.render({ elem: et, type: 'date', max: nowDate });

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
    }

    /**
     * 上报单条记录
     */
    function Report(id){
        Swal({
            title: "确认上报?",
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
                        url: url_prefix + id + "/report",
                        data: {
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
            fundsNum : $("#pjax-container #search-group #fundsNum").val(),
            status : $("#pjax-container #search-group #status").val(),
            from_open_date : $("#pjax-container #search-group #startTime").val(),
            to_open_date : $("#pjax-container #search-group #endTime").val()
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
            temp["sortOrder"] = 'desc';                   //排位命令（desc，asc）
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
            }, {
                field: 'fundsNum',
                title: '资金账号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'name',
                title: '姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'yyb',
                title: '营业部',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    return row.yybName+"("+row.yybNum+")";
                }
            }, {
                field: 'manager',
                title: '客户经理',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    return row.customerManagerName+"("+row.customerNum+")";
                }
            }, {
                field: 'mediator',
                title: '居间人',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    return row.jjrName+"("+row.jjrNum+")";
                }
            }, {
                field: 'remark',
                title: '备注',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'creater',
                title: '开户人',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'open_date',
                title: '开户日期',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'status',
                title: '状态',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    //0 资料待补全  1 待上报 2 待归档  3 完成
                    if(row.status == 1) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">待上报</span>';
                    } else if(row.status == 2) {
                        res = '<span class="x-tag x-tag-sm x-tag-warning">待归档</span>';
                    } else if(row.status == 3) {
                        res = '<span class="x-tag x-tag-sm x-tag-info">完成</span>';
                    } else if(row.status == -1) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">失信有问题</span>';
                    } else {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">资料待补全</span>';
                    }
                    return res;
                }
            }, {
                field: 'jkzxstates',
                title: '监控中心状态',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    //0 正常  1 不符合二次金融条件 -1 程序运行失败
                    if(row.jkzxstates == -1) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">程序运行失败</span>';
                    } else if(row.jkzxstates == 0) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">正常</span>';
                    } else if(row.jkzxstates == 1) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">不符合二次金融条件</span>';
                    } else {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">未查询</span>';
                    }
                    return res;
                }
            }, {
                field: 'sbstatus',
                title: '上报状态',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    //0 正常  1 不符合二次金融条件 -1 程序运行失败
                    if(row.sbstatus === '1') {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">上报失败</span>';
                    } else if(row.jkzxstates === '0') {
                        res = '<span class="x-tag x-tag-sm x-tag-success">上报成功</span>';
                    }
                    return res;
                }
            },{
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                events: {
                    "click #report":function (e, value, row, index){
                        var id = row.id;
                        Report(id);
                    }
                },
                formatter: function(value, row, index){
                    var result = "";
                    var id = row.id;
                    if(row.status > 0){
                        result += "<a href='javascript:;' class='btn btn-sm btn-info' onclick=\"operation($(this));\" url='/admin/rpa_second_finance/"+id+"' title='查看资料'>查看资料</a>";
                        result += " <a href='/admin/rpa_second_finance/download/"+id+"' class='btn btn-sm btn-success'  title='导出'>导出</a>";
                    }
                    if(row.status === 1 || row.jkzxstates === 0) {
                        result += " <a href='javascript:;' class='btn btn-sm btn-primary' id='report' title='上报'>上报</a>";
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
