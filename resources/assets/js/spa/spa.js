// Polyfill
import 'babel-polyfill'

// Vue core
import Vue from 'vue'

// Bootstrap plugins
import store from './store'
import http from './http'
import router from './router'

// Front-end Vuetify Material framework
import Vuetify from 'vuetify'

// Integration helpers
import { sync } from 'vuex-router-sync'
import VueAxios from 'vue-axios'

// Install plugins
Vue.use(Vuetify)
Vue.use(VueAxios, http)

// Sync router and store
sync(store, router)

// Vue config
const debug = process.env.NODE_ENV !== 'production'
Vue.config.devtools = debug

import App from './components/App'

// Instantiate the app
new Vue({
    store,
    router,
    created () {
        window.VueInstance = this
    },
    render: h => h(App),
    el: '#app'
})
