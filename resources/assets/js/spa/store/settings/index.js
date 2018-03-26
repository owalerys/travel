import uuid from 'uuid'
import http from 'Travel/http'

export default {
    namespaced: true,
    state () {
        return {
            resetPassword: {
                password: null,
                password_confirmation: null,
                formUuid: uuid.v4(),
                submitting: false
            }
        }
    },
    actions: {
        submit ({ commit, dispatch, state }) {
            return new Promise((resolve, reject) => {
                commit('PASSWORD_SUBMISSION_STATUS', { status: true })
                dispatch('validation/flush', { formUuid: state.resetPassword.formUuid }, { root: true })
                http.patch('/profile/password', {
                    password: state.resetPassword.password,
                    password_confirmation: state.resetPassword.password_confirmation
                }).then((result) => {
                    dispatch('messages/write', { type: 'success', busUuid: state.resetPassword.formUuid, message: 'Successfully updated your password', timeout: 5000 }, { root: true })
                    commit('PASSWORD_SUBMISSION_STATUS', { status: false })
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', busUuid: state.resetPassword.formUuid, message: error.response.data.message || 'An unexpected error occurred...', timeout: 10000 }, { root: true })
                    dispatch('validation/load', { formUuid: state.resetPassword.formUuid, errors: error.response.data.errors || {} }, { root: true })
                    commit('PASSWORD_SUBMISSION_STATUS', { status: false })
                    reject()
                })
            })
        }
    },
    mutations: {
        UPDATE_PASSWORD (state, { password }) {
            state.resetPassword.password = password
        },
        UPDATE_PASSWORD_CONFIRMATION (state, { password_confirmation }) {
            state.resetPassword.password_confirmation = password_confirmation
        },
        PASSWORD_SUBMISSION_STATUS (state, { status }) {
            state.resetPassword.submitting = status
        }
    }
}
