import SecurityAPI from "../api/security";

export const AUTHENTICATING = "AUTHENTICATING",
  AUTHENTICATING_SUCCESS = "AUTHENTICATING_SUCCESS",
  PROVIDING_DATA_ON_REFRESH_SUCCESS = "PROVIDING_DATA_ON_REFRESH_SUCCESS",
  AUTHENTICATING_ERROR = "AUTHENTICATING_ERROR";

export default {
  namespaced: true,
  state: {
    isLoading: false,
    error: null,
    isAuthenticated: false,
    user: null
  },
  getters: {
    isLoading(state) {
      return state.isLoading;
    },
    hasError(state) {
      return state.error !== null;
    },
    error(state) {
      return state.error;
    },
    isAuthenticated(state) {
      console.log("isAuthenticated: " + state.isAuthenticated);
      return state.isAuthenticated;
    },
    getUser(state) {
      return state.user;
    },
    hasRole(state) {
      return role => {
        return state.user.roles.indexOf(role) !== -1;
      }
    }
  },
  mutations: {
    [AUTHENTICATING](state) {
      state.isLoading = true;
      state.error = null;
      console.log("[AUTHENTICATING](state)");
      state.isAuthenticated = false;
      state.user = null;
    },
    [AUTHENTICATING_SUCCESS](state, user) {
      console.log("AUTHENTICATING_SUCCESS");
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
      state.user = user;
    },
    [AUTHENTICATING_SUCCESS](state) {
      console.log("AUTHENTICATING_SUCCESS");
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = true;
    },
    [PROVIDING_DATA_ON_REFRESH_SUCCESS](state, payload) {
      state.isLoading = false;
      state.error = null;
      state.isAuthenticated = payload.isAuthenticated;
      state.user = payload.user;
    },
    [AUTHENTICATING_ERROR](state, error) {
      console.log("AUTHENTICATING_ERROR");
      state.isLoading = false;
      state.error = error;
      state.isAuthenticated = false;
      state.user = null;
    }
  },
  actions: {
    async login({commit}, payload) {
      commit(AUTHENTICATING);
      try {
        let response = await SecurityAPI.login(payload.login, payload.password);
        commit(AUTHENTICATING_SUCCESS, response.data);
        console.log("AUTHENTICATING_SUCCESS");
        return response.data;
      } catch (error) {
        console.log(error);
        commit(AUTHENTICATING_ERROR, error);
        return null;
      }
    },
    async setIsAuthenticated({commit}, payload) {
      try {
        commit(AUTHENTICATING_SUCCESS, payload);
        console.log("AUTHENTICATING_SUCCESS");
        return true;
      } catch (error) {
        console.log(error);
        commit(AUTHENTICATING_ERROR, error);
        return null;
      }
    },
    onRefresh({commit}, payload) {
      commit(PROVIDING_DATA_ON_REFRESH_SUCCESS, payload);
    }
  }
}