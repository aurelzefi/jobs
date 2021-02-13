import Vue from 'vue';
import axios from 'axios';
import Base from './base';
import Routes from './routes';
import VueI18n from 'vue-i18n';
import VueRouter from 'vue-router';
import App from './components/App';
import AlLocale from './lang/al.json';
import EnLocale from './lang/en.json';

let locale = document.documentElement.lang;
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.prototype.$http = axios.create();

Vue.use(VueI18n);
Vue.use(VueRouter);

Vue.mixin(Base);

const i18n = new VueI18n({
    locale,
    messages: {
        al: AlLocale,
        en: EnLocale,
    },
});

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: `/${locale}`
});

new Vue({
    el: '#app',
    i18n,
    router,
    render: h => h(App),
    data() {
        return {};
    },
})
