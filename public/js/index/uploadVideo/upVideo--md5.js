// 按钮替代点击事件
$(document).on("click", ".uploadBtn", function() {
	$('#myFile').click();
})
$(document).on("click", ".conBtn", function() {
	$('#conMyFile').click();

})
$(document).on("click", ".remarkCancle", function() {
	$(".model").hide()

})

// 填写的备注和相应的视频对应起来
$(document).on("click", ".remarkSure", function() {

	$(".videoName").each(function() {
		var videoName = $(this).text();
		var remarkContent = $(this).parent().parent(".remarkForm").find(".videoMark").val();

		$(".upload-item-btn").each(function() {
			var dataName = $(this).attr("data-name");
			if(videoName == dataName) {
				$(this).attr("data-remark", remarkContent)
			}
		})

	})

	$(".model").hide()

})

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

// 全部删除操作
$(document).on("click", "#del-all-btn", function() {

	$('#upload-list .del-item-btn').each(function() {
		$(this).click();
	});
})

// 防止加载重复视频数组
var videoName = [];

// ajax函数名
var currentAjax = null;

// 选择文件-显示文件信息
$('#myFile,#conMyFile').change(function(e) {
	
	var $this = $(this),
		file,
		uploadItem = [],
		uploadItemTpl = $('#file-upload-tpl').html(),
		size,
		percent,
		progress = '未上传',
		uploadVal = '开始上传';

	var that = this;
	for(var i = 0; i < that.files.length; i++) {
		
//		(function(i) {
			
			
			
			var sfile = that.files[i];
			percent = undefined;
			progress = '未上传';
			uploadVal = '开始上传';
			// 计算文件大小
			size = sfile.size > 1024 ?
				sfile.size / 1024 > 1024 ?
				sfile.size / (1024 * 1024) > 1024 ?
				(sfile.size / (1024 * 1024 * 1024)).toFixed(2) + 'GB' :
				(sfile.size / (1024 * 1024)).toFixed(2) + 'MB' :
				(sfile.size / 1024).toFixed(2) + 'KB' :
				(sfile.size).toFixed(2) + 'B';
			if(/video\/\w+/.test(sfile.type)) {
				alert("请选择视频文件")
				return false;
			}	
				
			// 初始通过本地记录，判断该文件是否曾经上传过
			percent = window.localStorage.getItem(sfile.name + '_p');
			if(percent && percent !== '100.0') {
				progress = '已上传 ' + percent + '%';
				uploadVal = '继续上传';
			}

//			let reader = new FileReader()
//				
//			reader.readAsBinaryString(sfile)
//			reader.onload = function() {
//				oMd5 = hex_md5(this.result);
//				console.log(sfile.name)
//				
//				appendMd5(oMd5)
//			}
//
//			function appendMd5(oMd5) {
				// 更新文件信息列表
				uploadItem.length = 0;
				uploadItem.push(uploadItemTpl
					.replace('{{iptID}}', $this.attr("id"))
					.replace(/{{fileName}}/g, sfile.name)
					.replace('{{fileType}}', sfile.type || sfile.name.match(/\.\w+$/) + '文件')
					.replace('{{fileotherName}}', sfile.name.substr(0, 4))
					.replace('{{progress}}', progress)
					.replace('{{totalSize}}', sfile.size)
					.replace('{{uploadVal}}', uploadVal)
//					.replace("{{md5}}", oMd5)
				);
			
				// 判断重复上传
				if(videoName.indexOf(sfile.name) == -1) {

					$('#upload-list').children('tbody').append(uploadItem.join(''))
						.end().show();
					videoName.push(sfile.name);
					$("#upload-list").show();

					// 视频备注的模态框
					$("#temDiv").append('<div class="remarkForm">' +
						'<div class="marKDivName">' +
						'<label>视频名称:</label>' +
						'<p class="videoName">' + sfile.name + '</p>' +
						'</div>' +
						'<div class="marKDivCon">' +
						'<label>备注:</label>' +
						'<textarea class="videoMark"></textarea>' +
						'</div>' +
						'</div>');
					$(".model").show();

				} else {
					alert("你选择的视频已存在上传列表")
				};

//			}
//		})(i)
	}

	$(".uploadBtn").hide();
	$(".conBtn").css({
		"display": "block"
	});

	console.log(videoName);
});

/**
 * 上传文件时，提取相应匹配的文件项
 * @param {String} fileName 需要匹配的文件名
 * @return {FileList} 匹配的文件项目
 */
function findTheFile(fileName, ID) {
	var files = $('#' + ID)[0].files,
		theFile;
	for(var i = 0, j = files.length; i < j; ++i) {
		if(files[i].name === fileName) {
			theFile = files[i];
			break;
		}
	}
	return theFile ? theFile : [];
}

// 删除文件
$(document).on("click", ".del-item-btn", function() {
	// 删除对应数组中的视频
	var delName = $(this).parent().parent().find(".upload-item-btn").attr("data-name")
	videoName.splice(videoName.indexOf(delName), 1)

	console.log(videoName)

	// 删除视频备注
	$(".videoName").each(function() {
		if($(this).text() == delName) {
			$(this).parent().parent(".remarkForm").remove();
		}
	})

	window.localStorage.removeItem(delName + '_chunk');
	window.localStorage.removeItem(delName + '_p');

	// 删除表单节点
	$(this).closest("tr").remove();

	// 无视频数据，隐藏表格
	var trLength = $("#upload-list").find("tbody").find("tr").length;
	if(trLength <= 0) {
		$("#upload-list").hide();
	};

	//删除时清空File表单值，否则影响继续添加视频
	$('#conMyFile').val("");

})

// 上传文件
$(document).on('click', '.upload-item-btn', function() {
	var setId = $(this).attr("data-id");
	var $this = $(this),
		state = $this.attr('data-state'),
		remark = $this.attr('data-remark'),
		msg = {
			done: '上传成功',
			failed: '上传失败',
			in: '上传中...',
			paused: '暂停中...'
		},
		fileName = $this.attr('data-name'),
		$progress = $this.closest('tr').find('.upload-progress'),
		//eachSize = 1024,
		eachSize = 1048576 * 10,
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
		var getId = window.localStorage.getItem("setId");
		// 如果第一次上传就为末分片，即文件已经上传完成，则重新覆盖上传
		if(times === 'first' && isLastChunk === 1) {
			window.localStorage.setItem(fileName + '_chunk', 0);
			chunk = 0;
			isLastChunk = 0;
		}

		// 判断暂停
		if($this.val() == "继续上传") {
			isPaused = 1;
		} else {
			isPaused = 0;
		}

		// 设置分片的开始结尾
		var blobFrom = chunk * eachSize, // 分段开始
			blobTo = (chunk + 1) * eachSize > totalSize ? totalSize : (chunk + 1) * eachSize, // 分段结尾
			percent = (100 * blobTo / totalSize).toFixed(1), // 已上传的百分比
			timeout = 5000, // 超时时间
			videoFile = findTheFile(fileName, setId).slice(blobFrom, blobTo), // 分段的视频 
			fd = new FormData($('#myForm')[0]);
			
			
			
		fd.append('video', videoFile); // 视频
		fd.append("blobNum", chunk) //当前的分段数
		fd.append("totalBlobNum", chunks) //分段总数

		fd.append('filename', fileName); // 文件名
		fd.append('filesize', totalSize); // 文件总大小
		fd.append('remark', remark); // 视频备注
		fd.append('id', getId); // 关联的id

		console.log(sha1(findTheFile(fileName, setId)),videoFile);
		
		// 上传
		currentAjax = $.ajax({
			type: 'post',
			url: '/api/v1/upload',
			data: fd,
			processData: false,
			contentType: false,
			timeout: timeout,
			success: function(rs) {
				//console.log(rs)

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