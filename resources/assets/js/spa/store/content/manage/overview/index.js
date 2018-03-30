import Vue from 'vue'
import http from 'Travel/http'
import uuid from 'uuid'
import editor from './editor'

export default {
    namespaced: true,
    state () {
        return {
            article_id: null,
            article: null,
            loading: false,
            busUuid: uuid.v4(),
            modifyingStatus: false
        }
    },
    actions: {
        fetch ({ commit, dispatch, state }, { article_id }) {
            return new Promise((resolve, reject) => {
                dispatch('messages/flush', { busUuid: state.busUuid }, { root: true }).then(() => {
                    commit('SET_ARTICLE', { article: null })
                    commit('UPDATE_ARTICLE_ID', { article_id })
                    commit('UPDATE_LOADING', { status: true })
                    http.get('/manage/article/' + article_id).then((result) => {
                        commit('SET_ARTICLE', { article: result.data })
                        commit('UPDATE_LOADING', { status: false })
                    }).catch((error) => {
                        dispatch('messages/write', { type: 'error', message: 'Article failed to fetch.', busUuid: state.busUuid, timeout: 10000 }, { root: true })
                        commit('SET_ARTICLE', { article: null })
                        commit('UPDATE_LOADING', { status: false })
                        dispatch('messages/write', { message: "An unknown error occurred trying to fetch the article.", busUuid: state.busUuid, type: 'error', timeout: 10000 }, { root: true })
                    })
                })
            })
        },
        refresh ({ dispatch, state }) {
            return dispatch('fetch', { article_id: state.article_id })
        },
        archive ({ commit, state, dispatch }) {
            commit('START_MODIFYING_STATUS')
            return new Promise((resolve, reject) => {
                http.patch('/manage/article/' + state.article_id + '/archive', {}).then((result) => {
                    dispatch('messages/write', { type: 'success', message: 'Article archived successfully.', timeout: 10000, busUuid: state.busUuid }, { root: true })
                    commit('STOP_MODIFYING_STATUS')
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', message: error.response.data.message || 'An unknown error occurred...', timeout: 10000, busUuid: state.busUuid }, { root: true })
                    commit('STOP_MODIFYING_STATUS')
                    reject()
                })
            })
        },
        activate ({ commit, state, dispatch }) {
            commit('START_MODIFYING_STATUS')
            return new Promise((resolve, reject) => {
                http.patch('/manage/article/' + state.article_id + '/activate', {}).then((result) => {
                    dispatch('messages/write', { type: 'success', message: 'Article re-activated successfully.', timeout: 10000, busUuid: state.busUuid }, { root: true })
                    commit('STOP_MODIFYING_STATUS')
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', message: error.response.data.message || 'An unknown error occurred...', timeout: 10000, busUuid: state.busUuid }, { root: true })
                    commit('STOP_MODIFYING_STATUS')
                    reject()
                })
            })
        }
    },
    mutations: {
        UPDATE_ARTICLE_ID (state, { article_id }) {
            state.article_id = article_id
        },
        SET_ARTICLE (state, { article }) {
            Vue.set(state, 'article', article)
        },
        UPDATE_LOADING (state, { status }) {
            state.loading = status
        },
        START_MODIFYING_STATUS (state) {
            state.modifyingStatus = true
        },
        STOP_MODIFYING_STATUS (state) {
            state.modifyingStatus = false
        }
    },
    modules: {
        editor
    }
}
