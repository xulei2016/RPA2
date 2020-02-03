<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
		<title>线下视频上传</title>
		<link rel="stylesheet" href="{{asset('css/index/uploadVideo/main.css')}}" />
	</head>

	<body>
		<div class="container">
			<div class="top">
				<a href="javascript:history.go(-1)"><img src="{{asset('images/index/uploadVideo/back.png')}}"></a>
				<h2>第二步 上传视频</h2>
			</div>
			<div class="contentVideo">
				<!-- 上传的表单 -->
				<form method="post" id="myForm" action="#" enctype="multipart/form-data" name="myForm">
					
					<input type="file" id="myFile" multiple>
					
					<input type="file" id="conMyFile" multiple>
					<button type="button" class="uploadBtn">上传视频</button>

					<!-- 上传的文件列表 -->
					<table id="upload-list">
						<thead>
							<tr>
								<th>文件名</th>
								<th>上传进度</th>
								<th>
									<input type="button" id="del-all-btn" value="全部删除">
								</th>
								<th>
									<input type="button" id="upload-all-btn" value="全部上传">
								</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<button type="button" class="conBtn">继续上传</button>
					
					<button class="tran" id="sureBtn" type="button" style="display: none;">提交</button>
					
				</form>
			</div>
			<div class="footer">
				<ul>
					<a href="/upload_video/upload">
						<li class="active firstLi">
							<div class="backVideoPic"></div>
							<p>文件上传</p>
			
						</li>
					</a>
					<a href="/upload_video/record">
						<li>
							<div class="recordPic"></div>
							<p>上传记录</p>
						</li>
					</a>
				</ul>
			
			</div>
			
		</div>
		
		<div class="model">
			<div class="remark">
				<h2>填写视频备注</h2>
				<div id="temDiv">
				</div>
				<div class="markBtn">
					<button type="button" class="remarkSure">确定</button>
					<button type="button" class="remarkCancle">取消</button>
				</div>
			</div>
			
			
		</div>
		
		<!-- 上传文件列表中每个文件的信息模版 -->
		<script type="text/template" id="file-upload-tpl">
			<tr>
				<!--<td>{#fileName#}</td>-->
				<td>{#fileotherName#}</td>
				<td class="upload-progress">{#progress#}</td>
				<td>
					<img src="{{asset('images/index/uploadVideo/dele.png')}}" class="del-item-btn">
				</td>
				<td>
					<input type="button" class="upload-item-btn" data-id="{#iptID#}"  data-name="{#fileName#}" data-size="{#totalSize#}" data-state="default" value="{#uploadVal#}">
				</td>
			</tr>
		</script>
		<script type="text/javascript" src="{{asset('include/jquery/jquery.min.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/index/uploadVideo/md5.js')}}" ></script>
		<script type="text/javascript" src="{{asset('js/index/uploadVideo/uploadVideo.js')}}"></script>
		
	</body>

</html>