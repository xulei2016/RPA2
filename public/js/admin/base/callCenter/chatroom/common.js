function get_time(timestamp) {
    var myDate;
    if (timestamp) {
        myDate = new Date(timestamp);
    } else {
        myDate =new Date();
    }

    return (myDate.getHours() > 9 ? myDate.getHours() : '0'+myDate.getHours()) + ":" + (myDate.getMinutes()>9 ? myDate.getMinutes() : '0'+myDate.getMinutes());
}

function get_date(timestamp) {
    var myDate;
    if (timestamp) {
        myDate =new Date(timestamp);
    } else {
        myDate =new Date();
    }
    var month = myDate.getMonth()+1;
    month = month > 9 ? month : '0'+month;
    var date = myDate.getDate();
    date = date > 9 ? date : '0'+date;
    return myDate.getFullYear()+"-"+month+"-"+date+" "+get_time(timestamp);
}

function htmlspecialchars(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function getbig(obj) {
    var src = $(obj).attr('src');
    var pswpElement = document.querySelectorAll('.pswp')[0];

    // build items array
    var items = [
        {
            src: src,
            w: obj.naturalWidth || obj.width,
            h: obj.naturalHeight || obj.height
        }
    ];

    // define options (if needed)
    var options = {
        // optionName: 'option value'
        // for example:
        index: 0, // start at first slide
        closeEl:true,
        captionEl: false,
        fullscreenEl: false,
        zoomEl: false,
        shareEl: false,
        counterEl: false,
        arrowEl: false,
        preloaderEl: false,
        tapToClose: true
    };

    // Initializes and opens PhotoSwipe
    var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
}

if (!Object.values) Object.values = function(obj) {
    if (obj !== Object(obj))
        throw new TypeError('Object.values called on a non-object');
    var val=[],key;
    for (key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj,key)) {
            val.push(obj[key]);
        }
    }
    return val;
};

if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
        value: function assign(target, varArgs) { // .length of function is 2
            'use strict';
            if (target == null) { // TypeError if undefined or null
                throw new TypeError('Cannot convert undefined or null to object');
            }

            let to = Object(target);

            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];

                if (nextSource != null) { // Skip over if undefined or null
                    for (let nextKey in nextSource) {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}

if (navigator.mediaDevices === undefined) {
    navigator.mediaDevices = {};
}
if (navigator.mediaDevices.getUserMedia === undefined) {
    navigator.mediaDevices.getUserMedia = function(constraints) {
        var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        if (!getUserMedia) {
            var e = new Error();
            e.name = '当前浏览器不支持语音';
            return Promise.reject(e);
        }
        return new Promise(function(resolve, reject) {
            getUserMedia.call(navigator, constraints, resolve, reject);
        });
    }
}

function show_user_media_error(e) {
    var error = e.name;
    switch (error) {
        case 'NotAllowedError':
        case 'PermissionDeniedError':
            error = e.name + ' 浏览器未获得麦克风权限';
            break;
        // 没接入录音设备
        case 'NotFoundError':
        case 'DevicesNotFoundError':
            error = e.name + '录音设备未找到';
            break;
        // 其它错误
        case 'NotSupportedError':
            error = e.name + '该浏览器不支持录音功能';
            break;
    }
    if (typeof layui.layer != 'undefined') {
        layui.layer.msg(error);
    } else {
        alert(error);
    }
}

function is_picture (str) {
    if(str.replace(/<\/?[^>]+(>|$)/g, "") != '') {
        return false;
    }
    return /<img.*? src=.*?>/i.test(str);
}