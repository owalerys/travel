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
            busUuid: uuid.v4()
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
        }
    },
    modules: {
        editor
    }
}
