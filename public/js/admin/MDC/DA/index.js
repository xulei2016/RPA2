/**
 *登录用户热力图
 */
(function () {

    $(document).ready(function () {
            if (!window.hasOwnProperty('BMap')) {
                Swal.fire({
                    type: 'warning',
                    title: '未检测到百度脚本，请刷新尝试！',
                    confirmButtonText: '刷新'
                }).then((result) => {
                    if (result.value) {
                        location.reload();
                    };
                });
                return;
            }

            //热力图
            var map = new BMap.Map("heatmap");          // 创建地图实例

            var point = new BMap.Point(117.235022, 31.832399);
            map.centerAndZoom(point, 8);             // 初始化地图，设置中心点坐标和地图级别
            map.enableScrollWheelZoom(); // 允许滚轮缩放

            var config = {
                'visible': true,   //visible 热力图是否显示,默认为true
                'opacity': 30,      //opacity 热力的透明度,1-100
                'radius': 8,       //radius 势力图的每个点的半径大小
                'gradient': {      //gradient  {JSON} 热力图的渐变区间 . gradient如下所示
                    .2: 'rgb(0, 255, 255)',
                    .5: 'rgb(0, 110, 255)',
                    .8: 'rgb(100, 0, 255)'
                }
            };
            heatmapOverlay = new BMapLib.HeatmapOverlay(config);
            map.addOverlay(heatmapOverlay);

            //频次柱状图
            var color = Chart.helpers.color;
            var barChartData = {
                labels: ['01月', '02月', '03月', '04月', '05月', '06月', '07月', '08月', '09月', '10月', '11月', '12月'],
                datasets: [{
                    label: '登录频次（月）',
                    backgroundColor: color('rgb(255, 99, 132)').alpha(0.5).rgbString(),
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1,
                    data: []
                }]
            };
            var barChartData2 = {
                labels: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'],
                datasets: [{
                    label: '登录频次(月)',
                    backgroundColor: color('rgb(105,196,255)').alpha(0.5).rgbString(),
                    borderColor: 'rgb(77,221,255)',
                    borderWidth: 1,
                    data: []
                }]
            };
            var barChartData3 = {
                labels: [],
                datasets: [{
                    label: '区域排名(省份)',
                    backgroundColor: color('rgb(255, 99, 132)').alpha(0.5).rgbString(),
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1,
                    data: []
                }]
            };
            var barChartData4 = {
                labels: [],
                datasets: [{
                    label: '区域排名(城市)',
                    backgroundColor: color('rgb(105,196,255)').alpha(0.5).rgbString(),
                    borderColor: 'rgb(77,221,255)',
                    borderWidth: 1,
                    data: []
                }]
            };

            var ctx = document.getElementById('tfrequency').getContext('2d');
            var ctx2 = document.getElementById('tfrequency_day').getContext('2d');
            var ctx3 = document.getElementById('tfrequency_region').getContext('2d');
            var ctx4 = document.getElementById('tfrequency_city').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '累计登录频次（月）'
                    }
                }
            });
            window.myBar2 = new Chart(ctx2, {
                type: 'bar',
                data: barChartData2,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '累计登录频次（日）'
                    }
                }
            });
            window.myBar3 = new Chart(ctx3, {
                type: 'horizontalBar',
                data: barChartData3,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '区域排名'
                    }
                }
            });
            window.myBar4 = new Chart(ctx4, {
                type: 'horizontalBar',
                data: barChartData4,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '区域排名'
                    }
                }
            });

            initChart();


            //根据条件查询信息
            $('#pjax-container #search-group #formSearch #search-btn').click(function () {
                initHeatMap();
            });

            //enter键盘事件
            $("#pjax-container #search-group #formSearch input").keydown(function (event) {
                event = event ? event : window.event;
                // if (event.keyCode == 13) {
                //     initHeatMap();
                // }
            });

            function initChart() {
                if (!isSupportCanvas()) {
                    alert('热力图目前只支持有canvas支持的浏览器,您所使用的浏览器不能使用热力图功能~');
                    return;
                }

                let form = $("#pjax-container #search-group #formSearch");
                let config = {
                    // type: form.find("#type").val(),
                    // user: form.find("#user").val(),
                    startTime: form.find("#startTime").val(),
                    endTime: form.find("#endTime").val()
                };

                initBar(config);
                initHeatMap(config);
                initHorizontalBar(config);
            }

            //设置柱状图数据
            function initBar(config) {
                let ChartData = barChartData.datasets[0].data;
                let ChartData2 = barChartData2.datasets[0].data;
                $.get('/admin/mdc/heat_map/getTfrequency', {
                    from_login_time: config.startTime,
                    to_login_time: config.endTime
                }, function (json) {
                    if (200 == json.code) {
                        let data = json.data;
                        data.months.map(function (e) {
                            if (e.months) {
                                return ChartData.push(e.count);
                            }
                        });
                        let d = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                        for (let i = 0; i < data.days.length; i++) {
                            let I = data.days[i].days.replace(/\b(0+)/gi, "");   //生产不存在此问题，上生产请删除此操作
                            d[I - 1] = data.days[i].count;
                        }
                        d.map(function (e) {
                            return ChartData2.push(e);
                        });
                        window.myBar.update();
                        window.myBar2.update();
                    } else {
                        swal('提示', '柱状图数据拉取失败！！！', 'warning');
                    }

                });
            }

            //初始化
            function initHorizontalBar(config) {
                let ChartData3 = barChartData3;
                let ChartData4 = barChartData4;
                $.get('/admin/mdc/heat_map/getArea', {
                    from_login_time: config.startTime,
                    to_login_time: config.endTime
                }, function (json) {
                    if (200 == json.code) {
                        let data = json.data;
                        for (let i = 0; i < data.region.length; i++) {
                            ChartData3.labels.push(data.region[i].region);
                            ChartData3.datasets[0].data.push(data.region[i].count);
                        }
                        for (let i = 0; i < data.city.length; i++) {
                            ChartData4.labels.push(data.city[i].city);
                            ChartData4.datasets[0].data.push(data.city[i].count);
                        }
                    } else {
                        swal('提示', '区域排布图数据拉取失败！！！', 'warning');
                    }
                    window.myBar3.update();
                    window.myBar4.update();
                });
            }

            //初始化
            function initHeatMap(config) {
                $.get('/admin/mdc/heat_map/getPosition', {
                    from_login_time: config.startTime,
                    to_login_time: config.endTime
                }, function (points) {
                    heatmapOverlay.setDataSet({data: points, max: 100});
                    heatmapOverlay.show();
                });
            }

            //判断浏览区是否支持canvas
            function isSupportCanvas() {
                var elem = document.createElement('canvas');
                return !!(elem.getContext && elem.getContext('2d'));
            }
        }
    );

})();


