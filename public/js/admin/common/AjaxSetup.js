/**
 * ajaxSetup
 *
 * @author hsu lay
 */
(function (){
    jQuery.ajaxSetup({


        //X-CSRF-TOKEN
        headers: {'X-CSRF-TOKEN': LA.token},


        //statusCode
        statusCode: {
            404: function() {
                alert('数据获取/输入失败，没有此服务。404');
            },
            504: function() {
                alert('数据获取/输入失败，服务器没有响应。504');
            },
            500: function() {
                alert('服务器有误。500');
            }
        }
    });
})(jQuery);
