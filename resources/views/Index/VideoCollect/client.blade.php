<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
		<title>线下视频上传</title>
		<link rel="stylesheet" href="{{asset('uploadVideo/css/main.css')}}" />
	</head>

	<body>
		<div class="container">
			<div class="top">
				<a href="javascript:history.go(-1)"><img src="{{asset('uploadVideo/images/back.png')}}"></a>
				<h2>第一步 录入客户信息</h2>
			</div>
			<div class="contentVideo">
				<!-- 上传的表单 -->
				<form method="post" id="myForm" action="fileTest.php" enctype="multipart/form-data">
					<div class="form-content">
						<div class="form-group">
							<input type="text" name="account" placeholder="资金账号" class="cAccount">

						</div>
						<div class="form-group">
							<input type="text" name="name" placeholder="姓名" class="cName">

						</div>
						<div class="form-group">
							<input type="text" name="cIdCard" placeholder="身份证号" class="cIdCard">
						</div>

						<div class="form-group">
							<button class="tran" id="nextBtn" type="button">下一步</button>
						</div>
					</div>
				</form>
				<div class="form-content tips">
					<span>注：法人身份证号请填写组织机构代码</span>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="{{asset('uploadVideo/js/jquery-1.10.2.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('uploadVideo/js/bootstrapValidator.js')}}"></script>
		
		<script>
			
			// 表单验证
			$('#myForm').bootstrapValidator({
				fields: {
					account: {
						validators: {
							notEmpty: {
								message: '资金账号不能为空'
							}
						}
					},
					name: {
						validators: {
							notEmpty: {
								message: '姓名不能为空'
							}
						}
					},
				}
			});
	
			// 提交客户信息			
			$("#nextBtn").on("click", function() {
				
				// 获取表单对象
				var bootstrapValidator = $("#myForm").data('bootstrapValidator');
				bootstrapValidator.validate();
				if(bootstrapValidator.isValid()) {
					
					// 获取客户经理信息
					var sData = JSON.parse(window.localStorage.getItem("mIfo"));
					console.log(sData)

					var account = $(".cAccount").val(),
						personName = $(".cName").val(),
						IDCard = $(".cIdCard").val();
					sData.FundAccount = account;
					sData.CName = personName;
					sData.CidCard = IDCard;

					$.ajax({
						url: "/api/v1/customer",
						type: 'post',
						async: true,
						data: sData,
						dataType: 'json',
						success: function(_data) {
							console.log(_data)

							if(_data.status == 200) {
								window.localStorage.setItem("setId", _data.data)
								window.location.href = "/index/uploadVideo/upload";
							} else if(_data.status == 500) {
								alert(_data.data)
							}

						}

					})

				} else{
					
				}

			});
		</script>
	</body>

</html>