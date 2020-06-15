$(function(){
    var myChart = echarts.init(document.getElementById('echart'), 'walden');
    var option = {
        title: {
            text: '',
            subtext: ''
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: [('Sales'), ('Orders')]
        },
        toolbox: {
            show: false,
            feature: {
                magicType: {show: true, type: ['stack', 'tiled']},
                saveAsImage: {show: true}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: Orderdata.column
        },
        yAxis: {},
        grid: [{
            left: 'left',
            top: 'top',
            right: '10',
            bottom: 30
        }],
        series: [{
            name: __('Sales'),
            type: 'line',
            smooth: true,
            areaStyle: {
                normal: {}
            },
            lineStyle: {
                normal: {
                    width: 1.5
                }
            },
            data: Orderdata.paydata
        },
            {
                name: __('Orders'),
                type: 'line',
                smooth: true,
                areaStyle: {
                    normal: {}
                },
                lineStyle: {
                    normal: {
                        width: 1.5
                    }
                },
                data: Orderdata.createdata
            }]
    };

    myChart.setOption(option);

    setInterval(function () {
        Orderdata.column.push((new Date()).toLocaleTimeString().replace(/^\D*/, ''));
        var amount = Math.floor(Math.random() * 200) + 20;
        Orderdata.createdata.push(amount);
        Orderdata.paydata.push(Math.floor(Math.random() * amount) + 1);

        if (Orderdata.column.length >= 20) {
            Orderdata.column.shift();
            Orderdata.paydata.shift();
            Orderdata.createdata.shift();
        }
        myChart.setOption({
            xAxis: {
                data: Orderdata.column
            },
            series: [{
                name: __('Sales'),
                data: Orderdata.paydata
            },
                {
                    name: __('Orders'),
                    data: Orderdata.createdata
                }]
        });
    }, 2000);

    /*
     * 初始化
     */
    function init(){
        bindEvent();

        $(window).resize(function () {
            myChart.resize();
        });

        $(document).on("click", ".btn-checkversion", function () {
            top.window.$("[data-toggle=checkupdate]").trigger("click");
        });

        $(document).on("click", ".btn-refresh", function () {
            setTimeout(function () {
                myChart.resize();
            }, 0);
        });
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        //视频榜单自定义查询
        $('#pjax-container .videoTop #topType label').on('click', function(e){
            //允许重新点击已选按钮
            // if(!$(this).hasClass('active')){
                $type = $(this).data('v');
                //自定义按钮
                if('custom' == $type){
                    $('#pjax-container .videoTop .custom').toggle();
                    return;
                }
                initVideoTopList($type);
                $('#pjax-container .videoTop .custom').css('display','none')
            // }
        });

        //datepicker
        $('#pjax-container .videoTop .custom #reservation').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            initVideoTopList({
                startDate: picker.startDate.format('YYYY-MM-DD'),
                endDate: picker.endDate.format('YYYY-MM-DD'),
            });
        });
    }

    //请求数据
    function initVideoTopList(param){
        $.ajax({
            method: 'get',
            url: '/admin/KpiVideos/getVideoTopList',
            data: {
                param:param
            },
            success: function (json) {
                console.log(json);
                if(200 == json.code){
                }else{
                }
            }
        });
    }

    init();
});
