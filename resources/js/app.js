/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('topci', require('./components/TopCi.vue').default);
Vue.component('topsolver', require('./components/TopSolver.vue').default);
Vue.component('backlog', require('./components/Backlog.vue').default);
Vue.component('resolved', require('./components/Resolved.vue').default);
Vue.component('taskpro', require('./components/Progress.vue').default);
Vue.component('notstarted', require('./components/NotStarted.vue').default);
Vue.component('slatable', require('./components/SlaTable.vue').default);
Vue.component('update-incident', require('./components/UpdateIncident.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    el: '#app',
});
