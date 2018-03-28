import axios from 'Travel/http'
import manage from './manage'

export default {
    namespaced: true,
    state () {
        return {
            airlines: [],
            schemas: {},
            countries: []
        }
    },
    getters: {
        categoryBySlugAndVersion: state => (version, slug) => {
            if (state.schemas[version]) {
                if (state.schemas[version].categories[slug]) {
                    return state.schemas[version].categories[slug]
                }
            }
            return {}
        },
        airlines: state => {
            return state.airlines || []
        },
        search: state => {
            let search = []

            for (let prop in state.schemas) {
                if (state.schemas.hasOwnProperty(prop) === false) {
                    continue
                }
                for (let prop2 in state.schemas[prop].categories) {
                    if (state.schemas[prop].categories.hasOwnProperty(prop2) === false) {
                        continue
                    }
                    search.push({
                        display: state.schemas[prop].categories[prop2].title,
                        reference: {
                            slug: prop2,
                            version: prop
                        }
                    })
                }
            }

            search.sort((a, b) => {
                return a.display > b.display
            })

            return search
        },
        creationSearch: state => {
            let search = []

            for (let prop in state.schemas) {
                if (state.schemas.hasOwnProperty(prop) === false) {
                    continue
                }
                for (let prop2 in state.schemas[prop].categories) {
                    if (state.schemas[prop].categories.hasOwnProperty(prop2) === false) {
                        continue
                    }
                    if (state.schemas[prop].categories[prop2].accept_new_submissions !== true) {
                        continue
                    }
                    search.push({
                        display: state.schemas[prop].categories[prop2].title,
                        reference: {
                            slug: prop2,
                            version: prop
                        }
                    })
                }
            }

            search.sort((a, b) => {
                return a.display > b.display
            })

            return search
        }
    },
    actions: {
        async airlines ({ commit }) {
            return new Promise((resolve, reject) => {
                axios.get('/content/airlines').then((result) => {
                    commit('SYNC_AIRLINES', result.data)
                    resolve()
                }).catch((error) => {
                    commit('SYNC_AIRLINES', { airlines: [] })
                    reject(error)
                })
            })
        },
        async schemas ({ commit }) {
            return new Promise((resolve, reject) => {
                axios.get('/content/schema').then((result) => {
                    commit('SYNC_SCHEMAS', result.data)
                    resolve()
                }).catch((error) => {
                    commit('SYNC_SCHEMAS', { schemas: {} })
                    reject(error)
                })
            })
        },
        async countries ({ commit }) {
            return new Promise((resolve, reject) => {
                axios.get('/content/countries').then((result) => {
                    commit('SYNC_COUNTRIES', result.data)
                    resolve()
                }).catch((error) => {
                    commit('SYNC_COUNTRIES', { countries: {} })
                    reject(error)
                })
            })
        },
        async syncContent ({ dispatch }) {
            return new Promise((resolve, reject) => {
                Promise.all([dispatch('airlines'), dispatch('schemas'), dispatch('countries')]).then((values) => {
                    resolve()
                }).catch((error) => {
                    reject(error)
                })
            })
        }
    },
    mutations: {
        SYNC_AIRLINES (state, { airlines }) {
            for (let i = 0; i < airlines.length; i++) {
                airlines[i].search_name = airlines[i].iata_code + ' - ' + airlines[i].name
            }

            airlines.sort((a, b) => {
                return a.search_name > b.search_name
            })

            state.airlines.splice(0)
            state.airlines.push(...airlines)
        },
        SYNC_SCHEMAS (state, { schemas }) {
            state.schemas = schemas
        },
        SYNC_COUNTRIES (state, { countries }) {
            state.countries.splice(0)

            for (let prop in countries) {
                if (countries.hasOwnProperty(prop)) {
                    state.countries.push({
                        code: prop,
                        name: countries[prop]
                    })
                }
            }
        }
    },
    modules: {
        manage
    }
}
