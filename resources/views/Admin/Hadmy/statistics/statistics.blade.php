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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    @component('admin.widgets.toolbar')
                        @slot('listsOperation')
                        @endslot

                        @slot('operation')
                        @endslot
                    @endcomponent

                    @component('admin.widgets.search-group')
                        @slot('searchContent')
                                <input type="hidden" id="tzjh_account" value="{{ $tzjh_account }}">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="ip" placeholder="ip">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="mac" placeholder="mac">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="version" placeholder="版本号">
                            </div>
                        @endslot
                    @endcomponent
                    <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="chart">
                <canvas id="charts2"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart">
                <canvas id="charts3"></canvas>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{URL::asset('/js/admin/rpa/statistics/utils.js')}}"></script>
<script src="{{URL::asset('/js/admin/Hadmy/statistics/statistics.js')}}"></script>
<script>
    var charts2 = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    {{ $time['single'] }},
                    {{ $time['total'] }}
                ],
                backgroundColor: [
                    window.chartColors.blue,
                    window.chartColors.red,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                '在线时长',
                '总时长'
            ]
        },
        options: {
            title: {
                display: true,
                text: '登录时长统计(分钟)'
            },
            responsive: true
        }
    };
    var charts3 = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    {{ $login['single'] }},
                    {{ $login['total'] }}
                ],
                backgroundColor: [
                    window.chartColors.blue,
                    window.chartColors.red,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                '登录次数',
                '总次数'
            ]
        },
        options: {
            title: {
                display: true,
                text: '客户登录次数统计'
            },
            responsive: true
        }
    };
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
    window.myRadar = new Chart(document.getElementById('charts2'), charts2);
    window.myRadar = new Chart(document.getElementById('charts3'), charts3);
</script>
@endsection