$(function () {

    /**
     * 初始化
     */
    function init() {

        bindEvent();

        showTodoList();
    }

    /**
     * 绑定
     */
    function bindEvent() {

        pieChart();

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

        document.addEventListener('operationFlow', function(){
            showTodoList();
        })
    }

    function showTodoList(){
        $.get('/admin/sys_flow_mine/todoList', function(res){
            var html = '';
            if(res.data.length ===  0) {
                html = "暂无待办流程";
                $('.flow .card-body').html(html);
                return false;
            }

            $.each(res.data, function(index, item){
                html += ' <div class="flow">\n' +
                    '         <div class="title"><span class="fa fa-angle-right"></span> '+item.flow_title+'-'+item.node_title+'</div>\n' +
                    '               <div class="flow-body">\n' +
                    '                   <a href="javascript:void(0)" onclick="operation($(this));" url="/admin/sys_flow_mine/'+item.id+'">\n' +
                    '                                <span class="flow-title">'+item.title+'</span>\n' +
                    '                    </a>\n' +
                    '                    <span>'+item.created_at+'</span>\n' +
                    '          </div>\n' +
                    '      </div>'
            });
            $('.flow .card-body').html(html);
        })
    }

    /**
     * 首页图表
     */
    function pieChart() {

        //我的足迹
        $.post('/admin/sys_chart/footprint', function(json){
            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: json.pie_count,
                        backgroundColor: ['#f56954', '#3c8dbc', '#f39c12', '#00c0ef', '#2f2f2f', '#00a65a', '#d2d6de'],
                        label: 'Dataset 1'
                    }],
                    labels: json.pie_labels
                },
                options: {
                    responsive: true
                }
            };
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieChart = new Chart(pieChartCanvas, config);
        });

    }

    init();

});