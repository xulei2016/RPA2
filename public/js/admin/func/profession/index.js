

$(function(){
    let selectInfo = [];
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
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= "/admin/rpa_profession_change/export?"+$url;
        });

        //导出选中
        $("#pjax-container section.content #toolbar #export").on('click', function(){
            var ids = RPA.getIdSelections('#tb_departments');
            var condition = getSearchGroup();
            $url = urlEncode(condition);
            location.href= "/admin/rpa_profession_change/export?"+$url+'&id='+ids;
        });

    }

    /**
     * 确认单个 
     * @param  id 
     */
    function confirmOne(id){
        swal({
            title: '提示',
            text: "是否确认已手动修改并上报客户信息",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确认',
            cancelButtonText: '取消'
        }).then(function(isConfirm) {
            if (isConfirm.value) {
                $.ajax({
                    url:'/admin/rpa_profession_change/confirmOne',
                    data:{id:id},
                    dataType:'json',
                    type:'post',
                    success:function(r){
                        if(r.code == 200) {
                            swal('提示', '操作成功','success');
                            // location.reload();
                        } else {
                            swal('提示', r.info, 'error');
                        }}
    
                });
            }
        })
    }

    
    /**
     * 获取模糊参数
     */
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            status : $("#pjax-container #search-group #status").val(),
            zjzh : $("#pjax-container #search-group #zjzh").val()
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
        };
        var param = {
            url: '/admin/rpa_profession_change/list',
            columns: [{
                checkbox: true
            },{
                field: 'name',
                title: '姓名',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'zjzh',
                title: '资金账号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'phone',
                title: '手机号',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'sfz',
                title: '身份证',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'profession',
                title: '职业',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'statusName',
                title: '状态',
                align: 'center',
                valign: 'middle'
            },{
                field: 'created_at',
                title: '申请时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'operation',
                title: '操作人',
                align: 'center',
                valign: 'middle'
            },{
                field: 'updated_at',
                title: '操作时间',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                events: {
                    "click .confirmOne":function (e, value, row, index){
                        var id = row.id;
                        confirmOne(id);
                    }
                },
                formatter: function(value, row, index){
                    var result = '';
                    if(row.status == 5 || row.status == 6) {
                        result = "<a href='javascript:;' class='btn btn-sm btn-primary confirmOne' item-id='"+value+"' onclick='confirmOne("+value+")' title='确认'>确认</a>";
                    }
                    return result;
                }
            }]
        }

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});
