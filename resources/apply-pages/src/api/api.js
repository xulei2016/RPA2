import request from '@/utils/request'

/* 给一个手机号发送验证码 */
export function sendCode(data) {
  return request({
    url: '/zt/sendCode',
    method: 'post',
    data
  })
}

/* 登录获取token */
export function login(data) {
  return request({
    url: '/zt/doLogin',
    method: 'post',
    data
  })
}

/* 获取用户最近的次席申请/取消数据 */
export function getLastCixiApply(params) {
  return request({
    url: '/zt/last/apply/data',
    method: 'get',
    params
  })
}

/* 获取用户最近的次席申请/取消成功的数据 */
export function getLastCixiApplySuccss(params) {
  return request({
    url: '/zt/last/apply/success/data',
    method: 'get',
    params
  })
}

/* 获取用户最近的银期变更或新增数据 */
export function getLastYqChange(params) {
  return request({
    url: '/zt/last/yingqi/change/data',
    method: 'get',
    params
  })
}

/*获取rpa银期列表 */
export function getYqChangeList(params) {
  return request({
    url: '/zt/yingqi/change/data/list',
    method: 'get',
    params
  })
}



/* ocr识别银行卡号和归属行 */
export function bankCardOcr(data) {
  return request({
    url: '/zt/bankcard/ocr',
    method: 'post',
    data
  })
}

//获取银行列表
export function getBankList(params) {
  return request({
    url: '/zt/get/bank/list',
    method: 'get',
    params
  })
}

//获取我的账户列表
export function getMyAccountList(params) {
  return request({
    url: '/zt/get/user/account/list',
    method: 'get',
    params
  })
}

/* 提交次席申请 */
export function commitCixiApply(data) {
  return request({
    url: '/zt/commit/cixi/apply',
    method: 'post',
    data
  })
}

/* 提交银期变更 */
export function commitYingqiChange(data) {
  return request({
    url: '/zt/commit/yingqi/change',
    method: 'post',
    data
  })
}
