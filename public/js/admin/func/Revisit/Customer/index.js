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
        let nowDate = getFormatDate();
        //定义时间按钮事件
        let st = '#pjax-container #search-group #startTime';
        let et = '#pjax-container #search-group #endTime';
        laydate.render({
            elem: st, type: 'date', max: nowDate, done: function (value, date, endDate) {
                laydate.render({elem: et, type: 'date', show: true, min: value, max: nowDate});
            }
        });
        laydate.render({elem: et, type: 'date', max: nowDate});

        $('#pjax-container #tb_departments').on('click', 'tr td .recording', function (e) {
            console.log(e);
        });

        //根据条件查询信息
        $('#pjax-container #search-group #formSearch #search-btn').click(function () {
            $('#tb_departments').bootstrapTable('refresh');
        });

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

        //导出全部
        $("#pjax-container #toolbar #exportAll").on('click', function () {
            let url = urlEncode(getSearchGroup());
            location.href = `/admin/rpa_customer_revisit/export?${url}`;
        });

        //导出全部
        $("#pjax-container #toolbar #export").on('click', function () {
            let ids = RPA.getIdSelections('#tb_departments');
            let url = urlEncode(getSearchGroup());
            location.href = `/admin/rpa_customer_revisit/export?${url}&ids=${ids}`;
        });
    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup() {
        //特殊格式的条件处理
        return temp = {
            name: $("#pjax-container #search-group #customer").val(),
            status: $("#pjax-container #search-group #status").val(),
            yybName: $("#pjax-container #search-group #yybName").val(),
            reviser: $("#pjax-container #search-group #reviser").val(),
            checker: $("#pjax-container #search-group #checker").val(),
            from_created_at: $("#pjax-container #search-group #startTime").val(),
            to_created_at: $("#pjax-container #search-group #endTime").val()
        };
    }

    //分页参数
    function pageNation(oTable) {
        oTable.queryParams = function (params) {
            //这里的键的名字和控制器的变量名必须一直，这边改动，控制器也需要改成一样的
            let temp = $("#pjax-container #search-group").serializeJsonObject();
            temp["rows"] = params.limit;                        //页面大小
            temp["total"] = params.total;                        //页面大小
            temp["page"] = (params.offset / params.limit) + 1;  //页码
            temp["sort"] = params.sort;                         //排序列名
            temp["sortOrder"] = params.order;                   //排位命令（desc，asc）
            //特殊格式的条件处理
            let obj = getSearchGroup();
            for (let i in obj) {
                temp[i] = obj[i];
            }
            return temp;
        };


        let param = {
            url: '/admin/rpa_customer_revisit/list',
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
                field: 'yybName',
                title: '营业部',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'customerManagerName',
                title: '客户经理',
                align: 'center',
                valign: 'middle'
            },{
                field: 'status',
                title: '状态',
                align: 'center',
                valign: 'middle',
                formatter: function (value, row, index) {
                    let res = "";
                    if (4 === value) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">已归档</span>';
                    } else if (3 === value) {
                        res = '<span class="x-tag x-tag-sm x-tag-default">审核完成</span>';
                    } else if (2 === value) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">待审核</span>';
                    } else if (1 === value) {
                        res = '<span class="x-tag x-tag-sm x-tag-warning">回访失败</span>';
                    } else {
                        res = '<span class="x-tag x-tag-sm x-tag-info">未回访</span>';
                    }
                    return res;
                }
                // }, {
                //     title: '试听',
                //     align: 'center',
                //     valign: 'middle',
                //     formatter: function (value, row, index) {
                //         let aud = '<a class="btn recording" data-id=`${value}`><i class="fa fa-play"></i></a>';
                //         return aud;
                //     }
            }, {
                field: 'KHRQ',
                title: '开户日期',
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                field: 'reviser',
                title: '回访人',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'revisit_at',
                title: '回访时间',
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                field: 'dict_prompt',
                title: '回访标记',
                align: 'center',
                valign: 'middle',
                formatter: function (value, row, index) {
                    let res = "";
                    if ('正常' === value) {
                        res = `<span class="x-tag x-tag-sm x-tag-success">${value}</span>`;
                    } else {
                        res = `<span class="x-tag x-tag-sm x-tag-danger">${value}</span>`;
                    }
                    return res;
                }
            }, {
                field: 'checker',
                title: '审核人',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'check_at',
                title: '审核时间',
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                field: 'failDesc',
                title: '备注',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                formatter: function (value, row, index) {
                    var id = value;
                    var result = "";
                    if (2 === row.status) {
                        result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_customer_revisit/" + id + "/edit' title='审核'>审核</a><br/>";
                    } else if (1 <= row.status) {
                        result += ` <a href='javascript:;' class='btn btn-sm btn-warning' onclick="operation($(this));" url='/admin/rpa_customer_revisit/${id}' title='查看'>查看</a><br/>`;
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
