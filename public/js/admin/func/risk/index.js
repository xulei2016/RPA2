$(function(){
    let selectInfo = [];
    var url_prefix = "/admin/rpa_risk/";
    /*
     * 初始化
     */
    function init(){
        bindEvent();
        $('.slider').bootstrapSlider()

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
        let rq = '#pjax-container #search-group #rq';
        laydate.render({ elem: rq, type: 'date', max: nowDate });




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


        var options = {
            width: '500px',
            height: '500px',
            language: 'CH', //语言
            showLunarCalendar: false, //阴历
            showHoliday: false, //休假
            showFestival: true, //节日
            showLunarFestival: true, //农历节日
            showSolarTerm: false, //节气
            showMark: true, //标记
            timeRange: {
                startYear: 1900,
                endYear: 2049
            },
            mark: {

            },
            theme: {
                changeAble: false,
                weeks: {
                    backgroundColor: '#FBEC9C',
                    fontColor: '#4A4A4A',
                    fontSize: '20px',
                },
                days: {
                    backgroundColor: '#ffffff',
                    fontColor: '#565555',
                    fontSize: '24px'
                },
                todaycolor: 'orange',
                activeSelectColor: 'orange'
            }
        };

        $('.cale').on('click', function(){
            var myCalendar;
            swal.fire({
                title:'数据日历',
                width:'540px',
                height:'500px',
                html:"<div id='container'></div>"
            });
            $.ajax({
                url: url_prefix + 'getQueryDay', //查询日
                data:{data:'查询日'},
                dataType:'json',
                type:'get',
                success:function (r){
                    options.mark = r.data;
                    myCalendar = new SimpleCalendar('#container',options);
                },
                error:function() {
                    myCalendar = new SimpleCalendar('#container',options);
                }
            });

        });

        $('.instructions').on('click', function(){
            swal.fire({
                title:'风险指标说明',
                html:'<hr /><div style="padding:5px 10px;text-align: left;"><strong style="font-weight: bold;">公司风险度指标</strong>：公司品种保证金 / 总权益</div> <hr />\n' +
                        '\n' +
                    '<div style="padding:5px 10px;text-align: left;"><strong style="font-weight: bold;">交易所风险度指标</strong>：交易所品种保证金 / 总权益</div> <hr />\n' +
                    '\n' +
                    '<div style="padding:5px 10px;text-align: left;"><strong style="font-weight: bold;">交易所品种集中度（保证金率）：</strong>交易所品种保证金 /&nbsp;交易所总保证金</div>  <hr />\n' +
                    '\n' +
                    '<div style="padding:5px 10px;text-align: left;"><strong style="font-weight: bold;">品种风险敞口</strong>：abs（多头持仓手数 - 空头持仓手数） / (多头持仓手数 +&nbsp;空头持仓手数)</div> <hr />\n' +
                    '\n' +
                    '<div style="padding:5px 10px;text-align: left;"><span class="marker">*abs() 取绝对值函数</span></div>\n' +
                    '\n' +
                    '<hr />\n'
            })

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

        /**
         * 获取数据
         */
        $('.get-data').on('click', function(){
            swal({
                title: '请输入获取数据的日期',
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
                        title: '正在获取数据,请稍后',
                        onBeforeOpen:() => {
                            swal.showLoading()
                        }
                    });
                    $.ajax({
                        url:url_prefix + 'getData',
                        type:'post',
                        dataType:'json',
                        data:{date:date},
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
            let date = '#date';
            laydate.render({ elem: date, type: 'date', max: nowDate });
        })

    }

    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            rq : $("#pjax-container #search-group #rq").val(),
            jgbz : $("#pjax-container #search-group #jgbz").val(),
            zjzh : $("#pjax-container #search-group #zjzh").val(),
            khxm : $("#pjax-container #search-group #khxm").val(),
            bzj_rate : $("#pjax-container #search-group #bzj_rate").val(),
            jys_rate : $("#pjax-container #search-group #jys_rate").val(),
            pz1_rate : $("#pjax-container #search-group #pz1_rate").val(),
            exp1 : $("#pjax-container #search-group #exp1").val()
        };
        var two = 0;
        if($('#pjax-container #search-group #two').is(':checked')) {
            two = 1;
        }
        temp['two'] = two;
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
                checkbox: true
            }, {
                field: 'khh',
                title: '客户号',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'zjzh',
                title: '资金账号',
                align: 'center',
                valign: 'middle'
            },{
                field: 'bzj_jys',
                title: '交易所<br />保证金',
                align: 'center',
                valign: 'middle',
                sortable:true,
            },{
                field: 'bzj',
                title: '公司<br />保证金',
                align: 'center',
                valign: 'middle',
                sortable:true,
            }, {
                field: 'brjc',
                title: '当日权益',
                align: 'center',
                valign: 'middle',
                sortable:true,
            },{
                field: 'bzj_rate',
                title: '公司<br />风险度',
                align: 'center',
                valign: 'middle',
                sortable:true,
                formatter:function (value, row, index){
                    if(value == 0) return value;
                    var str=Number(value*100).toFixed(2);
                    str+="%";
                    return str;
                }
            },{
                field: 'jys_rate',
                title: '交易所<br />风险度',
                align: 'center',
                valign: 'middle',
                sortable:true,
                formatter:function (value, row, index){
                    if(value == 0) return value;
                    var str=Number(value*100).toFixed(2);
                    str+="%";
                    return str;
                }
            },{
                field: 'pz1',
                title: '品种A<br />(交易所)',
                align: 'center',
                valign: 'middle'
            },{
                field: 'pz1_rate',
                title: '品种A<br />集中度',
                align: 'center',
                valign: 'middle',
                sortable:true,
                formatter:function (value, row, index){
                    if(value == 0) return value;
                    var str=Number(value*100).toFixed(2);
                    str+="%";
                    return str;
                }
            },{
                field: 'exp1',
                title: '品种A<br />敞口',
                align: 'center',
                valign: 'middle',
                sortable:true,
                formatter:function (value, row, index){
                    if(value == 0) return value;
                    var str=Number(value*100).toFixed(2);
                    str+="%";
                    return str;
                }
            },{
                field: 'yyb',
                title: '营业部',
                align: 'center',
                valign: 'middle'
            },{
                field: 'khjl',
                title: '客户经理',
                align: 'center',
                valign: 'middle'
            },{
                field: 'khxm',
                title: '客户姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'phone',
                title: '手机号',
                align: 'center',
                valign: 'middle'
            },{
                field: 'jgbz',
                title: '客户类型',
                align: 'center',
                valign: 'middle',
                formatter:function (value, row, index){
                    if(value == 3) {
                        return '特殊客户';
                    } else if(value == 2){
                        return '自营';
                    } else if(value == 1) {
                        return '机构';
                    } else {
                        return '个人';
                    }
                }
            },{
                field: 'rq',
                title: '日期',
                align: 'center',
                valign: 'middle',
                sortable:true,
            }],
        }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
