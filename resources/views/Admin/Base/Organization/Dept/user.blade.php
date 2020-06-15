<div class="card card-primary card-outline" id="treeTransfer">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#baseInfo" data-toggle="tab">基本信息</a></li>
            <li class="nav-item"><a class="nav-link" href="#roleList" data-toggle="tab">角色管理</a></li>
            <li class="nav-item"><a class="nav-link" href="#authList" data-toggle="tab">权限管理</a></li>

        </ul>
        <div class="card-body  tab-content">
            <div class="tab-pane active" id="baseInfo">
                <div>基本信息</div>
                <hr>
                <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th width="50%">属性</th>
                    <th width="50%">值</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>用户名</td>
                    <td id="item_name">{{ $admin->name??'暂无' }}</td>
                </tr>
                <tr>
                    <td>真实姓名</td>
                    <td id="item_realName">{{ $admin->realName??'暂无' }}</td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td id="item_statusName">{{$admin->statusName ?? '暂无'}}</td>
                </tr>
                <tr>
                    <td>部门</td>
                    <td id="item_department">{{ $admin->department ?? "暂无" }}</td>
                </tr>
                <tr>
                    <td>岗位</td>
                    <td id="item_post">{{$admin->post??'暂无'}}</td>
                </tr>
                <tr>
                    <td>角色</td>
                    <td id="item_roleLists">{{$admin->roleLists??'暂无'}}</td>
                </tr>
                <tr>
                    <td>性别</td>
                    <td id="item_gender">{{ $admin->gender ?? "暂无" }}</td>
                </tr>
                <tr>
                    <td>手机号</td>
                    <td id="item_phone">{{$admin->phone??'暂无'}}</td>
                </tr>
                <tr>
                    <td>邮箱</td>
                    <td id="item_email">{{$admin->email??'暂无'}}</td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td id="item_address">{{$admin->address??'暂无'}}</td>
                </tr>
                <tr>
                    <td>操作</td>
                    <td id="item_operation" item-id="{{ $admin->id }}">
                        <button class="btn btn-primary btn-sm edit_user">编辑</button>
                    </td>
                </tr>
                </tbody>
                </table>
            </div>
            <div class="tab-pane" id="roleList">
                <div>角色管理</div>
                <hr />
                <div>
                    <tree-role uid="{{$admin->id}}"></tree-role>
                </div>
            </div>
            <div class="tab-pane" id="authList">
                <div>权限管理</div>
                <div><span class="text-red">*</span> 被禁用的列是角色带来的权限</div>
                <div><span class="text-red">*</span> 右侧列表全选按钮请勿使用</div>
                <hr />
                <div>
                    <tree-auth uid="{{$admin->id}}"></tree-auth>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/admin/components.js')}}?id={{ rand() }}"></script>
