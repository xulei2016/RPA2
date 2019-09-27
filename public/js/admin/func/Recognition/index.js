$(function(){
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
            location.href="/admin/rpa_address_recognition/export?"+$url;
        });

        //导出选中
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            location.href="/admin/rpa_address_recognition/export?id="+ids;
        });

    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            zjzh : $("#pjax-container #search-group #zjzh").val(),
            state : $("#pjax-container #search-group #state").val(),
            from_created_at : $("#pjax-container #search-group #startTime").val(),
            to_created_at : $("#pjax-container #search-group #endTime").val()
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
            url: '/admin/rpa_address_recognition/list',
            columns: [{
                    checkbox: true,
                }, {
                    field: 'zjzh',
                    title: '资金账号',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let result = value;
                        if(row.has_jybm == '0'){
                            result = "<span class='x-tag x-tag-sm x-tag-danger'>"+value+"</span>"
                        }
                        return result;
                    }
                }, {
                    field: 'address_final',
                    title: '最终地址',
                    align: 'center',
                    valign: 'middle'
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
                    sortable: true,
                }, {
                    field: 'review',
                    title: '复核人',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'review_time',
                    title: '复核时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'state',
                    title: '状态',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        let res = "";
                        if(row.rpa_state == 1){
                            if(4 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-primary">地址已修改</span>';
                            }else if(5 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-primary">地址已核验</span>';
                            }else if(6 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-primary">已上报</span>';
                            }else if(7 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-primary">上报已核验</span>';
                            }else{
                                res = '<span class="x-tag x-tag-sm x-tag-primary">rpa运行失败</span>';
                            }
                        }else{
                            if(-1 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-danger">复核失败</span>';
                            }else if(0 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-info">未处理</span>';
                            }else if(1 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-warning">已审核</span>';
                            }else if(2 == value){
                                res = '<span class="x-tag x-tag-sm x-tag-success">已复核</span>';
                            }
                        }
                        return res;
                    }
                }, {
                    field: 'remarks',
                    title: '备注',
                    align: 'center',
                    valign: 'middle',
                }, {
                    field: 'created_at',
                    title: '创建时间',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                }, {
                    field: 'id',
                    title: '操作',
                    align: 'center',
                    valign: 'middle',
                    formatter: function(value, row, index){
                        var result = "";
                        var id = row.id;
                        if(row.state == 0 || row.state == -1){
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_address_recognition/"+id+"/edit' title='审核'>审核</a>";
                        }else if(row.state == 1){
                            result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_address_recognition/review/"+id+"' title='复核'>复核</a>";
                        }
                        return result;
                    }
                }],
            }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
