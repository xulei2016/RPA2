import Vue from 'vue'
import App from './App.vue'

import router from './router'

import { 
	Button, 
	Form,
	Field, 
	Step, 
	Steps, 
	Uploader,
	RadioGroup, 
	Radio,
	Icon,
	Col, 
	Row,
	Toast, 
	Picker,
	Popup,
	Tag,
	Card
	} 
from 'vant';

Vue.use(Button);
Vue.use(Field);
Vue.use(Form);
Vue.use(Step).use(Steps).use(Uploader).use(RadioGroup).use(Radio).use(Icon);
Vue.use(Col);
Vue.use(Row);
Vue.use(Toast);
Vue.use(Picker);
Vue.use(Popup);
Vue.use(Tag).use(Card);
 
import VueClipboard from 'vue-clipboard2'
VueClipboard.config.autoSetContainer = true
Vue.use(VueClipboard)

import TitleNav from './components/headerNav'
Vue.use(TitleNav)

Vue.config.productionTip = false

const whiteList = ['/login', '/home']; 
router.beforeEach(async(to, from, next) => {
	document.title = to.title || '华安期货';
	
	if (whiteList.indexOf(to.path) !== -1) {
		next();
	}else{
		let login = localStorage.getItem('login');
		if(login){
			next();
		}else{
			if(to.meta){
				next('/login?t='+to.meta.flag);
			}
		}
	}
})

new Vue({
	router,
  render: h => h(App),
}).$mount('#app')
