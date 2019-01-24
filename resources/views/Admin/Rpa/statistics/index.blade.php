@extends('admin.layouts.wrapper-content')

@section('content')
<script type="text/javascript" src="{{URL::asset('/include/charts/Chart.bundle.js')}}"></script>
<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>

<meta charset="utf-8">
<div class="row">
    <div class="col-lg-8">
        <div class="chart">
            <canvas id="trueorfail"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart">
            <canvas id="charts"></canvas>
        </div>
    </div>
    <div class="col-lg-11">
        <div class="chart">
            <div class="title text-center">RPA数据统计图表</div>
            <div class="taskType">
                <select name="task" id="taskType" class="btn btn-default btn-sm">
                    @foreach($taskname as $v)
                        <option value="{{$v['name']}}">{{$v['bewrite']}}</option>
                    @endforeach
                </select>
                <select name="days" id="taskDays" class="btn btn-default btn-sm">
                    <option value="7" selected>7天</option>
                    <option value="15">15天</option>
                    <option value="30">30天</option>
                </select>
            </div>
            <canvas id="canvas"></canvas>
        </div>
    </div>

</div>


<script type="text/javascript" src="{{URL::asset('/js/admin/rpa/statistics/utils.js')}}"></script>
	<script>
        var setChartConfig = () => {
            let mylabels = '';
            let mydata1 = '';
            let mydata2 = '';
            let task = $('.chart .taskType select[name="task"]').val();
            let day = $('.chart .taskType select[name="days"]').val();
            $.ajax({
                async:false,
                url:"/admin/rpa_statistics/getData",
                type:'post',
                data:{task:task,day:day, _token:LA.token},
                dataType:'json',
                success:function(json){
                    for(let i in json){
                        mylabels = mylabels+i+',';
                        if(json[i].hasOwnProperty('success')){
                            mydata1+=json[i]['success'].length+',';
                        }else{
                            mydata1+='0,';
                        }
                        if(json[i].hasOwnProperty('fail')){
                            mydata2+=json[i]['fail'].length+',';
                        }else{
                            mydata2+='0,';
                        }
                    }
                    config.data.labels = mylabels.split(',');
                    config.data.datasets.forEach(function(dataset) {
                        if(dataset.label == '成功'){
                            dataset.data = mydata1.split(',');
                        }else{
                            dataset.data = mydata2.split(',');
                        }
                    });
                }
            });
        }
		var config = {
			type: 'line',
			data: {
				labels: '',
				datasets: [{
					label: '成功',
                    show: true, 
					borderColor: window.chartColors.blue,
					backgroundColor: window.chartColors.blue,
					data: '',
					fill: false,
				}, {
					label: '失败',
					borderColor: window.chartColors.red,
					backgroundColor: window.chartColors.red,
					data: '',
                    fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: false,
                        text: 'RPA数据统计图表'
                    },
                    tooltips: {
                        mode: 'index',
                        callbacks: {
                            // Use the footer callback to display the sum of the items showing in the tooltip
                            footer: function(tooltipItems, data) {
                                var sum = 0;

                                tooltipItems.forEach(function(tooltipItem) {
                                    sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                });
                                return 'Sum: ' + sum;
                            },
                        },
                        footerFontStyle: 'normal'
                    },
                    hover: {
                        mode: 'index',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                show: true,
                                labelString: '日期'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                show: true,
                                labelString: '次数'
                            }
                        }]
                    }
                }
		};

        var randomScalingFactor = function() {
			return Math.round(Math.random() * 100);
		};

		var color = Chart.helpers.color;
		var charts = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
						{{ $info['success'] }},
						{{ $info['fail'] }}
					],
					backgroundColor: [
						window.chartColors.blue,
						window.chartColors.red,
						window.chartColors.yellow,
					],
					label: 'Dataset 1'
				}],
				labels: [
					'成功',
					'失败'
				]
			},
			options: {
                title: {
					display: true,
					text: 'RPA执行统计'
				},
				responsive: true
			}
		};
        var t = [];
        @foreach($taskss as $v)
            t.push("{{$v}}");
        @endforeach
        var trueorfail_config = {
            type: 'bar',
            data: {
                labels: t,
//                labels: ['taskdistribution','bak','zwtx','IDidentification','sdxTestWirte','SupervisionSF','SupervisionCFA','CustomerGroupings'],
                datasets: [{
                    label: '执行成功',
                    data: [{{ $tasks['success'] }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                },
                {
                    label: '执行失败',
                    data: [{{ $tasks['fail'] }}],
                    backgroundColor: window.chartColors.grey
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
                title: {
					display: true,
					text: 'RPA任务执行统计'
				},
            }
        };

        // Define a plugin to provide data labels
		Chart.plugins.register({
			afterDatasetsDraw: function(chart) {
				var ctx = chart.ctx;

				chart.data.datasets.forEach(function(dataset, i) {
					var meta = chart.getDatasetMeta(i);
					if (!meta.hidden) {
						meta.data.forEach(function(element, index) {
							// Draw the text in black, with the specified font
							ctx.fillStyle = '#ccc';

							var fontSize = 16;
							var fontStyle = 'normal';
							var fontFamily = '微软雅黑';
							ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

							// Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

							// Make sure alignment settings are correct
							ctx.textAlign = 'center';
							ctx.textBaseline = 'middle';

							var padding = 5;
							var position = element.tooltipPosition();
							ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
						});
					}
				});
			}
        });
        setChartConfig();

        var trueorfail = document.getElementById("trueorfail");
        window.rpa_trueorfail = new Chart(trueorfail, trueorfail_config);

        window.myRadar = new Chart(document.getElementById('charts'), charts);

        var ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = new Chart(ctx, config);

        //监听按钮选择
        document.getElementById('taskType').addEventListener('change', function() {
            setChartConfig();
			window.myLine.update();
        });
        document.getElementById('taskDays').addEventListener('change', function() {
            setChartConfig();
			window.myLine.update();
		});
	</script>
@endsection