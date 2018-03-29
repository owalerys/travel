import uuid from 'uuid'

export default {
    namespaced: true,
    state () {
        return {
            messages: []
        }
    },
    getters: {
        messages: (state) => (busUuid) => {
            return state.messages.filter((item) => {
                return item.busUuid === busUuid
            })
        }
    },
    actions: {
        write ({ commit, dispatch }, { busUuid, message, type, timeout }) {
            return new Promise((resolve, reject) => {
                let messageUuid = uuid.v4()

                commit('PUSH_MESSAGE', {
                    busUuid,
                    message,
                    messageUuid,
                    type,
                    value: true
                })

                if (timeout) {
                    setTimeout(() => {
                        dispatch('dismiss', { messageUuid }).then(() => {
                            resolve(messageUuid)
                        })
                    }, timeout)
                } else {
                    resolve(messageUuid)
                }
            })
        },
        writeFromErrorBag ({ dispatch }, { busUuid, bag, timeout }) {
            return new Promise((resolve, reject) => {
                let promises = []

                bag = bag || {}

                for (let prop in bag) {
                    if (bag.hasOwnProperty(prop)) {
                        let errors = bag[prop] || []

                        for (let i = 0; i < errors.length; i++) {
                            promises.push(dispatch('write', {
                                busUuid,
                                timeout,
                                type: 'error',
                                message: errors[i]
                            }))
                        }
                    }
                }

                if (promises.length === 0) {
                    resolve()
                    return
                }

                Promise.all(promises).then((result) => {
                    resolve()
                }).catch((error) => {
                    reject()
                })
            })
        },
        dismiss ({ commit }, { messageUuid }) {
            return new Promise((resolve, reject) => {
                commit('VALUE_FALSE', { messageUuid })
                setTimeout(() => {
                    commit('DISMISS_MESSAGE', { messageUuid })
                    resolve()
                }, 2000)
            })
        },
        flush ({ commit, getters }, { busUuid }) {
            return new Promise((resolve, reject) => {
                let toDelete = getters.messages(busUuid)

                for (let i = 0; i < toDelete.length; i++) {
                    commit('DISMISS_MESSAGE', { messageUuid: toDelete[i].messageUuid })
                }

                resolve(busUuid)
            })
        }
    },
    mutations: {
        PUSH_MESSAGE (state, { busUuid, message, messageUuid, type, value }) {
            state.messages.push({
                busUuid, message, messageUuid, type, value
            })
        },
        VALUE_FALSE (state, { messageUuid }) {
            let index = state.messages.findIndex((item) => {
                return item.messageUuid === messageUuid
            })

            if (index > -1) {
                state.messages[index].value = false
            }
        },
        DISMISS_MESSAGE (state, { messageUuid }) {
            let index = state.messages.findIndex((item) => {
                return item.messageUuid === messageUuid
            })

            if (index > -1) {
                state.messages.splice(index)
            }
        }
    }
}
