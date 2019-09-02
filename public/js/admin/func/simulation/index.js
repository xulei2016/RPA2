function sendSms(id, type = "msg") {
    $.ajax({
        url:'/admin/rpa_simulation_account_business/sendSms',
        data:{id:id,type:type},
        type:'post',
        dataType:'json',
        success:function (r) {
            if(r.code == 200) {
                toastr.success('发送成功');
                location.reload();
            } else {
                toastr.error(r.info);
            }
        }
    });
}
$(function(){
    let selectInfo = [];
    let url_prefix = "/admin/rpa_simulation_account_business/";
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
            location.href= url_prefix + "export?condition=all"
        });

        //导出筛选
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= url_prefix + "export?"+$url+"&condition=where";
        });

        //导出当日
        $("#pjax-container section.content #toolbar #exportCurrent").on('click', function(){
            location.href= url_prefix + "export?condition=current";
        });

        //一键发送短信
        $("#pjax-container section.content #sendAll").on('click', function(){
            $.ajax({
                url:'/admin/rpa_simulation_account_business/sendAll',
                data:{method:'post'},
                type:'post',
                dataType:'json',
                success:function (r) {
                    if(r.code == 200) {
                        toastr.success("短信发送成功,应发短信"+r.data.count+"条,成功发送"+r.data.successCount+"条,失败短信"+r.data.failureCount+"条");
                        location.reload();
                    } else {
                        toastr.error(r.info);
                    }
                }
            });
        });

    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            startTime : $("#pjax-container #search-group #startTime").val(),
            endTime : $("#pjax-container #search-group #endTime").val(),
            name : $("#pjax-container #search-group #name").val()
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
            url: url_prefix+'list',
            columns: [{
                checkbox: true,
            }, {
                field: 'name',
                title: '姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'sfz',
                title: '身份证',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'phone',
                title: '电话',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'created_at',
                title: '录入时间',
                align: 'center',
                valign: 'middle',
                formatter:function(value, row, index){
                    return value.substr(0, 10);
                }
            }, {
                field: 'zjzh',
                title: '资金账号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'dl_days',
                title: '大连天数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'dl_amount',
                title: '大连笔数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'dl_xq',
                title: '大连行权数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zz_days',
                title: '郑州天数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zz_amount',
                title: '郑州笔数',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zz_xq',
                title: '郑州行权数',
                align: 'center',
                valign: 'middle'
            },{
                field: 'from',
                title: '来源',
                align: 'center',
                valign: 'middle'
            }
            // , {
            //     field: 'is_sms',
            //     title: '短信发送情况',
            //     align: 'center',
            //     valign: 'middle',
            //     formatter:function (value, row, index) {
            //         var is_sms = value;
            //         var is_notice = row.is_notice;
            //         var result;
            //         if(row.zjzh) {
            //             if(!is_notice) {
            //                 var type = "notice";
            //                 result = "<a href='javascript:;' onclick='sendSms("+row.id+",\"notice\""+" )'  class='btn btn-sm btn-info sendSms'>发送初始通知短信</a>";
            //             } else {
            //                 result = is_sms?"已发送":"<a href='javascript:;' onclick='sendSms("+row.id+")'  class='btn btn-sm btn-info sendSms'>发送交易情况短信</a>";
            //             }
            //         } else {
            //             result = "资金账号未设置";
            //         }

            //         return result;
            //     }
            // }
            // , {
            //     field: 'id',
            //     title: '操作',
            //     align: 'center',
            //     valign: 'middle',
            //     events: {
            //         "click #change_type":function (e, value, row, index){
            //             var id = row.id;
            //             change_type(id);
            //         }
            //     },
            //     formatter: function(value, row, index){
            //         var id = value;
            //         var result = " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/rpa_simulation_account_business/edit/"+id+"' title='编辑'>编辑</a><br/>";
            //         return result;
            //     }
            // }
        ],
        }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
