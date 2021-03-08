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
        </ul>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<script>
export default {
  name: "App",
}
</script>