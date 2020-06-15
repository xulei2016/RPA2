import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export const constantRoutes = [{
		path: '/',
		redirect: '/home',
	},
	{
		path: '/home',
		component: () => import('@/pages/home'),
	},
	{
		path: '/login',
		title: '登录',
		component: () => import('@/pages/login'),
	},

	//次席申请
	{
		path: '/cx/index',
		meta: {
			flag: 'cx'
		},
		component: () => import('@/pages/cixishenqing/index'),
	},
	{
		path: '/cx/info',
		meta: {
			flag: 'cx'
		},
		component: () => import('@/pages/cixishenqing/info'),
	},
	{
		path: '/cx/state',
		meta: {
			flag: 'cx'
		},
		component: () => import('@/pages/cixishenqing/state'),
	},



	//银期
	{
		path: '/yq/index',
		meta: {
			flag: 'yq'
		},
		component: () => import('@/pages/yingqibiangeng/index'),
	},
	{
		path: '/yq/list',
		meta: {
			flag: 'yq'
		},
		component: () => import('@/pages/yingqibiangeng/list'),
	},
	{
		path: '/yq/state',
		meta: {
			flag: 'yq'
		},
		component: () => import('@/pages/yingqibiangeng/state'),
	},
	// 404 page must be placed at the end !!!
	{
		path: '/404',
		component: () => import('@/pages/404'),
		hidden: true
	},
	{
		path: '*',
		redirect: '/404',
		hidden: true
	}

]

const createRouter = () => new Router({
	// mode: 'history', // require service support
	scrollBehavior: () => ({
		y: 0
	}),
	routes: constantRoutes
})

const router = createRouter()

export function resetRouter() {
	const newRouter = createRouter()
	router.matcher = newRouter.matcher // reset router
}

export default router
