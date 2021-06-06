/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
window.$ = window.jQuery = require('jquery');


// window.perfectScrollbar = require('perfect-scrollbar/jquery')($);
window.Cropper = require('cropperjs');
window.Cropper = 'default' in window.Cropper ? window.Cropper['default'] : window.Cropper;
window.toastr = require('toastr');
window.DataTable = require('datatables');
require('datatables-bootstrap3-plugin/media/js/datatables-bootstrap3');
require('dropzone');
require('nestable2');

// require('bootstrap');
require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('./bootstrap');


window.EasyMDE = require('easymde');
require('jquery-match-height');
require('bootstrap-toggle');
require('bootstrap-switch');
require('select2');
require('eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker');
var brace = require('brace');
require('brace/mode/json');
require('brace/theme/github');

window.TinyMCE = window.tinymce = require('tinymce');



// Includes
require('../../vendor/sierratecnologia/pedreiro/resources/assets/js/adminlte.js');
// require('../../vendor/sierratecnologia/stalker/resources/js/app.js');

// import Vue from 'vue'
// import VueVideoPlayer from 'vue-video-player'
// // require videojs style
// import 'video.js/dist/video-js.css'
// // import 'vue-video-player/src/custom-theme.css'
// Vue.use(VueVideoPlayer, /* {
//   options: global default options,
//   events: global videojs events
// } */)
import VueCoreVideoPlayer from 'vue-core-video-player'
//...
Vue.use(VueCoreVideoPlayer)

window.VueGallery = require('vue-gallery');



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
