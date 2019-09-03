@extends("admin.layouts.wrapper-content")

@section("content")
    <link rel="stylesheet" href="{{asset('callCenter/css/chat.css')}}">

    <div class="box box-default">
        <div class="box-body" style="height: 100%">
            <div class="row" >
{{--                客户与客服列表--}}
                <div class="col-md-2">
                    <div class="online-users">
                        <div class="online-users-head">在线客户</div>
                        <div class="online-users-info">
                            <ul>

                            </ul>
                        </div>
                    </div>
                    <div class="online-operators">
                        <div class="online-operators-head">在线客服</div>
                        <div class="online-operators-info">
                            <ul>
{{--                                在线客服--}}
                            </ul>
                        </div>
                    </div>
                </div>
{{--                聊天界面--}}
                <div class="col-md-7">
                    <div class="talk">
                        <ul class="nav nav-tabs">
{{--                           tab页--}}
                        </ul>
                        <div class="chat-content-out">
{{--                            聊天窗口--}}
                        </div>

                        <div class="footer">

                            <div class="customer-chat-emots-menu">
                                <div class="customer-chat-header-menu-triangle"></div>
                                <div class="emots-wrapper">
                                    <a href="#" id=":)" class="customer-chat-emoticon"><i class="emot emot-1"></i></a>
                                    <a href="#" id=";)" class="customer-chat-emoticon"><i class="emot emot-2"></i></a>
                                    <a href="#" id=":(" class="customer-chat-emoticon"><i class="emot emot-3"></i></a>
                                    <a href="#" id=":D" class="customer-chat-emoticon"><i class="emot emot-4"></i></a>
                                    <a href="#" id=":P" class="customer-chat-emoticon"><i class="emot emot-5"></i></a>
                                    <a href="#" id="=)" class="customer-chat-emoticon"><i class="emot emot-6"></i></a>
                                    <a href="#" id=":|" class="customer-chat-emoticon"><i class="emot emot-7"></i></a>
                                    <a href="#" id="=|" class="customer-chat-emoticon"><i class="emot emot-8"></i></a>
                                    <a href="#" id=">:|" class="customer-chat-emoticon"><i class="emot emot-9"></i></a>
                                    <a href="#" id=">:D" class="customer-chat-emoticon"><i class="emot emot-10"></i></a>

                                    <a href="#" id="o_O" class="customer-chat-emoticon"><i class="emot emot-11"></i></a>
                                    <a href="#" id="=O" class="customer-chat-emoticon"><i class="emot emot-12"></i></a>
                                    <a href="#" id="<3" class="customer-chat-emoticon"><i class="emot emot-13"></i></a>
                                    <a href="#" id=":S" class="customer-chat-emoticon"><i class="emot emot-14"></i></a>
                                    <a href="#" id=":*" class="customer-chat-emoticon"><i class="emot emot-15"></i></a>
                                    <a href="#" id=":$" class="customer-chat-emoticon"><i class="emot emot-16"></i></a>
                                    <a href="#" id="=B" class="customer-chat-emoticon"><i class="emot emot-17"></i></a>
                                    <a href="#" id=":-D" class="customer-chat-emoticon"><i class="emot emot-18"></i></a>
                                    <a href="#" id=";-D" class="customer-chat-emoticon"><i class="emot emot-19"></i></a>
                                    <a href="#" id="*-D" class="customer-chat-emoticon"><i class="emot emot-20"></i></a>
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
{{--                客户与客服信息--}}
                <div class="col-md-3">
                    <div class="modification-info">
                        <div class="userMod">
                            <div class="userMod-header">
                                客户信息
                            </div>
                            <div class="userMod-body" style="text-align: left">
{{--                                <img id="customer-avatar" src="{{asset('callCenter/img/avatar-2.png')}}" />--}}
                                <div class="userDiv">
                                    <div>
                                        <label>客户姓名:</label>
                                        <span id="customer-name">暂无</span>
                                    </div>
                                    <div>
                                        <label>资金账号:</label>
                                        <span id="customer-zjzh">暂无</span>
                                    </div>

                                </div>

                                <div class="userDiv">
                                    <div>
                                        <label>开户日期:</label>
                                        <span id="customer-khrq">暂无</span>
                                    </div>
                                    <div>
                                        <label>当日权益:</label>
                                        <span id="customer-zjqy">暂无</span>
                                    </div>
                                </div>

                                <div class="userDiv">
                                    <div>
                                        <label>客户类型:</label>
                                        <span id="customer-client">暂无</span>
                                    </div>
                                    <div>
                                        <label>银期关联:</label>
                                        <span id="customer-yq">未知</span>
                                    </div>

                                </div>

                                <div class="userDiv">
                                    <div>
                                        <label>手续费标准:</label>
                                        <span id="customer-sxf">暂无</span>
                                    </div>
                                    <div>
                                        <label>保证金标准:</label>
                                        <span id="customer-bzj">暂无</span>
                                    </div>

                                </div>
                                <div class="userDiv">
                                    <label>交易编码:</label>
                                    <p id="customer-jybm" style="text-align: center">

                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="opMod">
                            <div class="modification-header">
                                客服管理
                            </div>
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
                        <div class="template common-panel">
                            <div class="common-header">模板语句(常见问题)</div>
                            <div class="common-body">
                                <ul>
{{--                                   模板--}}
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
    <script src="{{asset('js/admin/base/callCenter/echoManager.js')}}"></script>
    <script src="{{asset('callCenter/js/chatManager.js')}}"></script>
@endsection