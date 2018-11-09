<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">添加操作</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" id="form" onsubmit="add($(this));return false;">


            <ul id="tree" class="ztree"></ul>


            <!-- /.box-body -->
            <div class="box-footer">
                <button type="reset" class="btn btn-warning" id="form-reset">重置</button>
                <button type="submit" class="btn btn-info pull-right" id="save">提交</button>
                <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续添加</label></div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
    <link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/zTreeStyle/zTreeStyle.css')}}" type="text/css">
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
    <script>
        $(function(){
            /*
            * 初始化
            */
            function init(){
                bindEvent();
            }

            //绑定事件
            function bindEvent(){
                //iCheck for checkbox and radio inputs
                $(document).ready(function(){
                    $('#modal input.minimal').iCheck({
                        checkboxClass: 'icheckbox_minimal-blue',
                        radioClass: 'iradio_minimal-blue',
                    });
                });
            }
        
            //添加
            function add(e){
                RPA.ajaxSubmit(e, FormOptions);
            }

            //提交信息的表单配置
            var FormOptions={
                url:'/admin/role',
                success:successResponse,
                error:RPA.errorReponse
            };
        
            var successResponse = function(json, xml){
                if(200 == json.code){
                    toastr.success('操作成功！');
                    $.pjax.reload('#pjax-container');
                    var formContinue = $('#form-continue').is(':checked');
                    !formContinue ? $('#modal').modal('hide') : $('#model #form-reset').click() ;
                }else{
                    toastr.error(json.info);
                }
            }

            init();
        });
        
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
            callback: {
                onClick: function(e,id,o){
                    var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
                    var node = treeObj.getNodeByTId(o.tId);
                    if (node.open) {
                        treeObj.expandNode(node, false, false, true);
                    }else{
                        treeObj.expandNode(node, true, false, true);
                    }
                },
            }
        };
        
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = [
            {name:"test1", open:true, children:[
                {name:"test1_1"}, {name:"test1_2"}
            ]},
            {name:"test2", open:true, children:[
                {name:"test2_1"}, {name:"test2_2"}
            ]}
        ];
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#tree"), setting, zNodes);
        });
  </script>