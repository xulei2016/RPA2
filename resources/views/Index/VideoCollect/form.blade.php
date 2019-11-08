<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
	
		<title>BootstrapValidator demo</title>
		<style type="text/css">
			body {
				font-family: Arial;
			}
			
			form {
				margin: 50px auto;
				width: 600px;
			}
			
			input[type="button"] {
				cursor: pointer;
			}
			
			table {
				display: none;
				margin-top: 15px;
				border: 1px solid #ddd;
				border-collapse: collapse;
			}
			
			table th {
				color: #666;
			}
			
			table td,
			table th {
				padding: 5px;
				border: 1px solid #ddd;
				text-align: center;
				font-size: 14px;
			}
		</style>

	</head>

	<body>
		<!-- 上传的表单 -->
		<form method="post" id="myForm" action="/fileTest.php" enctype="multipart/form-data">
			<input type="file" id="myFile" multiple>
			<input type="file" id="conMyFile" multiple>
			<!-- 上传的文件列表 -->
			<table id="upload-list">
				<thead>
					<tr>
						<th width="35%">文件名</th>
						<th width="15%">文件类型</th>
						<th width="15%">文件大小</th>
						<th width="20%">上传进度</th>
						<th width="15%">
							<input type="button" id="upload-all-btn" value="全部上传">
						</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</form>
		<!-- 上传文件列表中每个文件的信息模版 -->
		<script type="text/template" id="file-upload-tpl">
			<tr>
				<td>{#fileName#}</td>
				<td>{#fileType#}</td>
				<td>{#fileSize#}</td>
				<td class="upload-progress">{#progress#}</td>
				<td>
					<input type="button" class="upload-item-btn"  data-id="{#iptID#}" data-name="{#fileName#}" data-size="{#totalSize#}" data-state="default" value="{#uploadVal#}">
				</td>
			</tr>
		</script>

		<script type="text/javascript" src="{{asset('uploadVideo/js/jquery-1.10.2.min.js')}}"></script>
		<script type="text/javascript">
			// 全部上传操作
			$(document).on('click', '#upload-all-btn', function() {
				// 未选择文件
				if(!$('#myFile').val()) {
					$('#myFile').focus();
				}
				// 模拟点击其他可上传的文件
				else {
					$('#upload-list .upload-item-btn').each(function() {
						$(this).click();
					});
				}
			});
			// 选择文件-显示文件信息
			$('#myFile,#conMyFile').change(function(e) {
				var file,
					uploadItem = [],
					uploadItemTpl = $('#file-upload-tpl').html(),
					size,
					percent,
					progress = '未上传',
					uploadVal = '开始上传';
				for(var i = 0, j = this.files.length; i < j; ++i) {
					file = this.files[i];
					percent = undefined;
					progress = '未上传';
					uploadVal = '开始上传';
					// 计算文件大小
					size = file.size > 1024 ?
						file.size / 1024 > 1024 ?
						file.size / (1024 * 1024) > 1024 ?
						(file.size / (1024 * 1024 * 1024)).toFixed(2) + 'GB' :
						(file.size / (1024 * 1024)).toFixed(2) + 'MB' :
						(file.size / 1024).toFixed(2) + 'KB' :
						(file.size).toFixed(2) + 'B';
					// 初始通过本地记录，判断该文件是否曾经上传过
					percent = window.localStorage.getItem(file.name + '_p');
					if(percent && percent !== '100.0') {
						progress = '已上传 ' + percent + '%';
						uploadVal = '继续上传';
					}
					// 更新文件信息列表
					uploadItem.push(uploadItemTpl
						.replace('{#iptID#}',$(this).attr("id"))
						.replace(/{#fileName#}/g, file.name)
						.replace('{#fileType#}', file.type || file.name.match(/\.\w+$/) + '文件')
						.replace('{#fileSize#}', size)
						.replace('{#progress#}', progress)
						.replace('{#totalSize#}', file.size)
						.replace('{#uploadVal#}', uploadVal)
					);
				}
				$('#upload-list').children('tbody').append(uploadItem.join(''))
					.end().show();
			});
			/**
			 * 上传文件时，提取相应匹配的文件项
			 * @param {String} fileName 需要匹配的文件名
			 * @return {FileList} 匹配的文件项目
			 */
			function findTheFile(fileName,ID) {
				var files = $('#'+ID)[0].files,
					theFile;
				for(var i = 0, j = files.length; i < j; ++i) {
					if(files[i].name === fileName) {
						theFile = files[i];
						break;
					}
				}
				return theFile ? theFile : [];
			}
			// 上传文件
			$(document).on('click', '.upload-item-btn', function() {
				var setId=$(this).attr("data-id");
				var $this = $(this),
					state = $this.attr('data-state'),
					msg = {
						done: '上传成功',
						failed: '上传失败',
						in: '上传中...',
						paused: '暂停中...'
					},
					fileName = $this.attr('data-name'),
					$progress = $this.closest('tr').find('.upload-progress'),
					eachSize = 1024,
					totalSize = $this.attr('data-size'),
					chunks = Math.ceil(totalSize / eachSize),
					percent,
					chunk,
					// 暂停上传操作
					isPaused = 0;
				// 进行暂停上传操作
				// 未实现，这里通过动态的设置isPaused值并不能阻止下方ajax请求的调用
				if(state === 'uploading') {
					$this.val('继续上传').attr('data-state', 'paused');
					$progress.text(msg['paused'] + percent + '%');
					isPaused = 1;
					console.log('暂停：', isPaused);
				}
				// 进行开始/继续上传操作
				else if(state === 'paused' || state === 'default') {
					$this.val('暂停上传').attr('data-state', 'uploading');
					isPaused = 0;
				}
				// 第一次点击上传
				startUpload('first');
				// 上传操作 times: 第几次
				function startUpload(times) {
					// 上传之前查询是否以及上传过分片
					chunk = window.localStorage.getItem(fileName + '_chunk') || 0;
					chunk = parseInt(chunk, 10);
					// 判断是否为末分片
					var isLastChunk = (chunk == (chunks - 1) ? 1 : 0);
					// 如果第一次上传就为末分片，即文件已经上传完成，则重新覆盖上传
					if(times === 'first' && isLastChunk === 1) {
						window.localStorage.setItem(fileName + '_chunk', 0);
						chunk = 0;
						isLastChunk = 0;
					}
					// 设置分片的开始结尾
					var blobFrom = chunk * eachSize, // 分段开始
						blobTo = (chunk + 1) * eachSize > totalSize ? totalSize : (chunk + 1) * eachSize, // 分段结尾
						percent = (100 * blobTo / totalSize).toFixed(1), // 已上传的百分比
						timeout = 5000, // 超时时间
						fd = new FormData($('#myForm')[0]);
					fd.append('theFile', findTheFile(fileName,setId).slice(blobFrom, blobTo)); // 分好段的文件
					fd.append('fileName', fileName); // 文件名
					fd.append('totalSize', totalSize); // 文件总大小
					fd.append('isLastChunk', isLastChunk); // 是否为末段
					fd.append('isFirstUpload', times === 'first' ? 1 : 0); // 是否是第一段（第一次上传）
					
					console.log(fd.get("theFile"))
					// 上传
					$.ajax({
						type: 'post',
						url: '/api/v1/upload',
						data: fd,
						processData: false,
						contentType: false,
						timeout: timeout,
						success: function(rs) {
							rs = JSON.parse(rs);
							// 上传成功
							if(rs.status === 200) {
								// 记录已经上传的百分比
								window.localStorage.setItem(fileName + '_p', percent);
								// 已经上传完毕
								if(chunk === (chunks - 1)) {
									$progress.text(msg['done']);
									$this.val('已经上传').prop('disabled', true).css('cursor', 'not-allowed');
									if(!$('#upload-list').find('.upload-item-btn:not(:disabled)').length) {
										$('#upload-all-btn').val('已经上传').prop('disabled', true).css('cursor', 'not-allowed');
									}
								} else {
									// 记录已经上传的分片
									window.localStorage.setItem(fileName + '_chunk', ++chunk);
									$progress.text(msg['in'] + percent + '%');
									// 这样设置可以暂停，但点击后动态的设置就暂停不了..
									// if (chunk == 10) {
									// isPaused = 1;
									// }
									console.log(isPaused);
									if(!isPaused) {
										startUpload();
									}
								}
							}
							// 上传失败，上传失败分很多种情况，具体按实际来设置
							else if(rs.status === 500) {
								$progress.text(msg['failed']);
							}
						},
						error: function() {
							$progress.text(msg['failed']);
						}
					});
				}
			});
		</script>
	</body>

</html>