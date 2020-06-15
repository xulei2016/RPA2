import  $ from 'jquery';
import {Toast} from 'vant';

let token = document.head.querySelector('meta[name="csrf-token"]');

if(token) {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': token.content}
    });
}


var prefix = "/mediator/";
/**
 * request请求
 * @param url
 * @param param
 * @returns {Promise<any>}
 */
function request(url, param = {}, method = 'post'){

    let option = {
        url: url,
        type: method,
        dataType:'json',
        data: param,
    };
    if(url === prefix + 'upload') {
        option.processData = false;
        option.contentType = false;
    }
    return new Promise((resolve, reject) => {
        $.ajax(option)
        .then((res) => {
            if(res.code !== 200) {
                reject(res.info?res.info:'网络异常');
            } else {
                resolve(res.data);
            }
        })
        .catch(function (error) {
            reject('网络错误');
        });
    });
}

export default {
    // 获取图片验证码
    getImageCode: () => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getImageCode', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //发送验证码
    sendCode: (param) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getCode', param)
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //登录
    doLogin: (param) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'doLogin', param)
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //上传图片
    uploadFile:(file, type) => {
        let form = new FormData();
        form.append('file', file);
        form.append('type', type);
        return new Promise((resolve, reject) => {
            request(prefix + 'upload', form)
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //上传个人信息
    doInfo:(param) => {
        Toast.loading({
            duration: 0,       // 持续展示 toast
            forbidClick: true, // 禁用背景点击
            loadingType: 'spinner',
        });
        return new Promise((resolve, reject) => {
            request(prefix + 'doInfo', param)
                .then((res) => {
                    Toast.success();
                    resolve(res);
                })
                .catch(error => reject(error));
        })
    },

    //确认居间比例
    doConfirmRate:(is_sure) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'doConfirmRate', {is_sure:is_sure})
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },


    //获取字典表信息
    getDistList:(type) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getDictionaries?type='+type, {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //获取部门信息
    getDeptList:() => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getRealDept', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //获取学历信息
    getEducationList:() => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getEducationList', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //获取银行列表
    getBankList:() => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getBankList', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //试题
    getReviewList:() => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getPotic', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    // 提交试题答案
    checkReview:(param) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'checkPotic', param)
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    // 身份证检测
    checkIdCard:(param) => {
        return new Promise((resolve, reject) => {
            if(param.length !== 18) {
                reject('请输入正确的证件号码!');
                return false;
            }
            request(prefix + 'checkIdCard', {card:param})
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    // 银行卡检测
    checkBankCard:(card, bankBranch) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'checkBankCard', {card:card, bankBranch:bankBranch})
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    // 保存协议
    saveAgreement:(agreement) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'saveAgreement', {agreement:agreement})
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },

    //获取协议
    getAgreement:() => {
        return new Promise((resolve, reject) => {
            request(prefix + 'getAgreement', {}, 'get')
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },


}