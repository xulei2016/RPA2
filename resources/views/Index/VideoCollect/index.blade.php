<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
		<title>线下视频上传</title>
		<link rel="stylesheet" href="{{asset('css/index/uploadVideo/main.css')}}" />
	</head>

	<body>
		<div class="container">
			<form id="defaultForm" method="" class="form-horizontal" action="">
				<div class="header">
					<img src="{{asset('images/index/uploadVideo/logo.jpg')}}" />
				</div>
				<div class="form-content">
					<div class="form-group">
						<input type="text" name="account" class="account"  placeholder="客户经理工号">
					</div>
					<div class="form-group">
						<input type="text" name="name" placeholder="姓名" class="person-name">

					</div>
					<!-- <div class="form-group">
						<input type="text" name="idCard" placeholder="身份证号" class="id-card">
					</div> -->
					
					<div class="form-group">
						<button class="tran" id="nextBtn">
							登录
						</button>
					</div>
				</div>
			</form>
		</div>

		<script type="text/javascript" src="{{asset('include/jquery/jquery.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/index/uploadVideo/bootstrapValidator.js')}}"></script>
		<script>
			$('#defaultForm').bootstrapValidator({
				fields: {
					account: {
						validators: {
							notEmpty: {
								message: '客户经理工号不能为空'
							}
						}
					},
					name: {
						validators: {
							notEmpty: {
								message: '姓名不能为空'
							}
						}
					}
				}
			});

			$("#nextBtn").on("click", function() {
				var bootstrapValidator = $("#defaultForm").data('bootstrapValidator');
				bootstrapValidator.validate();
				if(bootstrapValidator.isValid()){
					var account=$(".account").val(),
					personName=$(".person-name").val(),
					IDCard=$(".id-card").val();
					var setData={
						MNum:account,
						MName:personName,
						MidCard:IDCard,
					}
					$.ajax({
						url: "/api/v1/login",
						type: 'post',
						async: true,
						data:setData,
						dataType: 'json',
						success: function(_data) {
							if(_data.status==200){
								setData.yyb=_data.data
								window.localStorage.setItem("mIfo",JSON.stringify(setData));
								window.location.href="/upload_video/client";
							}else if(_data.status==500){
								alert("登陆失败")
							}
						},
						
					})
					
					
				}
			});
		</script>
	</body>

</html>