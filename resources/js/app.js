/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from "vue-router";
import routes from './routes';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '../sass/styles.scss'
import '@mdi/font/css/materialdesignicons.css'


Vue.use(VueRouter);

Vue.use(Vuetify, {
    iconfont: 'mdi' // 'md' || 'mdi' || 'fa' || 'fa4'
})

const app = new Vue({
    vuetify: new Vuetify(),
    el: '#app',
    router: new VueRouter(routes)
});
