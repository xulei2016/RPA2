
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */




import Vue from 'vue';
import Vuerify from 'vuerify'; //验证器

import {
   NavBar,Field,Cell,CellGroup,List,Row,Col,Button,Dialog,
   Divider,Toast,Picker,Popup,DatetimePicker,SwitchCell,Icon,
   Checkbox, CheckboxGroup,RadioGroup,Radio,Panel,Grid,GridItem
} from 'vant';
import api from './mediator/api.js';
import util from './mediator/util.js';

var rootUrl = "./mediator/components/";
var pageUrl = rootUrl + "page/";


Vue.api = api;
Vue.utils = util;

Vue.component('layout', require(rootUrl + 'layout/Layout'));
Vue.component('login', require(pageUrl + 'Login')); // 登录
Vue.component('index', require(pageUrl + 'Index')); // 首页
Vue.component('id-card', require(pageUrl + 'IDCard')); // 上传身份证
Vue.component('perfect-information', require(pageUrl + 'PerfectInformation')); // 完善信息
Vue.component('sign', require(pageUrl + 'Sign')); // 签名
Vue.component('bank-card', require(pageUrl + 'BankCard')); // 银行卡
Vue.component('hand-id-card', require(pageUrl + 'HandIdCard')); // 手持银行卡
Vue.component('agreement', require(pageUrl + 'Agreement')); // 用户协议
Vue.component('agreement-inform', require(pageUrl + 'AgreementInform')); // 协议告知书
Vue.component('agreement-commitment', require(pageUrl + 'AgreementCommitment')); // 居间人自律承诺书
Vue.component('agreement-detail', require(pageUrl + 'AgreementDetail')); // 居间协议书
Vue.component('rate', require(pageUrl + 'Rate')); // 佣金返还比例
Vue.component('confirm-rate', require(pageUrl + 'ConfirmRate')); // 确认佣金返还比例
Vue.component('video-training', require(pageUrl + 'Video')); // 视频培训
Vue.component('review', require(pageUrl + 'Review')); // 考试
Vue.component('result', require(pageUrl + 'Result')); // 结果
Vue.component('info', require(pageUrl + 'Info'));

Vue.use(Row)
    .use(Col).use(NavBar).use(Cell).use(List).use(Field).use(Button).use(SwitchCell).use(Radio).use(RadioGroup)
    .use(Divider).use(CellGroup).use(Toast).use(Picker).use(Popup).use(DatetimePicker).use(Checkbox).use(CheckboxGroup)
    .use(Panel).use(Grid).use(GridItem).use(Dialog).use(Icon)
    .use(Vuerify);

const app = new Vue({
   el: '#app',
});