import axios from '../../http'

export default {
    namespaced: true,
    state () {
        return {
            authenticated: true,
            username: '',
            id: null,
            name: null,
            email: null,
            roles: [],
            permissions: []
        }
    },
    getters: {
        isAuthenticated: state => {
            return state.authenticated
        },
        hasRole: state => role => {
            return state.roles.findIndex((storedRole) => {
                return role === storedRole.name
            }) > -1
        },
        hasPermission: state => permission => {
            return state.permissions.findIndex((storedPermission) => {
                return permission === storedPermission.name
            }) > -1 || state.roles.findIndex((storedRole) => {
                return storedRole.permissions.findIndex((storedPermission) => {
                    return permission === storedPermission.name
                }) > -1
            }) > -1
        },
        user (state) {
            return state
        }
    },
    actions: {
        /*authenticate ({ commit }, { username, password }) {
            return new Promise((resolve, reject) => {
                // call authentication api
                if (true) {
                    commit('AUTHENTICATE', { username })
                    resolve()
                } else {
                    commit('DEAUTHENTICATE')
                    reject()
                }
            })
        },*/
        permissions ({ commit }) {
            return new Promise((resolve, reject) => {
                axios.get('/profile').then((result) => {
                    commit('SYNC_PERMISSIONS', result.data)
                    resolve()
                }).catch((error) => {
                    reject(error)
                })
            })
        }
    },
    mutations: {
        AUTHENTICATE (state, { username }) {
            state.authenticated = true
        },
        DEAUTHENTICATE (state) {
            state.authenticated = false
            state.username = ''
        },
        SYNC_PERMISSIONS (state, { permissions, roles, name, email, id }) {
            state.roles.splice(0)
            state.permissions.splice(0)
            state.id = id
            state.name = name
            state.email = email

            state.roles.push(...roles)
            state.permissions.push(...permissions)
        }
    }
}
