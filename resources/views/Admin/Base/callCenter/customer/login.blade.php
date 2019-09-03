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
				<div class="customer-chat-header-title">在线咨询</div>
			</div>
			<div class="customer-chat-select-avatar">
				<a href="#" class="icon-white icon-left">
					<i class="icon-chevron-left"></i>
				</a>
				<img src="{{asset('callCenter/img/a.png')}}" class="icon-pic"/>
				<a href="#"  class="icon-white icon-right">
					<i class="icon-chevron-right"></i>
				</a>
			</div>
			<form id="defaultForm">
				<div class="form-content">
					<div class="form-group">
						<input type="text" id="name" name="name" class="name"  placeholder="姓名">
					</div>
					<div class="form-group">
						<input type="text" id="zjzh" name="zjzh" placeholder="资金账号" class="account">
					</div>
					<div class="form-group yd-div">
						<a href="/call_center/forget" class="forget">忘记账号?</a>
						<a href="http://www.haqh.com/zt/kaihu/shuoming_haqh.htm" class="newUser">新用户开户</a>
					</div>
					<div class="form-group">
						<button class="tran" id="nextBtn">
							下一步
						</button>
					</div>
				</div>
			</form>
			
		</div>
	
		<script type="text/javascript" src="{{asset('callCenter/js/jquery-1.10.2.min.js')}}" ></script>
		<script type="text/javascript" src="{{asset('callCenter/js/login.js')}}" ></script>
		<script type="text/javascript" src="{{asset('callCenter/js/bootstrapValidator.js')}}" ></script>
		<script src="{{asset('js/admin/base/callCenter/echoClient.js')}}"></script>
		<script>
			$(function(){
				function init(){
					checkLogin();
					bindEvent();
				}

				function checkLogin(){
					localStorage.removeItem('call_center_timestamp');
					var info = localStorage.getItem('customer_info')
					if(info) {
						info = JSON.parse(info);
						window.location.href = info.href
					}
				}

				function bindEvent(){
					$('#defaultForm').bootstrapValidator({
						fields: {
							name: {
								validators: {
									notEmpty: {
										message: '姓名不能为空'
									}
								}
							},
							zjzh: {
								validators: {
									notEmpty: {
										message: '资金账号不能为空'
									}
								}
							}
						}
					});
					$("#nextBtn").on("click", function() {
						var bootstrapValidator = $("#defaultForm").data('bootstrapValidator');
						bootstrapValidator.validate();
						if(bootstrapValidator.isValid()){
							var data = {
								zjzh:$('#zjzh').val(),
								name:$('#name').val(),
								avatar:$('.icon-pic').attr('src')
							};
							EchoClient.prototype.login(data);
						} else {
							console.log('验证失败');
						}
					});
				}
				init();
			});


		</script>
		
	</body>
</html>
