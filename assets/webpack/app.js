import "@babel/polyfill"; //for IE..

window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

// Vuravel packages
require('vue-kompo')
/*require('kompo-ckeditor')
require('kompo-googlemaps')
require('kompo-trix')
require('kompo-flatpickr')*/

const app = new Vue({ el: '#app' });
