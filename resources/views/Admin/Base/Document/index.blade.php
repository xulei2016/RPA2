@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid" id="document">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">目录</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-card-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body card-primary">
                        <div class="input-group input-group-sm">
                            <input class="form-control" placeholder="搜索文件" id="searchFile">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" onclick="RPA.Alert.howSearch()"><i class="fa fa-question-circle"></i></button>
                            </span>
                        </div>
                        <div class="zTreeDemoBackground left">
                            <ul id="tree" class="ztree"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">查阅文档</h3>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h1>档案管理系统说明v1.0</h1>

                        <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; --华安期货金融科技部</p>
                        
                        <hr />
                        <p>1、说明</p>
                        
                        <p>&nbsp; &nbsp; 该文档仅适用于华安期货内部部门，用于便捷管理电子档案。</p>
                        
                        <p>2、背景</p>
                        
                        <p>&nbsp; &nbsp; ...</p>

                    </div>
                    <div class="card-body read"  style="display:none;">
                        <div class="mailbox-read-info">
                            <h5></h5>
                            <h6><span class="creater"></span><span class="mailbox-read-time float-right"></span></h6>
                        </div>
                        <!-- /.mailbox-read-info -->
                        <div class="mailbox-controls with-border text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="prev">
                                <i class="fa fa-reply"></i></button>
                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="next">
                                <i class="fa fa-share"></i></button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            
                        </div>
                        <!-- /.mailbox-read-message -->
                        <!-- /.card-body -->
                        <!-- /.card-footer -->
                        <div class="card-footer bg-white">
                            <ul class="mailbox-attachments d-flex align-items-stretch clearfix uploads">

                            </ul>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-default"><i class="fa fa-trash-alt"></i> 删除</button>
                            <button type="button" class="btn btn-default edit"><i class="fa fa-edit"></i> 编辑</button>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <div class="card-body edit" style="display:none;">
                        <form>
                            <div class="form-group">
                                <input class="form-control" placeholder="文档名称" name="name">
                            </div>
                            <div class="form-group">
                                <textarea id="editor" name="content" class="form-control" style="height: 300px; display: none;">请编辑文档内容</textarea>
                            </div>
                            <div class="form-group">
                                <div class="btn btn-default btn-file">
                                    <i class="fa fa-paperclip"></i> 
                                    <input type="file" name="attachment">
                                </div>
                                <span class="help-block">Max. 32MB</span>
                                <div></div>
                            </div>
                            <!-- /.card-footer -->
                            <div class="card-footer bg-white">
                                <ul class="mailbox-attachments align-items-stretch clearfix uploads">
    
                                </ul>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default" id="save"><i class="fa fa-save"></i> 发布</button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/metroStyle/metroStyle.css')}}" type="text/css">
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exedit.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/fuzzysearch.js')}}"></script>
    <script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
    <script src="{{URL::asset('/js/admin/base/document/index.js')}}"></script>
@endsection