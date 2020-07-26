import Vue from 'vue';
import CheeseWhizApp from './components/CheeseWhizApp';
import 'bootstrap/dist/css/bootstrap.css';
import '../scss/global.scss';

Vue.component('cheese-whiz-app', CheeseWhizApp);

// TODO: https://blog.logrocket.com/how-to-write-a-vue-js-app-completely-in-typescript/
const app = new Vue({
    el: '#cheese-app'
});
