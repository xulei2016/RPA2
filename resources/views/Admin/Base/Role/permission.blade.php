@component('admin.widgets.viewForm')
    @slot("title")
        权限管理
    @endslot
    @slot("formContent")
    <div class="card-body">
        <form class="form-horizontal" id="form">

            <ul id="tree" class="ztree"></ul>

            <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" id="save">提交</button>
            </div>
        </form>
    </div>
    @endslot
    @slot("formScript")
    <link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/zTreeStyle/zTreeStyle.css')}}" type="text/css">
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
    <script>
        $(function(){
            let modal = RPA.config.modal;
            
            /*
            * 初始化
            */
            function init(){
                bindEvent();
                initPermission();
            }

            //绑定事件
            function bindEvent(){
                //iCheck for checkbox and radio inputs
                $(document).ready(function(){
                    $(modal+' input.minimal').iCheck({
                        checkboxClass: 'icheckbox_minimal-blue',
                        radioClass: 'iradio_minimal-blue',
                    });
                });

                $(modal+' #form #save').click(function(){
                    add($(this));
                });
            }
        
            //添加
            function add(){
                var treeObj = $.fn.zTree.getZTreeObj("tree");
                var nodes = treeObj.getCheckedNodes(true);
                v="";
                for(var i=0;i<nodes.length;i++){
                    v+=nodes[i].desc + ",";
                }
                $.post('/admin/sys_role/{{ $id }}/roleHasPermission', {data:v}, function(json){
                    if(200 == json.code){
                        toastr.success('操作成功！');
                        $(modal+'').modal('hide');
                        $.pjax.reload('#pjax-container');
                    }else{
                        toastr.error(json.info);
                    }
                })
            }

            //提交信息的表单配置
            var FormOptions={
                url:'/admin/sys_role',
                success:successResponse,
                error:RPA.errorReponse
            };
        
            var successResponse = function(json, xml){
                if(200 == json.code){
                    toastr.success('操作成功！');
                    $.pjax.reload('#pjax-container');
                }else{
                    toastr.error(json.info);
                }
            }

            var zTreeObj;
            // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
            var setting = {
                check: {
                    enable: true,
                    chkStyle: 'checkbox',
                    chkboxType: {"Y": "ps", "N": "ps"},
                },
                view: {
                    showIcon: false,
                    dblClickExpand: false
                },
                data: {
                    simpleData: {
                        enable: true,
                        idKey: "id",
                        pIdKey: "pid",
                        rootPId: 0
                    }
                },
                callback: {
                    onClick: function(e,id,o){
                        var treeObj = $.fn.zTree.getZTreeObj('tree');
                        var node = treeObj.getNodeByTId(o.tId);
                        if (node.open) {
                            treeObj.expandNode(node, false, false, true);
                        }else{
                            treeObj.expandNode(node, true, false, true);
                        }
                    },
                },
                
            };

            function initPermission(){
                $.post('/admin/sys_role/{{ $id }}/getCheckPermission', {}, function(json){
                    if(200 == json.code){
                        var zNodes = json.data;
                        zTreeObj = $.fn.zTree.init($("#tree"), setting, zNodes);
                    }else{
                        Swal('Oops...','权限获取失败！','error');
                    }
                });
            }


            init();
        });
    </script>
    @endslot
@endcomponent