
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

Vue.prototype.authorize = function(handler){
    return handler(window.App.user);
};

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/flash.vue'));
Vue.component('thread-view', require('./components/pages/thread.vue'));
Vue.component('paginator', require('./components/paginator.vue'));
Vue.component('user-notification', require('./components/UserNotification.vue'));


const app = new Vue({
    el: '#app'
});
