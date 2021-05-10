<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <router-link
        class="navbar-brand"
        to="/home"
      >
        Home
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon" />
      </button>
      <div
        id="navbarNav"
        class="collapse navbar-collapse"
      >
        <ul class="navbar-nav">
          <router-link
            class="nav-item"
            tag="li"
            to="/imprint"
            active-class="active"
          >
            <a class="nav-link">Imprint</a>
          </router-link>
          <router-link
            class="nav-item"
            tag="li"
            to="/start"
            active-class="active"
          >
            <a class="nav-link">Start</a>
          </router-link>

          <router-link
            class="nav-item"
            tag="li"
            to="/posts"
            active-class="active"
          >
            <a class="nav-link">Posts</a>
          </router-link>
          <li
            v-if="isAuthenticated"
            class="nav-item"
          >
            <a
              class="nav-link"
              href="/api/logout"
            >Logout</a>
          </li>
        </ul>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: 'App',
  computed: {
    isAuthenticated() {
      return this.$store.getters["security/isAuthenticated"];
    }
  },
  /* handle unauthorized => redirect to login */
  created() {
    let isAuthenticated = JSON.parse(this.$parent.$el.attributes["data-is-authenticated"].value);
    console.log(isAuthenticated);
    console.log("user: " + this.$parent.$el.attributes["data-user"].value);
    let user = JSON.parse(this.$parent.$el.attributes["data-user"].value);

    let payload = { isAuthenticated: isAuthenticated, user: user };
    this.$store.dispatch("security/onRefresh", payload);
    axios.interceptors.response.use(undefined, (err) => {
      return new Promise(() => {
        if (err.response.status === 401) {
          this.$router.push({path: "/login"})
        }
        throw err;
      });
    });
  },
}
</script>