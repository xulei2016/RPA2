$(function(){
    /*
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        $('#pjax-container form .switch input').bootstrapSwitch({onText:"是", offText:"否"});

        //基本信息提交
        $('#pjax-container form button.submit').click(function(){
            if('changePWD' == $(this).prev().val()){
                $(this).parents('form').find('input').each(function(){
                    if(!$(this).val()){
                        swal('哎呦……','请完成提交信息！！','warning');
                        return;
                    }
                });
            }
            add($(this).parents('form'));
        });
    }

    //添加
    function add(e){
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_profile',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
                $.pjax.reload('#pjax-container');
            }else{
                swal('哎呦……',json.info,'warning');
            }
        },
        error:RPA.errorReponse
    };

    	
	var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
		browse_button : 'plupload-avatar',
		url : '/admin/sys_profile_head_img',
		flash_swf_url : 'js/Moxie.swf',
        silverlight_xap_url : 'js/Moxie.xap',
        multipart_params: { '_token': LA.token, 'type': 'file'},
		filters: {
		  mime_types : [ //只允许上传图片文件
		    { title : "图片文件", extensions : "jpg,gif,png" }
		  ]
		}
	});
	uploader.init(); //初始化

	//绑定文件添加进队列事件
	uploader.bind('FilesAdded',function(uploader,files){
        uploader.start(); //开始上传
    });
    
    //上传完成
    uploader.bind('UploadComplete',function(uploader,files){
        toastr.success("上传成功！");
        previewImage(files.pop(),function(imgsrc){
            $('#pjax-container #plupload-avatar').prev().prev().attr('src', imgsrc);
        })
    });
    
    //上传失败！
    uploader.bind('Error',function(uploader,files){
        toastr.error("上传失败！");
    });

    //文件预览
	function previewImage(file,callback){//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
		if(!file || !/image\//.test(file.type)) return; //确保文件是图片
		if(file.type=='image/gif'){//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
			var fr = new mOxie.FileReader();
			fr.onload = function(){
				callback(fr.result);
				fr.destroy();
				fr = null;
			}
			fr.readAsDataURL(file.getSource());
		}else{
			var preloader = new moxie.image.Image();
			// var preloader = new mOxie.Image();
			preloader.onload = function() {
				preloader.downsize( 300, 300 );//先压缩一下要预览的图片,宽300，高300
				var imgsrc = preloader.type=='image/jpeg' ? preloader.getAsDataURL('image/jpeg',80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
				callback && callback(imgsrc); //callback传入的参数为预览图片的url
				preloader.destroy();
				preloader = null;
			};
			preloader.load( file.getSource() );
		}	
	}
	
    init();
});
