$(function () {
    var zTreeObj;
    var oTable;
    var selectNode = {};

    /*
    * 初始化
    */
    function init() {
        bindEvent();
        initFlowTree();
        oTable = new RPA.TableInit();
        pageNation(oTable);
    }

    //绑定事件
    function bindEvent() {
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

        document.addEventListener('operationFlow', function(){
            $('#tb_departments').bootstrapTable('refresh');
        });
    }


    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        status: {
            rename: {
                name: '',
                id: ''
            }
        },
        view: {
            addHoverDom: false,
            removeHoverDom: false,
            selectedMulti: false
        },
        check: {
            enable: false,
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "pid",
                rootPId: 0
            }
        },
        edit: {
            enable: false,
            showRemoveBtn: false,
            showRenameBtn: false,
        },
        callback: {
            beforeDrag:function(){return false;}, //禁止拖动
            onClick: zTreeOnClick
        }
    };

    //初始化根目录
    function initFlowTree() {
        $.get('/admin/sys_flow_mine/getMenuTree', {}, function (json) {
            if (200 == json.code) {
                var zNodes = json.data;
                zTreeObj = $.fn.zTree.init($("#flowTree"), setting, zNodes);
                fuzzySearch('flowTree', '#searchFlow', null, true); //初始化模糊搜索方法
            } else {
                Swal('Oops...', '部门列表加载失败！', 'error');
            }
        });
    }


    //单击事件
    function zTreeOnClick(event, treeId, treeNode) {
        var nodeType = treeNode.type;
        if(nodeType === 'group') { //
            selectNode = { group_id : treeNode.id}
        } else { // flow
            selectNode = {flow_id : treeNode.id.replace('flow_', '')}
        }
        $('#tb_departments').bootstrapTable('refresh');
    }

    //get searchGroup
    function getSearchGroup(){
        //特殊格式的条件处理
        var temp = {
            name : $("#pjax-container #search-group #name").val(),
            type : $("#pjax-container #search-group #type").val(),
        };
        if(selectNode.group_id) {
            temp.group_id =  selectNode.group_id
        }
        if(selectNode.flow_id) {
            temp.flow_id =  selectNode.flow_id
        }
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
            url: '/admin/sys_flow_mine/list',
            columns: [{
                valign: 'middle',
                checkbox: true,
            }, {
                field: 'instanceName',
                title: '实例名称',
                align: 'center',
                valign: 'middle',
                formatter:function(value, row, index) {
                    var id = row.id;
                    var result = '';
                    result += " <a href='javascript:;' onclick=\"operation($(this));\" url='/admin/sys_flow_mine/"+id+"' title='查看'>"+value+"</a>";
                    return result;
                }
            }, {
                field: 'nodeName',
                title: '所处节点',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'statusName',
                title: '节点状态',
                align: 'center',
                valign: 'middle',
            }, {
                field: 'created_at',
                title: '创建时间',
                align: 'center',
                valign: 'middle',
            },{
                field: 'id',
                title: '操作',
                align: 'center',
                valign: 'middle',
                formatter: function(value, row, index){
                    var id = value;
                    var result = '';
                    result += " <a href='javascript:;' class='btn btn-sm btn-primary' onclick=\"operation($(this));\" url='/admin/sys_flow_mine/"+id+"' title='查看'>查看</a>";
                    return result;
                }
            }],
        };

        //初始化表格
        oTable.Init('#tb_departments', param);
    }

    init();
});