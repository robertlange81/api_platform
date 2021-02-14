import Vue from 'vue';
import SkeletonApp from './components/SkeletonApp';
import 'bootstrap/dist/css/bootstrap.css';
import '../scss/global.scss';

Vue.component('SkeletonApp', SkeletonApp);

// TODO: https://blog.logrocket.com/how-to-write-a-vue-js-app-completely-in-typescript/
new Vue({
  el: '#skeleton-app'
}).$mount("#app");
