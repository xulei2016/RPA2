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
