<div class="card card-primary card-outline">
    <div class="card-header">
        查询人员
    </div>
    <div class="card-body">
        <div class="input-group input-group-sm">
            <input class="form-control" placeholder="搜索部门" id="searchAdminSearch">
            <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat" onclick="RPA.Alert.howSearch()"><i class="fa fa-question-circle"></i></button>
            </span>
        </div>
        <div class="zTreeDemoBackground" style="height:250px;overflow-y:scroll">
            <ul id="searchAdminTree" class="ztree"></ul>
        </div>
    </div>
    <div class="card-footer">
        您当前选中的是:
        <span id="selectAdmin">
            暂未选择
        </span>
        <br>
        <div>
            <a class="btn btn-primary btn-sm pull-right" id="searchAdminConfirm">确认</a>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/metroStyle/metroStyle.css')}}" type="text/css">
<script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exedit.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/fuzzysearch.js')}}"></script>
<script src="{{URL::asset('js/admin/base/organization/dept/searchAdmin.js')}}"></script>
