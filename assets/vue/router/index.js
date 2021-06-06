import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Imprint from "../views/Imprint";
import Login from "../views/Login";
import Posts from "../views/Posts";
import SkeletonApp from '../components/SkeletonApp';
import store from "../store";

Vue.use(VueRouter);

let router = new VueRouter({
  mode: "history",
  routes: [
    { path: "/home", component: Home },
    { path: "/imprint", component: Imprint },
    { path: "/login", component: Login },
    { path: "/posts", component: Posts, meta: { requiresAuth: true } },
    { path: "*", redirect: "/home" },
    {
      path: '/start',
      name: 'start',
      component: SkeletonApp
    },
    { path: "*", redirect: "/home" }
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (store.getters["security/isAuthenticated"]) {
      next();
    } else {
      console.log("next login");
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;