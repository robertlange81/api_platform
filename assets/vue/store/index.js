import Vue from "vue";
import Vuex from "vuex";
import PostModule from "./post";
import SecurityModule from "./security";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    post: PostModule,
    security: SecurityModule
  }
});