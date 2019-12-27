$(function () {
    var editId;
    var zTreeObj;
    var selectNode;

    /*
    * 初始化
    */
    function init() {
        bindEvent();
        initSearchAdmin();
    }

    //绑定事件
    function bindEvent() {
        $(document).one('click', '#searchAdminConfirm', function(){
            if(!selectNode) return false;
            selectNode.parentId = $(this).parents('.modal').attr('id');
            var newEvent = document.createEvent("CustomEvent");
            newEvent.initCustomEvent("searchAdmin",true,true, selectNode);
            document.dispatchEvent(newEvent);
        })
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
    function initSearchAdmin() {
        $.get('/admin/sys_dept/getMenus', {}, function (json) {
            if (200 == json.code) {
                var zNodes = json.data;
                zTreeObj = $.fn.zTree.init($("#searchAdminTree"), setting, zNodes);
                fuzzySearch('searchAdminTree', '#searchAdminSearch', null, true); //初始化模糊搜索方法
            } else {
                Swal('Oops...', '部门列表加载失败！', 'error');
            }
        });
    }


    //单击事件
    function zTreeOnClick(event, treeId, treeNode) {
        var nodeType = treeNode.type;
        editId = treeNode.id;
        if(nodeType === 'node') { //节点 展示员工信息 以及下属组织

        } else { //员工
            selectNode = treeNode;
            $('#selectAdmin').html(treeNode.name);
        }
    }

    init();
});