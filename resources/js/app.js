import Vue from 'vue'
import Vuex from "vuex";
import Vuetify from 'vuetify';
import Home from "./components/pages/HomeComponent";
import store from "./store/index"; // vuexストアを読み込む

require('./bootstrap');

Vue.component('HomeComponent', require('./components/pages/HomeComponent.vue').default);
Vue.use(Vuex)
Vue.use(Vuetify);

new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    store: store,
    components: {
        Home
    }
});
