<template>
  <form @submit.prevent="handleSubmit">
    <div
      v-if="error"
      class="alert alert-danger"
    >
      {{ error }}
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input
        id="exampleInputEmail1"
        v-model="email"
        type="email"
        class="form-control"
        aria-describedby="emailHelp"
        placeholder="Enter email"
      >
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input
        id="exampleInputPassword1"
        v-model="password"
        type="password"
        class="form-control"
        placeholder="Password"
      >
    </div>
    <div class="form-check">
      <input
        id="exampleCheck1"
        type="checkbox"
        class="form-check-input"
      >
      <label
        class="form-check-label"
        for="exampleCheck1"
      >I accept the Terms of Use.</label>
    </div>
    <button
      type="submit"
      class="btn btn-primary"
      :class="{ disabled: isLoading }"
      @click="performLogin()"
    >
      Log in
    </button>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  props: {  user: {
    type: Object,
    default: null
  }},
  data() {
    return {
      email: '',
      password: '',
      error: '',
      isLoading: false
    }
  },
  methods: {
    async performLogin() {
      let payload = {login: this.$data.login, password: this.$data.password},
        redirect = this.$route.query.redirect;

      await this.$store.dispatch("security/login", payload);
      if (!this.$store.getters["security/hasError"]) {
        if (typeof redirect !== "undefined") {
          this.$router.push({path: redirect});
        } else {
          this.$router.push({path: "/home"});
        }
      }
    },
    handleSubmit() {
      this.isLoading = true;
      this.error = '';

      axios
        .post('/api/login', {
          email: this.email,
          password: this.password
        })
        .then(response => {
          console.log("login start: " + JSON.stringify(response));
          let payload = {user: response.data};
          this.$emit('user-authenticated', response.headers.location);
          this.$store.dispatch("security/setIsAuthenticated", payload);

          this.email = '';
          this.password = '';
        }).catch(error => {
          console.log(error);
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = 'Unknown error';
          }
        }).finally(() => {
          this.isLoading = false;
        })
    },
  },
}
</script>

<style scoped lang="scss">
</style>
