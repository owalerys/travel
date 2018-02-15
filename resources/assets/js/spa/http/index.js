import axios from 'axios'

let config = {}

config.headers = {
    'X-Requested-With': 'XMLHttpRequest'
}

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    config.headers['X-CSRF-TOKEN'] = token.content;
} else {
    // console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

let instance = axios.create(config)

export default instance
