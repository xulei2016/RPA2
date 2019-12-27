$(function(){
    var id;

    function init() {
        id = $('input#id').val();
        CKEDITOR.replace('remark', {
            height:100
        });

        bindEvent();
    }

    function bindEvent(){
        //发送事件
        $('.operation a').click(function(){
            var url;
            var type = $(this).attr('item-data');
            var recordId = $(this).attr('record-id');
            if(type === 'transfer') {
                return swal('Oops...', '暂未实现！！', 'warning');
            } else if(type === 'confirm') {
                url = "/admin/flow/pass/"+recordId;
            } else if(type === 'back') {
                url = "/admin/flow/unpass/"+recordId;
            }
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();

            var remark = $('#flowForm #remark').val();
            if( !remark){
                return swal('Oops...', '请完善发送信息！！', 'warning');
            }

            $.ajax({
                url:url,
                data:{remark:remark,id:recordId},
                type:'post',
                dataType:'json',
                success:function(json){
                    if(200 == json.code){
                        var newEvent = document.createEvent("CustomEvent");
                        newEvent.initCustomEvent("operationFlow",true,true, {id:id});
                        document.dispatchEvent(newEvent);
                        toastr.success('操作成功！');
                        setTimeout(function(){
                            $(RPA.config.modal).modal('hide');
                        }, 500);
                    }else{
                        toastr.error(json.info);
                    }
                },
                error:function(){
                    toastr.error("操作失败");
                }
            })

        });

        //展示图片
        $('a.file').on('click', function(){

            var type = $(this).attr('type');
            var url = $(this).attr('url');
            var name = $(this).attr('name');
            if(type == 'image') {
                Swal.fire({
                    title: name,
                    text: '',
                    imageUrl: '/'+url,
                    imageAlt: 'Custom image',
                    imageWidth: 600,
                })
            } else if(type == 'file') {
                window.location.href = '/'+url;
            }

        });

        //流程图
        $('a.flowPic').on('click', function(){
            console.log("流程图事件");
        })
    }

    init();
});