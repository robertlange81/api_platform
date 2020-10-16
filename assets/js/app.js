import Vue from 'vue';
import SkeletonApp from './components/SkeletonApp';
import 'bootstrap/dist/css/bootstrap.css';
import '../scss/global.scss';

Vue.component('skeleton-app', SkeletonApp);

// TODO: https://blog.logrocket.com/how-to-write-a-vue-js-app-completely-in-typescript/
const app = new Vue({
    el: '#skeleton-app'
});
