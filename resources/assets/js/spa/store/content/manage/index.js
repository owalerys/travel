import http from 'Travel/http'
import Vue from 'vue'
import uuid from 'uuid'

import overview from './overview'

export default {
    namespaced: true,
    state () {
        return {
            airline: null,
            results: [],
            searching: false,
            searchingArchives: false,
            creating: false,
            topic: {},
            creation: {
                busUuid: uuid.v4(),
                title: '',
                description: '',
                url: '',
                type: null
            },
            busUuid: uuid.v4()
        }
    },
    actions: {
        search ({ commit, state, dispatch }) {
            return new Promise((resolve, reject) => {
                commit('UPDATE_SEARCHING', { status: true })
                commit('PUSH_RESULTS', { articles: [] })
                http.post('/content/articles/active', { airline_id: state.airline }).then((result) => {
                    commit('UPDATE_SEARCHING', { status: false })
                    commit('PUSH_RESULTS', { articles: result.data })
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { busUuid: state.busUuid, message: error.response.data.message || 'An unexpected error occurred...', timeout: 10000, type: 'error' }, { root: true })
                    commit('UPDATE_SEARCHING', { status: false })
                    commit('PUSH_RESULTS', { articles: [] })
                    reject(error)
                })
            })
        },
        searchArchives ({ commit, state, dispatch }) {
            return new Promise((resolve, reject) => {
                commit('UPDATE_SEARCHING_ARCHIVES', { status: true })
                commit('PUSH_RESULTS', { articles: [] })
                http.post('/content/articles/archive', { airline_id: state.airline }).then((result) => {
                    commit('UPDATE_SEARCHING_ARCHIVES', { status: false })
                    commit('PUSH_RESULTS', { articles: result.data })
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { busUuid: state.busUuid, message: error.response.data.message || 'An unexpected error occurred...', timeout: 10000, type: 'error' }, { root: true })
                    commit('UPDATE_SEARCHING_ARCHIVES', { status: false })
                    commit('PUSH_RESULTS', { articles: [] })
                    reject(error)
                })
            })
        },
        create ({ commit, dispatch, state }) {
            return new Promise((resolve, reject) => {
                commit('UPDATE_CREATING', { status: true })
                http.post('/manage/article', {
                    title: state.creation.title,
                    description: state.creation.description,
                    airline_id: state.airline,
                    schema_version: state.topic.version,
                    category_slug: state.topic.slug,
                    url: (state.creation.type === 'link') ? state.creation.url : null,
                    type: state.creation.type
                }).then((result) => {
                    dispatch('messages/write', { type: 'success', message: 'Article created successfully, find it below.', busUuid: state.busUuid, timeout: 5000 }, { root: true })
                    commit('UPDATE_CREATING', { status: false })
                    commit('RESET_FORM')
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', message: error.response.data.message || 'An unexpected error occurred...', busUuid: state.creation.busUuid, timeout: 10000 }, { root: true })
                    dispatch('messages/writeFromErrorBag', { busUuid: state.creation.busUuid, timeout: 10000, bag: error.response.data.errors || {} }, { root: true })

                    commit('UPDATE_CREATING', { status: false })
                    reject(error)
                })
            })
        }
    },
    mutations: {
        PUSH_RESULTS (state, { articles }) {
            state.results.splice(0)
            state.results.push(...articles)
        },
        UPDATE_SEARCHING (state, { status }) {
            state.searching = status
        },
        UPDATE_SEARCHING_ARCHIVES (state, { status }) {
            state.searchingArchives = status
        },
        UPDATE_CREATING (state, { status }) {
            state.creating = status
        },
        UPDATE_AIRLINE (state, { airline_id }) {
            state.airline = airline_id
        },
        UPDATE_TOPIC (state, { topic }) {
            Vue.set(state, 'topic', topic || {})
        },
        UPDATE_TYPE (state, { type }) {
            state.creation.type = type
        },
        UPDATE_TITLE (state, { title }) {
            state.creation.title = title
        },
        UPDATE_DESCRIPTION (state, { description }) {
            state.creation.description = description
        },
        UPDATE_URL (state, { url }) {
            state.creation.url = url
        },
        RESET_FORM (state) {
            state.creation.type = null
            state.creation.title = ''
            state.creation.description = ''
            state.creation.url = ''
        }
    },
    modules: {
        overview
    }
}
