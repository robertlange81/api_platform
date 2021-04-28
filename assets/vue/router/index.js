import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Imprint from "../views/Imprint";
import Posts from "../views/Posts";
import SkeletonApp from '../components/SkeletonApp';

Vue.use(VueRouter);

export default new VueRouter({
  mode: "history",
  routes: [
    { path: "/home", component: Home },
    { path: "/imprint", component: Imprint },
    { path: "/posts", component: Posts },
    {
      path: '/start',
      name: 'start',
      component: SkeletonApp
    },
    { path: "*", redirect: "/home" }
  ]
});