import Vue from 'vue'
import Router from 'vue-router'

import ExampleComponent from '../components/ExampleComponent'

Vue.use(Router)

const routes = [
    { path: '/', redirect: 'kb', name: 'test' },
    { path: '/kb', component: ExampleComponent, name: 'kb' }
]

const mode = 'history'

export default new Router({ mode, routes, base: '/app/' })
