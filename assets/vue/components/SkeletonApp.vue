<template>
  <div class="wrapper">
    <div style="position:relative;">
      <div
        class="row no-gutters"
        style="box-shadow: 0 3px 7px 1px rgba(0,0,0,0.06);"
      >
        <div class="col py-5">
          <h1 class="text-center">
            Web & App IT Services
          </h1>

          <h6 class="text-center">
            Software Services
          </h6>
        </div>
      </div>
      <div class="row no-gutters">
        <div
          class="col-xs-12 col-md-6 px-5"
          style="background-color: #659dbd; padding-bottom: 150px;"
        >
          <h2 class="text-center mb-5 pt-5 text-white">
            API
          </h2>
          <p class="text-white">
            You are currently,
            <span v-if="isAuthenticated">
              authenticated as {{ getUser.username }}

              <a
                href="/api/logout"
                class="btn btn-warning btn-sm"
              >Log out</a>
            </span>
            <span v-else>not authenticated</span>
          </p>
          <p class="text-white">
            Check out the API Docs: <a
              :href="entrypoint"
              class="text-white"
            ><u>{{ entrypoint }}</u></a>
          </p>
        </div>
        <div
          class="col-xs-12 col-md-6 px-5"
          style="background-color: #7FB7D7; padding-bottom: 150px;"
        >
          <h2 class="text-center mb-5 pt-5 text-white">
            Or, login!
          </h2>
          <loginForm
            @user-authenticated="onUserAuthenticated"
          />
        </div>
      </div>
      <footer class="footer">
        <p class="text-muted my-5 text-center">
          Made with ❤️ by the <a
            style="text-decoration: underline; color: #6c757d; font-weight: bold;"
            href="http://www.robert-lange.eu"
          >Web & App</a> Team
        </p>
      </footer>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import loginForm from './LoginForm';

export default {
  components: {
    loginForm
  },
  props: {
    entrypoint: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      user: null
    }
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters["security/isAuthenticated"];
    },
    getUser() {
      return this.$store.getters["security/getUser"];
    },
  },
  mounted() {
    console.log("mounted");
    if (window.user) {
      this.user = window.user;
    }
  },
  methods: {
    onUserAuthenticated(userUri) {
      console.log("atut: " + userUri);
      axios
        .get(userUri)
        .then(response => {
          this.user = response.data;
          let payload = { isAuthenticated: true, user: this.user };
          this.$store.dispatch("security/onRefresh", payload);
          this.$store.dispatch("security/setIsAuthenticated", payload);
        })
    }
  }
}
</script>

<style scoped lang="scss">
    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        min-height: 60px;
        background-color: #f5f5f5;
    }
</style>