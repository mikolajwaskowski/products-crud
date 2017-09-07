
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

Vue.component('list', require('./components/List.vue'));
Vue.component('add', require('./components/Add.vue'));
Vue.component('edit', require('./components/Edit.vue'));

const app = new Vue({
    el: '#app',
    data: {
    	currentView: 'list',
    	productId: null
    },
    mounted() {
    	
    },
    methods: {
    	add() {
    		this.currentView = 'add';
    	},
    	back() {
    		this.currentView = 'list';
    	},
    	edit(id) {
    		this.productId = id;
    		this.currentView = 'edit';
    	}
    }
});
