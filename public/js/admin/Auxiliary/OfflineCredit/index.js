$(function(){

    let url_prefix = "/admin/rpa_offline_credit/";

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

        let date = '#pjax-container #search-group #date';
        laydate.render({ elem: date, type: 'date', max: nowDate });

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

        // 点击reset
        $('#formSearch #reset').on('click', function(){
            $('#uid').val('');
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
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            idCard : $("#pjax-container #search-group #idCard").val(),
            code : $("#pjax-container #search-group #code").val(),
            date : $("#pjax-container #search-group #date").val(),
            type : $("#pjax-container #search-group #type").val(),
            status : $("#pjax-container #search-group #status").val(),
            from_created_at : $("#pjax-container #search-group #startTime").val(),
            to_created_at : $("#pjax-container #search-group #endTime").val(),
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
                field: 'name',
                title: '名称',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'idCard',
                title: '证件号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'code',
                title: '流水号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'date',
                title: '查询日期',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'type',
                title: '类型',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = '';
                    if(value == 1) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">个人</span>';
                    } else if(value == 2) {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">企业</span>';
                    }
                    return res
                }
            }, {
                field: 'status',
                title: '关联结果',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    let res = "";
                    //0  初始化  1 正在查询  2 无失信  3 有失信  4 查询失败
                    if(value == 1) {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">正在查询</span>';
                    } else if(value == 2) {
                        res = '<span class="x-tag x-tag-sm x-tag-success">未失信</span>';
                    } else if(value == 3) {
                        res = '<span class="x-tag x-tag-sm x-tag-danger">失信</span>';
                    } else if(value == 4) {
                        res = '<span class="x-tag x-tag-sm x-tag-warning">查询失败</span>';
                    } else {
                        res = '<span class="x-tag x-tag-sm x-tag-primary">初始化</span>';
                    }
                    return res;
                }
            },{
                field: 'created_at',
                title: '创建时间',
                align: 'center',
                valign: 'middle',
                sortable: true,
            },{
                field: 'updated_at',
                title: '修改时间',
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
                    // 查看图片
                    if(row.status === 2 || row.status === 3) {
                        result += "<a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='"+url_prefix+id+"' title='查看'>查看</a>";
                        result += "<a href='"+url_prefix+'download/'+id+"' target='_blank' class='btn btn-sm btn-primary' title='下载'>下载</a>";
                    }
                    return result;
                }
            }
            ],
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
