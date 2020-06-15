import axios from 'axios'
import Vue from 'vue'
import {
	Toast,
	Dialog
} from 'vant'
Vue.use(Toast).use(Dialog);

const service = axios.create({
	baseURL: process.env.VUE_APP_BASE_API,
	timeout: 5000
})

// 请求拦截器
service.interceptors.request.use(
	config => {
		return config
	},
	error => {
		console.log(error)
		return Promise.reject(error)
	}
)

// 响应拦截器
service.interceptors.response.use(
	response => {
		const res = response.data
		//if the custom code is not 200, it is judged as an error.
		if (res.status == 200 || res.code == 200) {
			//无权限
			if (res.code == 10004) {
				Dialog.alert({
					title: '重新登录',
					message: res.msg || '登录身份已失效，即将重新登录'
				}).then(() => {
					localStorage.removeItem('login');
					window.location.reload();
				});

			} else {
				return res;
			}

		} else {
			Toast.clear();
			if (res.info == "用户状态监管休眠 激活后才能办理该业务") {
				Dialog.alert({
					title: '提示',
					confirmButtonText: '知道了',
					message: res.info,
				}).then(() => {
					// on close
				});
			} else {
				Toast({
					message: res.msg || res.info,
					duration: 4 * 1000
				});
			}

		}
	},
	error => {
		console.log('err' + error) // for debug
		// Message({
		//   message: error.message,
		//   type: 'error',
		//   duration: 5 * 1000
		// })
		return Promise.reject(error)
	}
)

export default service
