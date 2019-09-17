<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
	<title>登录</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{asset('callCenter/css/main.css')}}" />
	<link rel="stylesheet" href="{{asset('callCenter/css/bootstrapValidator.css')}}" />
</head>

<body>
<div class="container">
	<div class="customer-chat-top">
		<a href="javascript:history.go(-1)"><img src="{{asset('callCenter/img/back.png')}}" class="back"></a>
		<div class="customer-chat-header-title cardNumber">输入身份证</div>
	</div>

	<div class="form-group">
		<input type="text" name="name" placeholder="姓名" class="id-card" id="name">
		<div class="error-message"></div>
	</div>

	<div class="form-group">
		<input type="text" name="card" placeholder="身份证号" class="id-card" id="card">
		<p class="error-message">如果身份证中含有字母,请使用大写字母</p>
		<div class="error-message"></div>
	</div>

	<div class="form-group">
		<button type="button" class="id-sure-btn addclass" id="idSBtn" onclick="setAjax()">确定</button>
	</div>

</div>

<script type="text/javascript" src="{{asset('callCenter/js/jquery-1.10.2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('callCenter/js/login.js')}}"></script>
<script src="{{asset('js/admin/base/callCenter/echoClient.js')}}"></script>
<script>

	function setAjax(){
		var idReg=/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
		var flag = false;
		var name = $('input#name').val();
		var card = $('input#card').val();
		if(!name) {
			$('input#name').parent().find('div.error-message').text("姓名必填");
			flag = true;
		}
		if(!card || !idReg.test(card)) {
			$('input#card').parent().find('div.error-message').text("请输入正确的身份证号码");
			flag = true;
		}
		if(flag) return;
		var data = {
			card:card,
			name:name
		};
		EchoClient.prototype.login(data);

	}

</script>
</body>

</html>