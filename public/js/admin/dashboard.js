$(function () {

    let options = {
        nextLabel: "下一步 &rarr;",
        prevLabel: "&larr; 上一步",
        skipLabel: "跳过",
        doneLabel: "知道了",
    };

    /**
     * 初始化
     */
    function init() {

        bindEvent();

        showTodoList();

        intro();

    }

    /**
     * 绑定
     */
    function bindEvent() {

        pieChart();

        //计时
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

        document.addEventListener('operationFlow', function () {
            showTodoList();
        })
    }

    function showTodoList() {
        $.get('/admin/sys_flow_mine/todoList', function (res) {
            let html = '';
            if (res.data.length === 0) {
                html = "暂无待办流程";
                $('.flow .card-body').html(html);
                return false;
            }

            $.each(res.data, function (index, item) {
                html += ' <div class="flow">\n' +
                    '         <div class="title"><span class="fa fa-angle-right"></span> ' + item.flow_title + '-' + item.node_title + '</div>\n' +
                    '               <div class="flow-body">\n' +
                    '                   <a href="javascript:void(0)" onclick="operation($(this));" url="/admin/sys_flow_mine/' + item.id + '">\n' +
                    '                                <span class="flow-title">' + item.title + '</span>\n' +
                    '                    </a>\n' +
                    '                    <span>' + item.created_at + '</span>\n' +
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
        $.post('/admin/sys_chart/footprint', function (json) {
            let config = {
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
            let pieChartCanvas = $('#pieChart').get(0).getContext('2d');
            new Chart(pieChartCanvas, config);
        });

    }

    function intro() {
        let localVersion = localStorage.getItem('version') ? JSON.parse(localStorage.getItem('version')) : {};
        if (Object.keys(localVersion).length > 0 && (RPA.version.index === localVersion.index))
            return;

        $('aside.main-sidebar').attr({'data-step': 1, 'data-intro': '这里是侧边菜单栏', 'data-position': 'left'});
        $('.content-wrapper .tags-view-container').attr({
            'data-step': 2,
            'data-intro': '这里是快捷菜单栏',
            'data-position': 'top'
        });
        $('.drawerPanel-container .drawerPanel').attr({
            'data-step': 3,
            'data-intro': '这里是个性化布局设置',
            'data-position': 'right'
        });

        localVersion['index'] = RPA.version.index;
        localStorage.setItem('version', JSON.stringify(localVersion));

        introJs().setOption(options).onafterchange(function (ele) {
            if ($(ele).hasClass('drawerPanel') && $(ele).parent().hasClass('show')) {
                $(ele).find('.handle-button').click();
            }
        }).start();
    }

    init();

});