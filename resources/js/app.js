/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// Import Bootstrap an BootstrapVue CSS files (order is important)
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-croppa/dist/vue-croppa.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

import VueMq from 'vue-mq'

Vue.use(VueMq, {
  breakpoints: { // default breakpoints - customize this
    sm: 768,
    md: 1250,
    lg: Infinity,
  },
  defaultBreakpoint: 'sm' // customize this for SSR
})

import VueScreen from 'vue-screen';

Vue.use(VueScreen);

import VueSimpleAccordion from 'vue-simple-accordion';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';

Vue.use(VueSimpleAccordion, {
  // ... Options go here
});

Vue.use(VueSimpleAccordion);

 import Croppa from 'vue-croppa'

 Vue.use(Croppa)

import VueRouter from 'vue-router'
import router from './router'
Vue.use(VueRouter)

import VueFuse from 'vue-fuse'
Vue.use(VueFuse)

import VueParticles from 'vue-particles'
Vue.use(VueParticles)

import MoneySpinner from 'v-money-spinner'
Vue.use(MoneySpinner)

import { Form, HasError, AlertError } from 'vform';

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

import swal from 'sweetalert2'
window.swal = swal;

// import Vue from 'vue'
import vSelect from './components/complements/Select'

Vue.component('v-select', vSelect)

import InstantSearch from 'vue-instantsearch';
Vue.use(InstantSearch);

import VueOffline from 'vue-offline'
Vue.use(VueOffline)

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '5px'
})


   import VueElementLoading from 'vue-element-loading';

   Vue.component('VueElementLoading', VueElementLoading);

// import DropZone from 'dropzone-vue';
// // optionally import default styles
// import 'dropzone-vue/dist/dropzone-vue.common.css';
// Vue.use(DropZone)


import moment from 'moment';
import numeral from 'numeral';

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate',function(created){
    return moment(created).locale("es").format('L');
});

Vue.filter('mySecondDate',function(created){
    return moment(created).locale("es").subtract(6, 'days').calendar();
});

Vue.filter('myNumber',function(number){
    return numeral(number).format('0,0');
});

Vue.filter('myNumberDecimal',function(number){
    return numeral(number).format('0,0.0');
});

Vue.filter('myNumberPercentage',function(number){
    return numeral(number).format('0.0%');
});

Vue.filter('myFirstLetter', function(text){
    return text.slice(0,1).toUpperCase();
});

window.Fire =  new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
// Vue.component('td-s', require('./components/folders/td.vue').default);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('site-footer', require('./components/site/Footer.vue').default);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    next()
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
    },
    computed: {
        managementRoute: function () {
            let substring = "manage";
            return this.$route.path.includes(substring);
        }
    }
});
