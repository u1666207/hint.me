
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

Vue.component('example', require('./components/Example.vue'));
Vue.component('question', require('./components/Question.vue'));
Vue.component('multiple', require('./components/Multiple.vue'));
Vue.component('short', require('./components/Short.vue'));
Vue.component('questionshow', require('./components/Quiz/QuestionShow.vue'));
Vue.component('multipleshow', require('./components/Quiz/MultipleShow.vue'));
Vue.component('shortshow', require('./components/Quiz/ShortShow.vue'));


const app = new Vue({
    el: '#app',
    props: ['questions'],

    created() {
        this.questions= JSON.parse(this.questions)
    },


});





