
$(function () {
    var editId;
    var uid;
    var zTreeObj;
    var selectNode;

    /*
    * 初始化
    */
    function init() {
        bindEvent();
        initMenus();
    }

    //绑定事件
    function bindEvent() {
        //新增员工
        $(document).on('click','.trans', function(){
            swal.close();
            var dept_id = $(this).attr('data-item'); //部门id
            loadModal('/admin/sys_admin/create?dept_id='+dept_id)
        });

        //编辑员工
        $(document).on('click', '.edit_user', function(){
            var id = $(this).parent().attr('item-id');
            loadModal('/admin/sys_admin/'+id+'/edit');
        });

        // 新增员工
        $(document).on('click', '.addUser', function (){
            loadModal('/admin/sys_admin/create?dept_id='+editId);
        });

        //修改部门
        $(document).on('click', '#deptDetail button.updateDept', function(){
            loadModal('/admin/sys_dept/'+editId+'/edit');
        });
        // 更新用户事件
        document.addEventListener('updateUser', function () {
            showUserHtml();
        });

        //新增用户事件
        document.addEventListener('addUser', function () {
            initMenus();
            showDepartmentHtml();
            showUserHtml();
        });

        document.addEventListener('deptPostRelation', function(){
            showDepartmentHtml();
        });

        // 更新部门节点事件
        document.addEventListener('updateDepartment', function(){
            showDepartmentHtml();
        });

        // 新增岗位
        $(document).on('click', '#postList .addPost', function (){
            loadModal("/admin/sys_dept_post_relation/create?dept_id="+editId);
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
            addHoverDom: addHoverDom,
            removeHoverDom: removeHoverDom,
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
            enable: true,
            showRemoveBtn: setRemoveBtn,
            showRenameBtn: setRenameBtn,
            renameTitle: "重命名",
            removeTitle: "删除",
            addTitle:'新增'
        },
        callback: {
            onRename: zTreeOnRename,
            beforeRemove: zTreeBeforeRemove,
            beforeRename: zTreeBeforeRename,
            beforeDrag:function(){return false;}, //禁止拖动
            onDrop: zTreeOnDrop,
            onClick: zTreeOnClick
        }
    };

    // 所有父节点 以及 员工 不显示删除按钮
    function setRemoveBtn(treeId, treeNode) {
        if(treeNode.isParent || treeNode.type == 'person') {
            return false;
        } else {
            return true;
        }
    }

    // 所有员工 不显示修改按钮
    function setRenameBtn(treeId, treeNode) {
        if(treeNode.type == 'person') {
            return false;
        } else {
            return true;
        }
    }

    //初始化根目录
    function initMenus() {
        $.get('/admin/sys_dept/getMenus', {}, function (json) {
            if (200 == json.code) {
                var zNodes = json.data;
                zTreeObj = $.fn.zTree.init($("#tree"), setting, zNodes);
                fuzzySearch('tree', 'section.content #searchFile', null, true); //初始化模糊搜索方法
            } else {
                Swal('Oops...', '部门列表加载失败！', 'error');
            }
        });
    }

    //加载modal
    function loadModal(url, type = 'lg'){
        $('#modal-'+type+' .modal-content').text('').load(url);
        $('#modal-'+type).modal('show');
    }
    //添加结点事件
    function addHoverDom(treeId, treeNode) {
        if(treeNode.type == 'person') {
            return false;
        }
        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) return;
        var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
            + "' title='新增' onfocus='this.blur();'></span>";
        sObj.after(addStr);
        var btn = $("#addBtn_" + treeNode.tId);
        if (btn) btn.bind("click", function () {
            Swal.fire({
                title: '请输入新节点名称',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                html:'<div class="text-right"><button class="btn btn-primary btn-sm trans" data-item="'+treeNode.id+'">新增员工</button></div>',
                showCancelButton: true,
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                showLoaderOnConfirm: true,
                preConfirm: (name) => {
                    return $.post('/admin/sys_dept', { method: 'post', name: name, pid: treeNode.id })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((json) => {
                json = json.value;
                if (200 == json.code) {
                    var zTree = $.fn.zTree.getZTreeObj("tree");
                    zTree.addNodes(treeNode, { id: json.data.id, pId: treeNode.id, name: json.data.name,type:'node' });
                    toastr.success(json.info);
                    return false;
                } else {
                    toastr.error(json.info);
                }
            })
        });
    }

    //单击事件
    function zTreeOnClick(event, treeId, treeNode) {
        var selectedNodes = zTreeObj.getSelectedNodes();
        var nodeType = treeNode.type;
        editId = treeNode.id;
        if(nodeType === 'node') { //节点 展示员工信息 以及下属组织
            $('#detail #nodeCard').show();
            $('#detail #personCard').hide();
            $('#detail #initCard').hide();
            // 部门一览
            var departmentHtml = "";
            $.each(treeNode.children, function(index, item) {
                if(item.type === 'node') {
                    departmentHtml += "<tr>" +
                        "<td>"+item.name+"</td>" +
                        "</tr>";
                }
            });
            $('#departmentList tbody').html(departmentHtml);
            showDepartmentHtml()
        } else { //员工
            uid =  editId.replace('admin_', ''); // 结果是 admin_111 这种形式
            $('#detail #personCard').show();
            $('#detail #nodeCard').hide();
            $('#detail #initCard').hide();
            showUserHtml();
        }
    }

    //部门节点信息展示
    function showDepartmentHtml(){
        if(!editId) return false;
        $.get('/admin/sys_dept/detail', {
            id:editId
        }).then((json) => {
            $('#nodeCard').html(json);
            return false;
        });
    }

    //展示员工详细信息
    function showUserHtml(){
        if(!uid) return false;
        $.get('/admin/sys_dept/getUserDetail', {
            id:uid
        }).then(res => {
            $('#personCard').html(res);
            return false;
        });
    }

    //移除结点事件
    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_" + treeNode.tId).unbind().remove();
    }

    //结点重命名
    function zTreeBeforeRename(treeId, treeNode, newName, isCancel) {
        if (isCancel) return true;
        if (newName == treeNode.name || '' == newName) return false;
    }

    //删除
    function zTreeOnDrop(event, treeId, treeNodes, targetNode, moveType) {
        //删除节点
        if (moveType) {
            $.post(`/admin/sys_dept/${treeNodes[0].id}`, {
                _method: 'PATCH',
                moveType: moveType,
                objId: targetNode.id,
            }).then((json) => {
                if (200 == json.code) {
                    toastr.success(json.info);
                } else {
                    toastr.error(json.info);
                }
            }, (e) => {
                Swal.fire('操作提示', '网络错误！', 'error');
            });
        }
    }

    //删除结点
    function zTreeBeforeRemove(treeId, treeNode) {
        Swal.fire({
            title: '确定删除吗?',
            text: "执行操作后无法取消!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '确认',
            cancelButtonText: '取消',
        }).then((result) => {
            if (result.value) {
                if (treeNode.isParent) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: '无法删除，该节点拥有子节点!'
                    });
                    return;
                }
                $.post(`/admin/sys_dept/${treeNode.id}`, { _method: 'DELETE' })
                    .then((json) => {
                        if (200 == json.code) {
                            $("#" + treeNode.tId).unbind().remove();
                            zTreeObj.removeNode(treeNode);// 删除节点
                            toastr.success(json.info);
                        } else {
                            toastr.error(json.info);
                        }
                    }, (e) => {
                        Swal.fire('操作提示', '网络错误！', 'error');
                    });
            }
        });
        return false;
    }

    //重命名事件
    function zTreeOnRename(event, treeId, treeNode, isCancel) {
        if (!isCancel) {
            Swal.fire({
                title: '确定操作吗?',
                text: "执行操作后无法取消!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确认',
                cancelButtonText: '取消',
            }).then((result) => {
                if (result.value) {
                    $.post(`/admin/sys_dept/${treeNode.id}`, { _method: 'PATCH', name: treeNode.name })
                        .then((json) => {
                            if (200 == json.code) {
                                toastr.success(json.info);
                            } else {
                                toastr.error(json.info);
                            }
                        }, (e) => {
                            Swal.fire('操作提示', '网络错误！', 'error');
                        });
                }
            })
        }
    }

    init();
});