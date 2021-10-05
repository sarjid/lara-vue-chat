
require('./bootstrap');
window.Vue = require('vue');

import Vuex from 'vuex'
Vue.use(Vuex)

import storeVuex from "./store/store"
const store = new Vuex.Store(storeVuex);

 //filter imoport
 import filter from './filter'

 //vue chat scroll
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

Vue.component('main-app', require('./components/MainApp.vue').default);
window.moment = require('moment');
const app = new Vue({
    el: '#app',
    store
});
