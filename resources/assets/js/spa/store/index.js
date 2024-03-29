import Vue from 'vue'
import Vuex from 'vuex'

import axios from '../http'

// Modules
import auth from './auth'
import content from './content'
import messages from './messages'
import validation from './validation'
import settings from './settings'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    strict: debug,
    modules: {
        auth,
        content,
        messages,
        validation,
        settings
    },
    state: {
        schemas: {}
    },
    actions: {

    },
    mutations: {

    },
    getters: {
        categoryBySlugAndVersion: state => (version, slug) => {
            if (state.schemas[version]) {
                if (state.schemas[version].categories[slug]) {
                    return state.schemas[version].categories[slug]
                }
            }
            return {}
        }
    }
})
