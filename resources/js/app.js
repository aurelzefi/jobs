import Vue from 'vue'
import axios from 'axios'
import Base from './base'
import Routes from './routes'
import VueI18n from 'vue-i18n'
import VueRouter from 'vue-router'
import App from './components/App'
import AlLocale from './lang/al.json'
import EnLocale from './lang/en.json'
import FormCreator from './FormCreator'

let locale = document.documentElement.lang
let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

Vue.prototype.$http = axios.create()
Vue.prototype.$form = FormCreator

Vue.use(VueI18n)
Vue.use(VueRouter)

Vue.mixin(Base)

const i18n = new VueI18n({
    locale,
    messages: {
        al: AlLocale,
        en: EnLocale,
    },
    silentTranslationWarn: true // temporary
})

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: `/${locale}`
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middleware === 'auth')) {
        if (window.App.user) {
            next()
        } else {
            window.location.replace(`/${locale}/login`)
        }
    } else if (to.matched.some(record => record.meta.middleware === 'guest')) {
        if (window.App.user) {
            window.location.replace(`/${locale}/jobs/all`)
        } else {
            next()
        }
    } else {
        next()
    }
})


new Vue({
    el: '#app',
    i18n,
    router,
    render: h => h(App),
    data: {
        banner: {
            style: 'success',
            message: ''
        }
    }
})
