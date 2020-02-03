import axios from 'axios';

var prefix = "/mediator/";
/**
 * request请求
 * @param url
 * @param param
 * @returns {Promise<any>}
 */
function request(url, param = {}, method = 'post'){
    return new Promise((resolve, reject) => {
        axios({
            url: url,
            method: method,
            data: param
        })
        .then((res) => {
            if(res.data.code !== 200) {
                reject(res.data.info?res.data.info:'网络异常');
            } else {
                resolve(res.data.data);
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
        var form = new FormData();
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
        return new Promise((resolve, reject) => {
            request(prefix + 'doInfo', param)
                .then((res) => resolve(res))
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
    checkBankCard:(param) => {
        return new Promise((resolve, reject) => {
            request(prefix + 'checkBankCard', {card:param})
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    },


}