$(function () {
    const formData = new FormData();
    var editId;
    var editor = CKEDITOR.replace('editor', { fileTools_requestHeaders: { 'X-CSRF-TOKEN': LA.token } });

    /*
    * 初始化
    */
    function init() {
        bindEvent();
        initMenus();
    }

    //绑定事件
    function bindEvent() {
        //保存文档
        $('section.content .card-body.edit #save').on('click', function (e) {
            let name = $(this).parents('.card-body').find('input[name="name"]').val();
            formData.append('name', name);
            formData.append('content', editor.getData());
            formData.append('type', 'doc');
            formData.append('_method', 'PATCH');
            $.ajax({
                url: `/admin/sys_document/${editId}`,
                data: formData,
                type:'post',
                dataType: 'json',
                contentType: false,
                processData: false,
            }).then((json) => {
                if (200 == json.code) {
                    toastr.success(json.info);
                } else {
                    toastr.error(json.info);
                }
            }, (e) => {
                Swal.fire('操作提示', '网络错误！', 'error');
            });
        });

        //编辑文档
        $('section.content .card-body.read button.edit').on('click', function () {
            let id = $(this).attr('id');
            $.get(`/admin/sys_document/getDoc/${id}`)
                .then(response => {
                    if (200 == response.code) {
                        let data = response.data;
                        $('section.content .card-body.edit input[name="name"]').val(data.name);
                        editor.setData(data.content);
                        $('section.content .card-body').siblings().css("display", "none");
                        $('section.content .card-body.edit').css("display", "block");
                    }
                    return response.json;
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                });
        });

        //文档上传
        $('#pjax-container #document input[name="attachment"]').on('change', function (e) {
            let input = $(this);
            let file = e.target.files[0];
            let temp = [".jpg", ".png", ".rar", ".txt", ".zip", ".doc", ".ppt", ".xls", ".pdf", ".docx", ".xlsx"];
            let fileName = file.name;
            let file_type = fileName.substring(fileName.indexOf("."));
            if (-1 == temp.indexOf(file_type)) {
                Swal('Oops...', '不支持的文件类型！', 'error'); return;
            }
            for (i of formData) {
                if (i[1].name == fileName) {
                    Swal('提示', '请勿重复上传！', 'warning');
                    return;
                }
            }
            let length = input.parents('.form-group').find('.text-info').length;
            let size = (file.size / 1024).toFixed(2);
            let file_html = `<div class="text-info" upload = 'upfile[${length}]' style="padding:10px;"><b>${fileName}</b><span style="margin:0 10px">(${size}KB)</span><span class="float-sm-right delete"><a><i class="fa fa-times"></i></a></span></div>`;
            input.parents('form').find('.card-footer .uploads').append(file_html);
            formData.append(`upfile[${length}]`, e.target.files[0]);
            //文档删除
            $('#pjax-container #document .card-body.edit span.delete:last').on('click', function (e) {
                var _this = $(this);
                Swal.fire({
                    title: '提示',
                    text: "确定删除吗?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '确认',
                    cancelButtonText: '取消',
                }).then((result) => {
                    if(result.value){
                        let name = _this.prev().prev().text();
                        let upload = _this.parents('.text-info').attr('upload');
                        for (i of formData) {
                            if (i[1].name == name) formData.delete(upload);
                        }
                        _this.parents('.text-info').remove();
                        input.val('');
                    }
                });
            });
        });

    }

    var zTreeObj;
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
            showRemoveBtn: true,
            showRenameBtn: true,
            removeTitle: "remove",
            removeTitle: "rename",
        },
        callback: {
            onRename: zTreeOnRename,
            beforeRemove: zTreeBeforeRemove,
            beforeRename: zTreeBeforeRename,
            onDrop: zTreeOnDrop,
            onClick: zTreeOnClick
        }
    };

    //初始化根目录
    function initMenus() {
        $.get('/admin/sys_document/getAllMenus', {}, function (json) {
            if (200 == json.code) {
                var zNodes = json.data;
                zTreeObj = $.fn.zTree.init($("#tree"), setting, zNodes);
                fuzzySearch('tree', 'section.content #searchFile', null, true); //初始化模糊搜索方法
            } else {
                Swal('Oops...', '权限文档列表失败！', 'error');
            }
        });
    }

    //附件删除
    function deleteDoc(){
        console.log(1);
    }

    //添加结点事件
    function addHoverDom(treeId, treeNode) {
        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) return;
        var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
            + "' title='add node' onfocus='this.blur();'></span>";
        sObj.after(addStr);
        var btn = $("#addBtn_" + treeNode.tId);
        if (btn) btn.bind("click", function () {
            Swal.fire({
                title: '请输入新节点名称',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                showLoaderOnConfirm: true,
                preConfirm: (name) => {
                    return $.post('/admin/sys_document', { method: 'post', name: name, parent_id: treeNode.id })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((json) => {
                json = json.value;
                if (200 == json.code) {
                    var zTree = $.fn.zTree.getZTreeObj("tree");
                    zTree.addNodes(treeNode, { id: json.data.id, pId: treeNode.id, name: json.data.name });
                    toastr.success(json.info);
                    return false;
                } else {
                    toastr.error(json.info);
                }
            })
        });
    };

    //单击事件
    function zTreeOnClick(event, treeId, treeNode) {
        formData.delete('upfile');
        $('section.content .card-body .card-footer .uploads').html('');
        $('#pjax-container #document input[name="attachment"]').val('');

        editId = treeNode.id;
        //单击事件，是否根节点
        if (!treeNode.isParent) {
            $.get(`/admin/sys_document/getDoc/${treeNode.id}`)
                .then(response => {
                    if (200 == response.code) {
                        if (!response.data) {//暂无档案
                            $('section.content .card-body').siblings().css("display", "none");
                            $('section.content .card-body.edit input[name="name"]').val(treeNode.name);
                            $('section.content .card-body.edit').css("display", "block");
                        } else {//存在档案
                            let data = response.data;
                            $('section.content .card-body.read h5').text(data.name);
                            $('section.content .card-body.read .mailbox-read-message').html(data.content);
                            $('section.content .card-body.read h6 span').text(data.created_at);
                            $('section.content .card-body.read h6 span.creater').text(`创建者：` + data.creater_name);
                            $('section.content .card-body').siblings().css("display", "none");
                            $('section.content .card-body.read').css("display", "block");
                            $('section.content .card-body.read button.edit').attr("id", data.did);
                            //是否有附件
                            if(response.data.uploads){
                                let files = response.data.uploads;
                                let html = '';
                                let file_html = '';
                                for(i of files){
                                    let type = '';
                                    switch(i.type){
                                        case 'pdf':
                                            type = 'fa-file-pdf-o';
                                        break;
                                        case 'doc':
                                            type = 'fa-file-word-o';
                                        break;
                                        case 'docx':
                                            type = 'fa-file-word-o';
                                        break;
                                        case 'xlx':
                                            type = 'fa-file-excel-o';
                                        break;
                                        case 'xlsx':
                                            type = 'fa-file-excel-o';
                                        break;
                                        case 'png':
                                            type = 'fa-file-image-o';
                                        break;
                                        case 'jpg':
                                            type = 'fa-file-image-o';
                                        break;
                                        case 'jpeg':
                                            type = 'fa-file-image-o';
                                        break;
                                        case 'gif':
                                            type = 'fa-file-image-o';
                                        break;
                                        case 'rar':
                                            type = 'fa-file-archive-o';
                                        break;
                                        case 'zip':
                                            type = 'fa-file-archive-o';
                                        break;
                                        case 'txt':
                                            type = 'fa-file-text-o';
                                        break;
                                    }
                                    html += `<li><span class="mailbox-attachment-icon"><i class="fa ${type}"></i></span><div class="mailbox-attachment-info"><a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i>${i.originalName}</a><span class="mailbox-attachment-size clearfix mt-1"><span>${(i.size/1024).toFixed(2)} KB</span><a href="${window.location.protocol+"//"+window.location.host}/storage/${i.filename}" class="btn btn-default btn-sm float-right"><i class="fa fa-cloud-download"></i></a></span></div></li>`;
                                    file_html += `<div class="text-info" style="padding:10px;"><b>${i.originalName}</b><span style="margin:0 10px">(${(i.size/1024).toFixed(2)} KB)</span><span class="float-sm-right delete"><a><i class="fa fa-times"></i></a></span></div>`;
                                }
                                $('section.content .card-body.read .card-footer .uploads').html(html);
                                $('section.content .card-body.edit .card-footer .uploads').html(file_html);

                                //文档删除
                                $('#pjax-container #document .card-body.edit span.delete').on('click', function (e) {
                                    var _this = $(this);
                                    let file = _this.parents('.text-info').find('b').text();
                                    Swal.fire({
                                        title: '提示',
                                        text: "确定删除吗?",
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: '确认',
                                        cancelButtonText: '取消',
                                    }).then((result) => {
                                        if(result.value){
                                            $.post(`/admin/sys_document/${response.data.id}/deleteDoc`,{file:file})
                                            .then((json) => {
                                                if (200 == json.code) {
                                                    toastr.success(json.info);
                                                    let name = _this.prev().prev().text();
                                                    let upload = _this.parents('.text-info').attr('upload');
                                                    if(upload){
                                                        for (i of formData) {
                                                            if (i[1].name == name) formData.delete(upload);
                                                        }
                                                    }
                                                    _this.parents('.text-info').remove();
                                                } else {
                                                    toastr.error(json.info);
                                                }
                                            }, (e) => {
                                                Swal.fire('操作提示', '网络错误！', 'error');
                                            });
                                        }
                                    });
                                });
                            }
                        }
                    }
                    return response.json;
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
        }
    };

    //移除结点事件
    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_" + treeNode.tId).unbind().remove();
    };

    //结点重命名
    function zTreeBeforeRename(treeId, treeNode, newName, isCancel) {
        if (isCancel) return true;
        if (newName == treeNode.name || '' == newName) return false;
    }

    //移动节点
    function zTreeOnDrop(event, treeId, treeNodes, targetNode, moveType) {
        if (moveType) {
            $.post(`/admin/sys_document/${treeNodes[0].id}`, {
                _method: 'PATCH',
                moveType: moveType,
                objId: targetNode.id,
            })
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
                $.post(`/admin/sys_document/${treeNode.id}`, { _method: 'DELETE' })
                    .then((json) => {
                        if (200 == json.code) {
                            $("#" + treeNode.tId).unbind().remove();
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
                    $.post(`/admin/sys_document/${treeNode.id}`, { _method: 'PATCH', name: treeNode.name })
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
    };


    init();
});