import axios from 'axios'

let config = {}

config.headers = {
    'X-Requested-With': 'XMLHttpRequest'
}

let instance = axios.create(config)

instance.interceptors.response.use((response) => {
    return response
}, function (error) {
    if (error.response.status === 401 || error.response.status === 419) {
        window.location.reload()
    } else {
        return Promise.reject(error)
    }
})

export default instance
