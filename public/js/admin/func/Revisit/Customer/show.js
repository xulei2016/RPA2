$(function () {
    let modal = RPA.config.modal;
    let id = $(modal + ' form #id').val();

    /**
     * 页面初始化
     */
    function init() {
        bindEvent();

        const xhr = new XMLHttpRequest();
        xhr.open('get', `./rpa_customer_revisit/getAudio/${id}/`, true);
        // 请求类型 bufffer
        xhr.responseType = 'arraybuffer';
        xhr.onload = function () {
            if (xhr.status === 200 || xhr.status === 304) {
                let blob = new Blob([xhr.response], {type: 'audio/*'});
                let url = URL.createObjectURL(blob);
                if (typeof (url) == 'undefined') url = "";
                $('#audio')[0].src = url;
            }
        };
        xhr.send();
    }

    //事件绑定
    function bindEvent() {

    }

    init();
});