$(function () {

    /**
     * 初始化
     */
    function init() {

        bindEvent();
    }

    /**
     * 绑定
     */
    function bindEvent() {

        footprint();

        //计时
        let obj = $('');
        let st = new Date('2017-06-01');
        setInterval(function () {
            let activeDate = new Date();
            let diffDate = activeDate.getTime() - st.getTime();
            let days = Math.floor(diffDate / (24 * 3600 * 1000));
            let leave1 = diffDate % (24 * 3600 * 1000);
            let hours = Math.floor(leave1 / (3600 * 1000));
            let leave2 = leave1 % (3600 * 1000);
            let minutes = Math.floor(leave2 / (60 * 1000));
            let leave3 = leave2 % (60 * 1000);
            let seconds = Math.round(leave3 / 1000);
            let accumulated_time = "<i>" + days + " </i>天<i> " + hours + " </i>时<i> " + minutes + " </i>分<i> " + seconds + " </i>秒";
            $('#pjax-container .accumulated_time').html(accumulated_time);
        }, 1000);
    }

    /**
     * 我的足迹
     */
    function footprint() {
        $.post('/admin/sys_chart/footprint', function(json){
            let label = json.pie_labels;
            let count = json.pie_count;
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = {
                labels: label,
                datasets: [
                    {
                        data: count,
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                    }
                ]
            };
            var pieOptions = {
                legend: {
                    display: false,
                },
            };
            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions
            });
        });
    }

    init();

});