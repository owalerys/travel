import uuid from 'uuid'

export default {
    namespaced: true,
    state () {
        return {
            errors: []
        }
    },
    getters: {
        errors: (state) => (formUuid, key) => {
            console.log(formUuid)
            console.log(key)

            let errors = state.errors.filter((item) => {
                return item.formUuid === formUuid && item.key === key
            })

            console.log(errors)

            if (errors.length) {
                let result = []

                for (let i = 0; i < errors.length; i++) {
                    result.push(...errors[i].value)
                }

                return result
            } else {
                return []
            }
        },
        hasErrors: (state, getters) => (formUuid, key) => {
            return getters.errors(formUuid, key).length > 0
        },
        categoryErrors: (state) => (formUuid, key) => {
            return state.errors.filter((item) => {
                return item.formUuid === formUuid && item.key.match(key) !== null
            }).length > 0
        }
    },
    actions: {
        load ({ commit }, { formUuid, errors }) {
            return new Promise((resolve, reject) => {
                for (let prop in errors) {
                    if (errors.hasOwnProperty(prop)) {
                        commit('SET_ERROR', { formUuid, key: prop, value: errors[prop] })
                    }
                }
                resolve()
            })
        },
        flush ({ commit }, { formUuid }) {
            return new Promise((resolve, reject) => {
                commit('CLEAR_FORM', { formUuid })
                resolve()
            })
        }
    },
    mutations: {
        SET_ERROR (state, { formUuid, key, value }) {
            let errorIndex = state.errors.findIndex((item) => {
                return item.formUuid === formUuid && item.key === key
            })

            if (errorIndex !== -1) {
                state.errors.splice(errorIndex, 1, { formUuid, key, value })
            } else {
                state.errors.push({ formUuid, key, value })
            }
        },
        CLEAR_FORM (state, { formUuid }) {
            let i = 0

            while (i !== -1) {
                i = state.errors.findIndex((item) => {
                    return item.formUuid === formUuid
                })

                if (i > -1) {
                    state.errors.splice(i, 1)
                }
            }
        }
    }
}
