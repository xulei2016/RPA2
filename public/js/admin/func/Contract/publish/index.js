$(function(){
    let url_prefix = '/admin/rpa_contract_publish/';

    let selectInfo = [];
    /*
     * 初始化
     */
    function init(){
        bindEvent();

        //1.初始化Table
        var oTable = new RPA.TableInit();
        pageNation(oTable);

        // 时间插件
        let jsondate = '#date';
        laydate.render({elem: jsondate, type: 'date'});
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
            jys_id : $("#pjax-container #search-group #jys_id").val(),
            type : $("#pjax-container #search-group #type").val(),
            date : $("#pjax-container #search-group #date").val(),
            date_type:$("#pjax-container #search-group #date_type").val()
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
                field: 'date',
                title: '日期',
                align: 'center',
                valign: 'middle',
                sortable:true
            }, {
                field: 'count',
                title: '数量',
                align: 'center',
                valign: 'middle'
            }],
            sortOrder:'asc',
            detailView: true,
            onExpandRow: function(index, row, $detail) {
                var subTable = $detail.html('<table></table>').find('table');
                var html = "";
                html += "<table class='table'>";
                html += "<thead>";
                html += "<tr style='height: 30px;'>";
                html += "<th class='text-center'>交易所</th>";
                html += "<th class='text-center'>品种</th>";
                html += "<th class='text-center'>上市合约</th>";
                html += "<th class='text-center'>下市合约</th>";
                html += "<th class='text-center'>调整合约</th>";
                html += "<th class='text-center'>类型</th>";
                html += "<th class='text-center'>交易手续费</th>";
                html += "<th class='text-center'>日内费用</th>";
                html += "<th class='text-center'>调整前手续费</th>";
                html += "<th class='text-center'>调整前日内费用</th>";
                html += "<th class='text-center'>调整日期</th>";
                html += "</tr>";
                html += "</thead>";
                $.ajax({
                    url:url_prefix+'getByDate',
                    data:{date:row.date},
                    dataType:'json',
                    async: false,
                    success:function(r) {
                        if(r.code == 200) {
                            var sxf;
                            var rnfy;
                            var type;
                            var hydm_off;
                            $.each(r.data, function(index, item){
                                if(item.type == 1) { //上下市
                                    type = '新合约上市';
                                    sxf = item.contract.xhy_jysxf;
                                    rnfy = item.contract.xhy_rnfy;
                                    hydm_on = item.hydm_on;
                                    hydm_off = item.hydm_off;
                                    hydm_tz = '-';
                                    sxf_before = '-';
                                    rnfy_before = '-';
                                } else {
                                    type = "交割月前第"+item.contract.tz_month+"月的第"+item.contract.tz_day+"个交易日";
                                    sxf = item.contract.tz_jysxf;
                                    rnfy = item.contract.tz_rnfy;
                                    hydm_on = '-';
                                    hydm_off = '-';
                                    hydm_tz = item.hydm_on;
                                    sxf_before = item.contract.has_online == 1?item.contract.xhy_jysxf:item.contract.pzfy_jysxf;
                                    rnfy_before = item.contract.has_online == 1?item.contract.xhy_rnfy:item.contract.pzfy_rnfy;
                                }
                                rnfy = rnfy == null ? '-' : rnfy;
                                rnfy_before = rnfy_before == null ? '-' : rnfy_before;
                                html += "<tr align='center'>"
                                    + "<td>" + item.jys + "</td>"
                                    + "<td>" + item.pz + "</td>"
                                    + "<td>" + hydm_on + "</td>"
                                    + "<td>" + hydm_off + "</td>"
                                    + "<td>" + hydm_tz + "</td>"
                                    + "<td>" + type + "</td>"
                                    + "<td>" + sxf + "</td>"
                                    + "<td>" + rnfy + "</td>"
                                    + "<td>" + sxf_before + "</td>"
                                    + "<td>" + rnfy_before + "</td>"
                                    + "<td>" + item.real_date + "</td>"
                                    + "</tr>";
                            })
                        }
                    }

                })
                subTable.html(html);
            }
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
