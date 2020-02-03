<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
		<title>上传记录</title>
		<link rel="stylesheet" href="{{asset('css/index/uploadVideo/main.css')}}" />
	</head>

	<body>
		<div class="container">
			<div class="top">
				<h2>上传记录</h2>
			</div>

			<div class="recordList" id="recordList">
			</div>
			<div class="noRecord">
				<div class="noDataPic"></div>
				<p>-----暂无数据-----</p>
			</div>

			<div class="footer">
				<ul>
					<a href="/upload_video/upload">
						<li class="firstLi">
							<div class="backVideoPic"></div>
							<p>文件上传</p>

						</li>
					</a>
					<a href="/upload_video/record">
						<li class="active">
							<div class="recordPic"></div>
							<p>上传记录</p>
						</li>
					</a>
				</ul>

			</div>
		</div>
		<script id="test" type="text/html">
			
		
			{#each#}
			<div class="whole">
				<div class="listhead">
					<h3>客户名称</h3>
					<h3>{#$value.customer_name#}</h3>
					<div class="down"></div>
				</div>
				
				<div class="controlDiv"> 
					 
					<div class="listCon">
						<ul>
							<li>资金账号</li>
							<li>{#$value.customer_zjzh#}</li>
						</ul>
					</div>

					<div class="listCon">
						<ul>
							<li>身份证号</li>
							<li>{#$value.customer_sfzh#}</li>
						</ul>
					</div>

					<div class="listCon">
						<ul>
							<li>业务类型</li>
							<li>{#$value.btype#}</li>
						</ul>
					</div>
					
					<div class="listCon">
						<ul>
							<li>审核状态</li>
							<li>
								{#if $value.status==1#}
								<span style="color:green;">审核成功</span>
								{#else if $value.status==2#}
								<span style="color:red;">审核失败（{#$value.reason#}）</span>
								{#else#}
									<span style="color:gray;">未审核</span>
								{#/if#}
							</li>
						</ul>
					</div>
					<hr>
					{#each $value.jsondata #}
					<div class="listCon">
						<ul>
							<li>视频名称</li>
							<li>{#$value.filename#}</li>
						</ul>
					</div>
					<div class="listCon">
						<ul>
							<li>上传时间</li>
							<li>{#$value.add_time#}</li>
						</ul>
					</div>
					{#/each#}
				</div>
			</div>	
			{#/each#}
			
		
			

		</script>
		<script type="text/javascript" src="{{asset('include/jquery/jquery.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/index/uploadVideo/template-web.js')}}"></script>
		<script>
			var getStroage =JSON.parse(window.localStorage.getItem("mIfo"));
			
			
			$.ajax({
				url: "/api/v1/history",
				type: 'post',
				async: true,
				data: {
					MNum: getStroage.MNum
				},
				dataType: 'json',
				success: function(_data) {
					
					console.log(_data.length)
					if(_data.length){
						var html = template('test', _data);
						document.getElementById('recordList').innerHTML = html;
					}else{
						$(".noRecord").show()
					}
				
					
				}

			})
			
			$(document).on("click",".listhead",function(){
				$(this).parent().find(".controlDiv").toggle();
			})
			
		</script>
	</body>

</html>