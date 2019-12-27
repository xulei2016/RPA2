@extends('admin.layouts.wrapper-content')

@section('content')
    <link rel="stylesheet" href="{{URL::asset('/css/admin/func/jquery.steps.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/func/main.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-fileinput/css/fileinput.css')}}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div id="wizard">

                            <h2>客户基本资料填写</h2>
                            <section>
                                <h3>客户基本资料填写</h3>
                                <div class="form-group row">
                                    <label for="customer_type" class="col-sm-2 control-label">客户类型</label>
                                    <div class="col-sm-10">
                                        <input id="customer_type" value="{{ $archives->type }}" @if($archives->type == '个人') checked @endif type="checkbox" class="my-switch">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-2 control-label">客户名称</label>
                                    <div class="col-sm-10">
                                        <input id="customer_name" value="{{ $archives->name }}" type="text" class="form-control" placeholder="请输入客户名称">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_zjbh" class="col-sm-2 control-label">证件号码</label>
                                    <div class="col-sm-10">
                                        <input id="customer_zjbh" value="{{ $archives->zjbh }}" type="text" class="form-control" placeholder="请输入证件号码">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="business_type" class="col-sm-2 control-label">业务类型</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="business_type">
                                            <option @if($archives->btype == '新开户') selected @endif value="新开户">新开户</option>
                                            <option @if($archives->btype == '开金融户') selected @endif value="开金融户">开金融户</option>
                                            <option @if($archives->btype == '激活') selected @endif value="激活">激活</option>
                                            <option @if($archives->btype == '适当性权限申请') selected @endif value="适当性权限申请">适当性权限申请</option>
                                        </select>
                                    </div>
                                </div>
                            </section>

                            <h2>视频审核</h2>
                            <section class="checkVideo">
                                <h3>视频审核</h3>
                                <div class="card card-primary card-outline">
                                    @if($archives->step > 1)
                                        <i style="color:green;" class="fa fa-check-circle-o"></i>
                                        <span>视频审核通过,请直接点击下一步！</span>
                                    @else
                                        <i class="fa fa-refresh"></i>
                                        <span>正在查询视频情况。。。</span>
                                    @endif
                                </div>
                            </section>

                            <h2>失信查询</h2>
                            <section class="credit">
                                <h3>失信查询</h3>
                                <div @if($archives->type == '法人') style="display:none;" @endif class="personal">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>客户名称</th>
                                            <th>证件编号</th>
                                            <th>证券业失信</th>
                                            <th>期货业失信</th>
                                            <th>恒生黑名单</th>
                                        </tr>
                                        @if($archives->step > 2 && $archives->type == '个人')
                                            @foreach(json_decode($archives->credit_list) as $v)
                                                <tr>
                                                    <td>{{ $v->name }}</td>
                                                    <td>{{ $v->idCard }}</td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->zq }}</span></td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->qh }}</span></td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->hh }}</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="selecting">
                                                <td colspan="5"></td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <div @if($archives->type == '个人') style="display:none;" @endif class="company">
                                    <fieldset>
                                        <legend>增加五类人信息</legend>
                                        <div class="whole">
                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">客户名称</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control customer_name" placeholder="请输入客户名称">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">证件号码</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control customer_zjbh" placeholder="请输入证件号码">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 control-label">客户类型</label>
                                                <div class="col-sm-10">
                                                    <input type="checkbox" class="fddbr"> <label>法定代表人 &nbsp;&nbsp;</label>
                                                    <input type="checkbox" class="khdlr"> <label>开户代理人 &nbsp;&nbsp;</label>
                                                    <input type="checkbox" class="zjdbr"> <label>资金调拨人 &nbsp;&nbsp;</label>
                                                    <input type="checkbox" class="zlxdr"> <label>指令下达人 &nbsp;&nbsp;</label>
                                                    <input type="checkbox" class="zdqrr"> <label>账单确认人 &nbsp;&nbsp;</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <button type="button" class="addCustomer btn btn-primary">增加人员</button>
                                            <button type="button" class="creditSelect btn btn-success">查询失信记录</button>
                                        </div>

                                    </fieldset>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>客户名称</th>
                                            <th>证件编号</th>
                                            <th>证券业失信</th>
                                            <th>期货业失信</th>
                                            <th>恒生黑名单</th>
                                        </tr>
                                        @if($archives->step > 2 && $archives->type == '法人')
                                            @foreach(json_decode($archives->credit_list ) as $v)
                                                <tr>
                                                    <td>{{ $v->name }}</td>
                                                    <td>{{ $v->idCard }}</td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->zq }}</span></td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->qh }}</span></td>
                                                    <td><span class="x-tag x-tag-primary x-tag-sm">{{ $v->hh }}</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="selecting">
                                                <td colspan="5"></td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </section>

                            <h2>附件上传</h2>
                            <section>
                                <h3>附件上传</h3>
                                <div class="container my-4">
                                    <form enctype="multipart/form-data">

                                        <div class="file-loading">
                                            <input multiple id="file-0a" name="file" type="file">
                                        </div>
                                        <br>
                                    </form>
                                </div>
                            </section>

                            @if($archives->btype == '适当性权限申请')
                                <h2>适当性测评</h2>
                                <section>
                                    <h3>适当性测评</h3>
                                    <div class="card card-primary card-outline sdx">
                                        @if($archives->step > 4)
                                            <i style="color:green;" class="fa fa-check-circle-o"></i>
                                            <span>适当性已完成,请直接点击下一步！</span>
                                        @else
                                            <i class="fa fa-refresh"></i>
                                            <span>正在查询适当性情况。。。</span>
                                        @endif
                                    </div>
                                </section>
                            @endif

                            @if($archives->btype == '激活')
                                <h2>音频上传</h2>
                                <section>
                                    <h3>音频上传</h3>
                                    <div class="container my-4">
                                        <form enctype="multipart/form-data">
                                            <div class="file-loading">
                                                <input multiple id="file-0b" name="file" type="file">
                                            </div>
                                            <br>
                                        </form>
                                    </div>
                                </section>
                            @endif

                            <h2>档案归档</h2>
                            <section class="finish">
                                <h3>视频审核</h3>
                                <div class="card card-primary card-outline">
                                    <i class="fa fa-check-circle-o text-success"></i>
                                    <span>操作完成</span>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/Archives/jquery.steps.min.js')}}"></script>
    <script>
        let step = parseInt({{ $archives->step }});
        @if($archives->btype == '新开户' || $archives->btype == '开金融户')
            if(step == 6) step = step - 1;
        @endif
        let uid = parseInt({{ $archives->id }});
    </script>

    <script src="{{URL::asset('/js/admin/func/Archives/edit.js')}}"></script>
@endsection