import Vue from 'vue'
import Router from 'vue-router'

import ExampleComponent from '../components/ExampleComponent'

Vue.use(Router)

const routes = [
    { path: '/', component: ExampleComponent, name: 'test' }
]

const mode = 'history'

export default new Router({ mode, routes })
