/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 import InstantSearch from 'vue-instantsearch';
window.Vue = require('vue');

Vue.use(InstantSearch);

import VueHighlightJS from 'vue-highlight.js';

// Highlight.js languages (All languages)
import 'vue-highlight.js/lib/allLanguages'

/*
* Import Highlight.js theme
* Find more: https://highlightjs.org/static/demo/
*/
import 'highlight.js/styles/atom-one-light.css';

/*
* Use Vue Highlight.js
*/
Vue.use(VueHighlightJS);

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
Vue.component('thread-search', require('./components/search.vue'));
Vue.component('text-editor', require('./components/editor.vue'));

const app = new Vue({
    el: '#app'
});
