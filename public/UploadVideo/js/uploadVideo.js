// 全局变量
var random = Math.random();	//随机数
// 按钮替代点击事件
$(document).on("click", ".uploadBtn", function() {
	$('#myFile').click();
})
$(document).on("click", ".conBtn", function() {
	$('#conMyFile').click();

})
$(document).on("click", ".remarkCancle", function() {

	$("#upload-list tbody tr").each(function(){
		var fileName = $(this).find(".upload-item-btn").attr("data-name");
		percent = window.localStorage.getItem(fileName + '_p');
		if(!percent){
			$(this).find(".del-item-btn").click();
		}
	})
	$(".model").hide()

})

// 填写的备注和相应的视频对应起来
$(document).on("click", ".remarkSure", function() {
	//判断备注是否正确
	
	//1.备注不能为空
	$("#temDiv .remarkForm").each(function(){
		if($(this).find(".videoMark:checked").length == 0){
			alert("视频备注不能为空！")
			return;
		}
	})
	
	//2.不能选择相同备注
	var ks = $("#temDiv .remarkForm .marKDivCon").find(".ks:checked").length;
	var cjd = $("#temDiv .remarkForm .marKDivCon").find(".cjd:checked").length;
	var sms = $("#temDiv .remarkForm .marKDivCon").find(".sms:checked").length;
	var qt = $("#temDiv .remarkForm .marKDivCon").find(".qt:checked").length;

	if(ks >= 2 || cjd >= 2 || sms >= 2 || qt >=2){
		alert("视频备注不能相同");
		return;
	}

	//3.已经上传过的备注不能再次上传
	var getStroage =JSON.parse(window.localStorage.getItem("mIfo"));
	var id = window.localStorage.getItem("setId");
	$.ajax({
		url: "/api/v1/getRemark",
		type: 'post',
		async: true,
		data: {
			id: id
		},
		dataType: 'json',
		success: function(_data) {
			if(ks >= 1 && _data.indexOf("股指考试") > -1){
				alert("股指考试视频已经上传过了");
				return;
			}
			if(cjd >= 1 && _data.indexOf("股指成绩单签署") > -1){
				alert("股指成绩单签署视频已经上传过了");
				return;
			}
			if(sms >= 1 && _data.indexOf("股指期货风险说明书签署") > -1){
				alert("股指期货风险说明书签署视频已经上传过了");
				return;
			}
			if(qt >= 1 && _data.indexOf("其他") > -1){
				alert("其他视频已经上传过了");
				return;
			}
		}

	})

	$(".videoName").each(function() {
		var videoName = $(this).text();
		var remarkContent = "";
		$(this).parent().parent(".remarkForm").find(".videoMark:checked").each(function(){
			remarkContent += $(this).val() + ","; 
		})
		remarkContent = remarkContent.substring(0,remarkContent.length-1);

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
		if(!/video\/\w+/.test(sfile.type)) {
			alert("请选择视频文件")
			return false;
		}

		// 初始通过本地记录，判断该文件是否曾经上传过
		percent = window.localStorage.getItem(sfile.name + '_p');
		if(percent && percent !== '100.0') {
			progress = '已上传 ' + percent + '%';
			uploadVal = '继续上传';
		}

		// 更新文件信息列表
		uploadItem.length = 0;
		uploadItem.push(uploadItemTpl
			.replace('{#iptID#}', $this.attr("id"))
			.replace(/{#fileName#}/g, sfile.name)
			.replace('{#fileType#}', sfile.type || sfile.name.match(/\.\w+$/) + '文件')
			.replace('{#fileotherName#}', sfile.name.substr(0, 4))
			.replace('{#progress#}', progress)
			.replace('{#totalSize#}', sfile.size)
			.replace('{#uploadVal#}', uploadVal)

		);

		// 判断重复上传
		if(videoName.indexOf(sfile.name) == -1) {

			$('#upload-list').children('tbody').append(uploadItem.join(''))
				.end().show();
			videoName.push(sfile.name);
			$("#upload-list").show();
			var date = new Date();
			var time = date.getMilliseconds();
			// 视频备注的模态框
			$("#temDiv").append('<div class="remarkForm">' +
				'<div class="marKDivName">' +
				'<label class="title">视频名称:</label>' +
				'<p class="videoName">' + sfile.name + '</p>' +
				'</div>' +
				'<div class="marKDivCon">' +
				'<label class="title">视频内容:</label>' +
				'<input id="ks'+time+'" class="videoMark ks" type="checkbox" value="股指考试" /> <label for="ks'+time+'">股指考试</label><br/>' +
				'<input id="cjd'+time+'" class="videoMark cjd" type="checkbox" value="股指成绩单签署" /> <label for="cjd'+time+'">股指成绩单签署</label><br/>' +
				'<input id="sms'+time+'" class="videoMark sms" type="checkbox" value="股指期货风险说明书签署" /> <label for="sms'+time+'">股指期货风险说明书签署</label><br/>' +
				'<input id="qt'+time+'" class="videoMark qt" type="checkbox" value="其他" /> <label for="qt'+time+'">其他</label>' +
				'</div>' +
				'</div>');
			$(".model").show();

		} else {
			alert("你选择的视频已存在上传列表")
		};

	}

	$(".uploadBtn").hide();
	$(".conBtn").css({
		"display": "block"
	});
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
	window.localStorage.removeItem(delName + '_isPaused');

	// 删除表单节点
	$(this).closest("tr").remove();
	// 对于上传中的视频，需要中断ajax请求，请求后台删除文件块
	window.localStorage.setItem(delName + '_isPaused', 1);

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
	let $this = $(this),
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
		eachSize = 5*1024 * 1024,
		totalSize = $this.attr('data-size'),
		chunks = Math.ceil(totalSize / eachSize),
		percent,
		chunk,
		isPaused;
	// 进行暂停上传操作
	if(state === 'uploading') {
		$this.val('继续上传').attr('data-state', 'paused');
		percent = window.localStorage.getItem(fileName + '_p');
		window.localStorage.setItem(fileName + '_isPaused', 1)
	}
	// 进行开始/继续上传操作
	else if(state === 'paused' || state === 'default') {
		$this.val('暂停上传').attr('data-state', 'uploading');
		window.localStorage.setItem(fileName + '_isPaused', 0)
		//开始上传之后不能修改备注
		$("#temDiv .remarkForm").each(function(){
			if($(this).find(".videoName").text() == fileName){
				//console.log($(this).find(".videoName").text(),fileName);
				$(this).find(".videoMark").attr("disabled",true);
			}
		})
		// 第一次点击上传
		startUpload('first');
	}

	// 上传操作 times: 第几次
	function startUpload(times) {
		isPaused = window.localStorage.getItem(fileName + '_isPaused') || 0;
		isPaused = parseInt(isPaused, 10);
		if(!isPaused){
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
			fd.append("eachSize",videoFile.size)
			fd.append('filename', random + fileName); // 文件名
			fd.append('filesize', totalSize); // 文件总大小
			fd.append('remark', remark); // 视频备注
			fd.append('id', getId); // 关联的id
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
							isPaused = window.localStorage.getItem(fileName + '_isPaused') || 0;
							isPaused = parseInt(isPaused, 10);
							if(isPaused){
								$progress.text(msg['paused'] + percent + '%');
							}else{
								$progress.text(msg['in'] + percent + '%');
							}
							startUpload();
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
	}
});
