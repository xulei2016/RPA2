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
        $('.versionDocument').on('click', function(){
            var id = $(this).attr('item-id');
            $.ajax({
                url:'/admin/rpa_download_plugin/getDocumentByDocId/' + id,
                data: {},
                success:function(res){
                    if(res.code != 200) {
                        swal(res.info, '', 'info');
                    } else {
                        swal.fire({
                            title:'说明文档',
                            html: "<hr />" + res.data,
                            width:800
                        })
                    }
                }
            });
        });
    }

    init();
});