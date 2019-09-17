<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
	<title>在线咨询</title>
	<link rel="stylesheet" href="{{asset('callCenter/css/main.css')}}" />
	<link rel="stylesheet" href="{{asset('css/all.css')}}" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<style>
		ul li {
			cursor:pointer
		}
	</style>
</head>
<body>
<div class="container">
	<div class="customer-chat-top chat-window-top">
		<div class="customer-chat-header-title">在线咨询</div>
		<div id="customer-chat-button-settings" class="customer-chat-header-button"></div>
		<audio id="video" style="display:none;">
			<source src="http://fjdx.sc.chinaz.com/Files/DownLoad/sound1/201507/6063.mp3" />
		</audio>
		<div class="customer-chat-header-menu" >
			<div class="customer-chat-header-menu-triangle"></div>
			<a href="javascript:;" id="customer-chat-setting-toggle-sound" flag="1" class="customer-chat-toggle-sound"><i class="icon-music"></i> <div>声音提醒</div></a>
			<a href="javascript:;" id="customer-chat-action-end-chat">
				<i class="icon-off"></i>
				<div>结束聊天</div>
			</a>
		</div>
	</div>
	<div class="chat-content" id="jj">
		{{--				第一行--}}
		<div class="customer-chat-content-message-operator">
			<div class="avatar customer-chat-content-message-avatar-operator"><img src="{{asset('callCenter/img/a.png')}}" alt=""></div>
			<div class="customer-chat-content-message-column">
				<div class="customer-chat-content-message-author">客服</div>
				<div class="customer-chat-content-message-time"></div>
				<div class="customer-chat-content-message-body" id="init-message">
					{{--							初始化信息--}}
				</div>
			</div>
			<div class="clear-both"></div>
		</div>
		{{--				第一行结束--}}
		<div class="mychat" id="mychat">
			{{--					具体内容--}}

		</div>

	</div>


	<div class="record">
		<div>您是否想问以下问题 :</div>
		<ul id="keyword">

		</ul>

	</div>
	<div class="footer">

		<div class="customer-chat-emots-menu" show="0">
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
		<div class="btn btn-info btn-send">发送</div>
	</div>
</div>
<script>
	function LA(){}
	LA.token = '{{ csrf_token() }}'
</script>
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script type="text/javascript" src="{{asset('callCenter/js/jquery-1.10.2.min.js')}}" ></script>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/all.js')}}"></script>
<script src="{{asset('js/admin/base/callCenter/echoClient.js')}}"></script>
<script type="text/javascript" src="{{asset('callCenter/js/chat.js')}}"></script>
</body>
</html>