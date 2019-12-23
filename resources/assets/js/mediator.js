
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
   NavBar,Field,Cell,CellGroup,List,Row,Col,Button,
   Divider,Toast,Picker,Popup,DatetimePicker,SwitchCell,
   Checkbox, CheckboxGroup,RadioGroup,Radio,Panel
} from 'vant';
import Layout from './mediator/components/layout/Layout';
import api from './mediator/api.js';

import 'vant/lib/index.css';
Vue.api = api;



Vue.component('layout', require('./mediator/components/layout/Layout'));

Vue.component('login', require('./mediator/components/page/Login.vue')); // 登录
Vue.component('id-card', require('./mediator/components/page/IDCard.vue')); // 上传身份证
Vue.component('perfect-information', require('./mediator/components/page/PerfectInformation.vue')); // 完善信息
Vue.component('sign', require('./mediator/components/page/Sign.vue')); // 签名
Vue.component('bank-card', require('./mediator/components/page/BankCard.vue')); // 银行卡
Vue.component('hand-id-card', require('./mediator/components/page/HandIdCard.vue')); // 手持银行卡
Vue.component('agreement', require('./mediator/components/page/Agreement.vue')); // 用户协议
Vue.component('rate', require('./mediator/components/page/Rate.vue')); // 佣金返还比例
Vue.component('video-training', require('./mediator/components/page/Video.vue')); // 视频培训
Vue.component('review', require('./mediator/components/page/Review.vue')); // 考试

Vue.use(Row)
    .use(Col).use(NavBar).use(Cell).use(List).use(Field).use(Button).use(SwitchCell).use(Radio).use(RadioGroup)
    .use(Divider).use(CellGroup).use(Toast).use(Picker).use(Popup).use(DatetimePicker).use(Checkbox).use(CheckboxGroup)
    .use(Panel)
    .use(Layout)
    .use(Vuerify);

const app = new Vue({
   el: '#app',
});