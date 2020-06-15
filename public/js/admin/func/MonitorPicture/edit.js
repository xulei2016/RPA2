$(function () {
    let url_prefix = "/admin/rpa_monitor_picture/";
    let modal = RPA.config.modal;
    var time_type = $(modal + " form #type ").is(':checked');

    /**
     * 页面初始化
     */
    function init() {
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal + " form").validate(validateInfo);
    }

    //事件绑定
    function bindEvent() {
        //表单提交
        $(modal + ' form #save').click(function () {
            add($(this).parents('form'));
        });
        //点击
        $(modal + ' form a.choose').click(function () {

            var _this = $(this);
            var item = _this.attr('item');
            var val = _this.attr('val');
            $('#' + item + '_final').val(val);
        });

        $("div[data-fancybox]").on('click', function () {
            zoomMaker();
        });

        $(document).ready(function () {
            var crmAddress = $('#crm-address');
            var baiduAddress = $('#baidu-address');
            var res = getHighLightDifferent(crmAddress.attr('val'), baiduAddress.attr('val'));
            crmAddress.html(res[0]);
            baiduAddress.html(res[1]);
        });

    }

    function zoomMaker() {
        var _this = $('.fancybox-image');
        if (_this.length > 0) {
            _this.zoomMarker({
                src: _this.attr('src'),
                rate: 0.2,
                markers: []
            });
            clearTimeout(t);
        } else {
            var t = setTimeout(function () {
                zoomMaker();
            }, 1000);
        }
    }

    //添加
    function add(e) {
        // 确认
        var flag = true;
        var address_final = $('#address_final').val();
        var start_at_final = $('#start_at_final').val();
        var end_at_final = $('#end_at_final').val();

        var addressList = [];
        var startList = [];
        var endList = [];
        $('a[item="address"]').each(function (key, item) {
            addressList.push($(this).attr('val'));
        });
        $('a[item="start_at"]').each(function (key, item) {
            startList.push($(this).attr('val'));
        });
        $('a[item="end_at"]').each(function (key, item) {
            endList.push($(this).attr('val'));
        });


        if (!($.inArray(address_final, addressList) > -1)) {
            flag = false;
        }
        if (!($.inArray(start_at_final, startList) > -1)) {
            flag = false;
        }
        if (!($.inArray(end_at_final, endList) > -1)) {
            flag = false;
        }
        if (!flag) { // 发生修改
            Swal.fire({
                title: "提交确认",
                text: '最终结果与crm和失败结果均不一致,是否确认?',
                type: 'warning', // 弹框类型
                confirmButtonText: '确定',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
                focusCancel: true, // 是否聚焦 取消按钮
                reverseButtons: true  // 是否 反转 两个按钮的位置 默认是  左边 确定  右边 取消
            }).then((isConfirm) => {
                try {
                    //判断 是否 点击的 确定按钮
                    if (isConfirm.value) {
                        RPA.form.ajaxSubmit(e, FormOptions);
                    }
                } catch (e) {

                }
            });
        } else {
            RPA.form.ajaxSubmit(e, FormOptions);
        }
        return false;


    }

    var id = $(modal + ' form #id').val();
    //提交信息的表单配置
    var FormOptions = {
        url: url_prefix + id,
        success: function (json, xml) {
            if (200 == json.code) {
                RPA.form.response(function () {
                    //自动跳转下一条
                    if ($(modal + ' #form-continue').is(':checked')) {
                        var dataModel = $('#tb_departments').bootstrapTable('getRowByUniqueId', id);
                        if (dataModel.next_id != null) {
                            var url = url_prefix + dataModel.next_id + '/edit';
                            $(modal + ' .modal-content').text('').load(url);
                        } else {
                            $(modal).modal('hide');
                            toastr.error('当页数据已结束，请手动跳转下一页');
                        }

                    } else {
                        $(modal).modal('hide');
                    }
                });
            } else {
                toastr.error(json.info);
            }
        },
        error: RPA.form.errorReponse
    };

    //表单验证信息
    var validateInfo = {
        rules: {
            address_final: {
                required: true
            },
            start_at_final: {
                required: true,
                minlength: 8,
                maxlength: 8,
                number: true,
                max: 20991231
            },
            end_at_final: {
                required: true,
                minlength: 8,
                maxlength: 8,
                number: true,
                max: 20991231
            },
        },
        errorPlacement: function (error, element) {
            element.parent().append(error);
        }
    };

    function StringBuffer() {
        this.__strings__ = [];
    };
    StringBuffer.prototype.append = function (str) {
        this.__strings__.push(str);
        return this;
    };
    //格式化字符串
    StringBuffer.prototype.appendFormat = function (str) {
        for (var i = 1; i < arguments.length; i++) {
            var parent = "\\{" + (i - 1) + "\\}";
            var reg = new RegExp(parent, "g")
            str = str.replace(reg, arguments[i]);
        }

        this.__strings__.push(str);
        return this;
    }
    StringBuffer.prototype.toString = function () {
        return this.__strings__.join('');
    };
    StringBuffer.prototype.clear = function () {
        this.__strings__ = [];
    }
    StringBuffer.prototype.size = function () {
        return this.__strings__.length;
    }

    var flag = 1;

    function getHighLightDifferent(a, b) {

        var temp = getDiffArray(a, b);
        var a1 = getHighLight(a, temp[0]);

        var a2 = getHighLight(b, temp[1]);

        return new Array(a1, a2);
    }

    function getHighLight(source, temp) {
        var result = new StringBuffer();
        var sourceChars = source.split("");
        var tempChars = temp.split("");
        var flag = false;
        for (var i = 0; i < sourceChars.length; i++) {
            if (tempChars[i] != ' ') {
                if (i == 0) {
                    result.append("<span style='color:red'>");
                    result.append(sourceChars[i]);
                }
                else if (flag) {
                    result.append(sourceChars[i]);
                }
                else {
                    result.append("<span style='color:red'>");
                    result.append(sourceChars[i]);
                }
                flag = true;
                if (i == sourceChars.length - 1) {
                    result.append("</span>");
                }
            }
            else if (flag == true) {
                result.append("</span>");
                result.append(sourceChars[i]);
                flag = false;
            } else {
                result.append(sourceChars[i]);
            }
        }
        return result.toString();
    }

    function getDiffArray(a, b) {
        var result = new Array();
        //选取长度较小的字符串用来穷举子串
        if (a.length < b.length) {
            var start = 0;
            var end = a.length;
            result = getDiff(a, b, start, end);
        } else {
            var start = 0;
            var end = b.length;
            result = getDiff(b, a, 0, b.length);
            result = new Array(result[1], result[0]);
        }
        return result;

    }

    //将a的指定部分与b进行比较生成比对结果
    function getDiff(a, b, start, end) {
        var result = new Array(a, b);
        var len = result[0].length;
        while (len > 0) {
            for (var i = start; i < end - len + 1; i++) {
                var sub = result[0].substring(i, i + len);
                var idx = -1;
                if ((idx = result[1].indexOf(sub)) != -1) {
                    result[0] = setEmpty(result[0], i, i + len);
                    result[1] = setEmpty(result[1], idx, idx + len);
                    if (i > 0) {
                        //递归获取空白区域左边差异
                        result = getDiff(result[0], result[1], start, i);
                    }
                    if (i + len < end) {
                        //递归获取空白区域右边差异
                        result = getDiff(result[0], result[1], i + len, end);
                    }
                    len = 0;//退出while循环
                    break;
                }
            }
            len = parseInt(len / 2);
        }
        return result;
    }

    //将字符串s指定的区域设置成空格
    function setEmpty(s, start, end) {
        var array = s.split("");
        for (var i = start; i < end; i++) {
            array[i] = ' ';
        }
        return array.join("");
    }

    init();
});