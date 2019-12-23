<div class="card card-primary card-outline">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#deptDetail" data-toggle="tab">部门信息</a></li>
            <li class="nav-item"><a class="nav-link" href="#deptList" data-toggle="tab">下级部门</a></li>
            <li class="nav-item"><a class="nav-link" href="#postList" data-toggle="tab">岗位一览</a></li>
            <li class="nav-item"><a class="nav-link" href="#userList" data-toggle="tab">人力资源</a></li>
        </ul>
        <div class="card-body  tab-content">
            <div class="tab-pane active" id="deptDetail">
                <div>基本信息</div>
                <hr>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td width="40%">名称</td>
                        <td>{{$dept->name}}</td>
                    </tr>
                    <tr>
                        <td>上级部门</td>
                        <td>{{$dept->pName}}</td>
                    </tr>
                    <tr>
                        <td>部门负责人</td>
                        <td>{{$dept->manager}}</td>
                    </tr>
                    <tr>
                        <td>部门分管领导</td>
                        <td>{{$dept->leader}}</td>
                    </tr>
                    <tr>
                        <td>部门人数</td>
                        <td>{{$dept->count}}</td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary btn-sm updateDept">编辑</button>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="deptList">
                <table class="table table-bordered table-striped table-hover table-head-fixed">
                    <thead>
                    <tr>
                        <th width="40%">名称</th>
                        <th>部门人数</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($deptList as $v)
                            <tr>
                                <td>{{$v->name}}</td>
                                <td>{{$v->count}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="postList">
                <div>
                    <button class="btn btn-primary btn-sm addPost">新增岗位</button>
                </div>
                <hr>
                <table class="table table-bordered table-striped table-hover table-head-fixed">
                    <thead>
                    <tr>
                        <th>岗位名称</th>
                        <th>岗位全名</th>
                        <th>岗位职责</th>
                        <th>任职资格</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($postList as $v)
                            <tr>
                                <td>{{$v->name}}</td>
                                <td>{{$v->fullname}}</td>
                                <td>{{$v->duty}}</td>
                                <td>{{$v->qualification}}</td>
                                <td>{{$v->remark}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="userList">
                <div>
                    <button class="btn btn-primary btn-sm addUser">新增员工</button>
                </div>
                <hr>
                <table class="table table-bordered table-striped table-hover table-head-fixed">
                    <thead>
                    <tr>
                        <th>姓名</th>
                        <th>工号</th>
                        <th>岗位</th>
                        <th>手机号</th>
                        <th>邮箱</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($adminList as $v)
                            <tr>
                                <td>{{$v->realName}}</td>
                                <td>{{$v->work_no}}</td>
                                <td>{{$v->post}}</td>
                                <td>{{$v->phone}}</td>
                                <td>{{$v->email}}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>
</div>
