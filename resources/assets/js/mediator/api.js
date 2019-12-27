import axios from 'axios';

var prefix = "/index/mediator/";
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
            if(res.data.code != 200) {
                reject(res.data.info);
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
            request(prefix + 'sendCode', param)
                .then((res) => resolve(res))
                .catch(error => reject(error));
        })
    }



}