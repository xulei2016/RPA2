@extends("admin.layouts.wrapper-content")

@section("content")
    <link rel="stylesheet" href="{{asset('callCenter/css/chat.css')}}">

    <div class="row" >
        <div class="col-md-3">
            <div class="card card-parimary card-outline">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#kh" data-toggle="tab">在线客户</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kf" data-toggle="tab">在线客服</a></li>
                    </ul>
                    <div class="card-body tab-content">
                        <div class="tab-pane active" id="kh">
                            <div class="online-users">
                                <div class="online-users-head">在线客户</div>
                                <div class="online-users-info">
                                    {{--在线客户--}}
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kf">
                            <div class="online-operators">
                                <div class="online-operators-head">在线客服</div>
                                <div class="online-operators-info">
                                    {{--在线客服--}}
                                    <ul></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--聊天界面--}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-body" style="height: 100%">
                    <div class="talk">
                        <ul class="nav nav-tabs">
                        {{--tab页--}}
                        </ul>
                        <div class="chat-content-out">
                        {{--聊天窗口--}}
                        </div>

                        <div class="footer">
                            <div class="customer-chat-emots-menu">
                                <div class="customer-chat-header-menu-triangle"></div>
                                <div class="emots-wrapper">
                                    <a href="javascript:;" id=":)" class="customer-chat-emoticon"><i class="emot emot-1"></i></a>
                                    <a href="javascript:;" id=";)" class="customer-chat-emoticon"><i class="emot emot-2"></i></a>
                                    <a href="javascript:;" id=":(" class="customer-chat-emoticon"><i class="emot emot-3"></i></a>
                                    <a href="javascript:;" id=":D" class="customer-chat-emoticon"><i class="emot emot-4"></i></a>
                                    <a href="javascript:;" id=":P" class="customer-chat-emoticon"><i class="emot emot-5"></i></a>
                                    <a href="javascript:;" id="=)" class="customer-chat-emoticon"><i class="emot emot-6"></i></a>
                                    <a href="javascript:;" id=":|" class="customer-chat-emoticon"><i class="emot emot-7"></i></a>
                                    <a href="javascript:;" id="=|" class="customer-chat-emoticon"><i class="emot emot-8"></i></a>
                                    <a href="javascript:;" id=">:|" class="customer-chat-emoticon"><i class="emot emot-9"></i></a>
                                    <a href="javascript:;" id=">:D" class="customer-chat-emoticon"><i class="emot emot-10"></i></a>

                                    <a href="javascript:;" id="o_O" class="customer-chat-emoticon"><i class="emot emot-11"></i></a>
                                    <a href="javascript:;" id="=O" class="customer-chat-emoticon"><i class="emot emot-12"></i></a>
                                    <a href="javascript:;" id="<3" class="customer-chat-emoticon"><i class="emot emot-13"></i></a>
                                    <a href="javascript:;" id=":S" class="customer-chat-emoticon"><i class="emot emot-14"></i></a>
                                    <a href="javascript:;" id=":*" class="customer-chat-emoticon"><i class="emot emot-15"></i></a>
                                    <a href="javascript:;" id=":$" class="customer-chat-emoticon"><i class="emot emot-16"></i></a>
                                    <a href="javascript:;" id="=B" class="customer-chat-emoticon"><i class="emot emot-17"></i></a>
                                    <a href="javascript:;" id=":-D" class="customer-chat-emoticon"><i class="emot emot-18"></i></a>
                                    <a href="javascript:;" id=";-D" class="customer-chat-emoticon"><i class="emot emot-19"></i></a>
                                    <a href="javascript:;" id="*-D" class="customer-chat-emoticon"><i class="emot emot-20"></i></a>
                                </div>
                            </div>
                            <input type="text" id="customer-chat-message-input" class="customer-chat-content-message-input-field" placeholder="输入你的问题">

                            <label class="file-button" for="file-input">
                                <i class="fa fa-upload"></i>
                                <!-- 	<input type="file"  name="files[]" multiple="">-->
                                <input name="firstImg"  type="file" id="file-input" multiple="">
                            </label>
                            <div class="customer-chat-content-message-emots-button"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
                {{--客户与客服信息--}}
        <div class="col-md-3">
            <div class="card card-parimary card-outline">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#kh_infos" data-toggle="tab">客户信息</a></li>
                        <li class="nav-item"><a class="nav-link" href="#speed_moudle" data-toggle="tab">快捷模板</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kf_infos" data-toggle="tab">客服管理</a></li>
                    </ul>
                    <div class="card-body tab-content">
                        <div class="tab-pane active" id="kh_infos">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a href="" class="nav-link">客户姓名:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">资金账号:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">开户日期:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">当日权益:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">客户类型:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">银期关联:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">银期关联:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">手续费标准:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">保证金标准:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">客户姓名:<span class="label label-primary pull-right">暂无</span></a>
                                    <a href="" class="nav-link">客户姓名:<span class="label label-primary pull-right">暂无</span></a>
                                </li>
                            </ul>
                            <div class="userMod-body">
                                {{--<img id="customer-avatar" src="{{asset('callCenter/img/avatar-2.png')}}" />--}}
                                <hr />
                                <div class="userDiv">
                                    <label>交易权限:</label>
                                    <br>
                                    <span id="customer-jybm">
                                        未知
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kf_infos">
                            <div class="modification-body">
                                <div class="manager-name changeDiv">
                                    <label>昵称:</label>
                                    <input type="text" id="nickname" value="{{$manager['nickname']}}" />
                                </div>
                                <div class="manager-label changeDiv">
                                    <label>标签:</label>
                                    <input type="text" id="label" value="{{$manager['label']}}" />
                                </div>
                                <div class="manager-desc changeDiv">
                                    <label>描述</label>
                                    <input type="text" id="desc" value="{{$manager['desc']}}" />
                                </div>
                            </div>
                            <input id="manager_id" name="manager_id" value="{{$manager['id']}}"type="hidden" />
                        </div>
                        <div class="tab-pane" id="speed_moudle">
                            <div class="template common-panel">
                                <div class="common-header">模板语句(常见问题)</div>
                                <div class="common-body">
                                    <ul>
                                    {{--模板--}}
                                    </ul>
                                </div>
                            </div>
                            <div class="show-template">
                                <p class="t-title">参考回答</p>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/admin/base/callCenter/echoManager.js')}}"></script>
    <script src="{{asset('callCenter/js/chatManager.js')}}"></script>
@endsection