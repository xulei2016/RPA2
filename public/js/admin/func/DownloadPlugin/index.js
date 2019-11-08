$(function(){
    /*
     * 初始化
     */
    function init(){
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': LA.token }
        });
        bindEvent();

    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        /**
         * 申请
         */
        $(".apply").on('click', function(){
            var id = $(this).parent().attr('item-id');
            swal({
                title: '提示',
                html: "必须本人申请！<br />必须本人申请！<br />必须本人申请！<br />替别人申请会导致插件冲突无法使用",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确认',
                cancelButtonText: '取消'
            }).then(function(isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url:'/admin/rpa_download_plugin/apply',
                        type:'post',
                        dataType:'json',
                        data:{id:id},
                        success:function(r){
                            if(r.code == 200) {
                                swal(
                                    '提示',
                                    '申请成功',
                                    'success'
                                );
                                setTimeout(function(){
                                    $.pjax.reload('#pjax-container');
                                }, 1000);
        
                            } else {
                                swal(
                                    '提示',
                                    r.info,
                                    'warning'
                                );
                                return;
                            }
                        }
                    })
                }
            })
            
        });
        
        $('.instructions').on('click', function(){
            var html = $('#document').html();
            swal.fire({
                title:'插件通用安装指南',
                html: html,
                width:800
            })
        });

        $('.document').on('click', function(){
            var id = $(this).parent().attr('item-id');
            $.ajax({
                url:'/admin/rpa_download_plugin/document',
                data: {id:id},
                success:function(res){
                    if(res.code != 200) {
                        swal(res.info, '', 'info');
                    } else {
                        swal.fire({
                            title:'说明文档',
                            html: res.info,
                            width:800
                        })
                    }
                }
            });
        });

        /**
         * 下载
         */
        $(".download").on('click', function(){
            RPA.pjaxOperation.modelLoad($(this));
        });
    }





    init();
});
