$(function(){
    var error = {};
    var files = [];
    var interval = "";
    //初始化
    function init(){
        bindEvent();
    }

    //事件绑定
    function bindEvent(){
        $('#txt_file').on('click',function(){
            $(this).next().click();
        });

        $('input[name="txt_file"]').on('change', function (e) {
            files = e.target.files;

            //初始化
            getStart(e);

            //渲染文件
            renderExcelList(files);

            //就绪
            getReady();
        });

        $('.submit').on('click', function(){
            Swal({
                title: "确认上传?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        for(let i=0;i<files.length;i++){
                            let formData = new FormData();
                            formData.append("filename", document.getElementById("uploadFile").files[i]);
                            uploadFile(formData,i);
                        };
                        immedtask();
                    });
                },
                allowOutsideClick: false
            }).then(function() {
                Swal('上传成功', '', 'success');
            },function(dismiss){
                Swal(dismiss, '', 'error');
            });

            //if(confirm('确定上传？')){
            //    for(let i=0;i<files.length;i++){
            //        let formData = new FormData();
            //        formData.append("filename", document.getElementById("uploadFile").files[i]);
            //        uploadFile(formData,i);
            //    };
            //    $("#loading").modal('show');
            //    immedtask();
            //}else{
            //    return false;
            //}
        });
    }
    //发起rpa任务
    function immedtask(){
        $.ajax({
            method: 'post',
            url: '/admin/rpa_cotton/immedtask',
            dataType:'json',
            success: function (json) {
                // 检查excel是否解析完成
                if(json.code == 200){
                    interval = setInterval(isanalysis,1000);
                }
            }
        });
    }
    //查询excel是否解析完成
    let time = 0;
    function isanalysis(){
        $.ajax({
            method: 'post',
            url: '/admin/rpa_cotton/isanalysis',
            dataType:'json',
            success: function (json) {
                if(json){
                    time +=1;
                    if(10 <= time){
                        clearInterval(interval);
                        $("#modal").modal('hide');
                        $.pjax.reload('#pjax-container');
                    }
                }else{
                    report("数据解析完成，正在检查数据。。。",1);
                    clearInterval(interval);
                    //查询数据是否正确
                    checkdata();
                }
            }
        });
    }
    //查询数据是否正确
    function checkdata(){
        $.ajax({
            method: 'post',
            url: '/admin/rpa_cotton/checkdata',
            dataType:'json',
            success: function (json) {
                if(json.code == 200){
                    $("#modal").modal('hide');
                    $.pjax.reload('#pjax-container');
                }
            }
        });
    }
    //upload file
    var uploadFile = function(formData,i){
        $.ajax({
            method: 'post',
            url: '/admin/rpa_cotton/adddata',
            data: formData,
            dataType:'json',
            processData: false, // 告诉jQuery不要去处理发送的数据
            contentType: false, // 告诉jQuery不要去设置Content-Type请求头
            success: function (json) {
                i = i+1;
                if(200 == json.code){
                    //resolve(json);
                    $(".list-group-item").eq(i).prepend("<i style='color:green;font-size:16px;' class='glyphicon glyphicon-ok-circle'></i>");
                    report('第'+i+'个：'+json.info,1);
                }else{
                    $(".list-group-item").eq(i).prepend("<i style='color:green;font-size:16px;' class='glyphicon glyphicon-remove-circle'></i>");
                    report('第'+i+'个：'+json.info,0);
                }
            }
        });
        return ;
    }

    //获取excel列表   fa-file-excel-o
    function renderExcelList(files){
        var html = '';
        var index = 0;
        for(let excel of files){
            index += 1;
            var size = renderSize(excel.size);
            var name = (excel.name.length > 40) ? excel.name.substr(0, 37)+'...' : excel.name ;
            html += '<li class="list-group-item">'
                +'<span>'+index+'、</span><span><i class="fa fa-file-excel-o"></i></span>'
                +'<span>'+name+'</span><span class="pull-right">'+size+'</span></li>';
        }
        $('.file-group').html(html);
    }

    //初始化
    function getStart(e){
        //禁止解析完成前提交
        $('.modal-footer .submit').addClass('disabled');

        //隐藏input
        $('#txt_file').addClass('hidden');
    }

    //ready
    function getReady(e){
        //禁止解析完成前提交
        $('.modal-footer .submit').removeClass('disabled');

        $info = '程序就绪，等待上传！';
        report($info, true);
    }

    //反馈
    function report(mes, status){
        type = status ? 'text-success' : 'text-danger' ;
        status ? error.num + 1 : '' ;
        let info = '';
        info += '<span class="'+type+'"><i class="fa fa-exclamation"></i>'+mes+'</span><br/>';

        $('.error-group').append(info);
        return '';
    }

    // 格式化文件大小
    // filesize文件的大小,传入的是一个bytes为单位的参数
    function renderSize(value){
        if(null==value||value==''){
            return "0 Bytes";
        }
        var unitArr = new Array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
        var index=0;
        var srcsize = parseFloat(value);
        index=Math.floor(Math.log(srcsize)/Math.log(1024));
        var size =srcsize/Math.pow(1024,index);
        size=size.toFixed(2);//保留的小数位数
        return size+unitArr[index];
    }

    init();
});