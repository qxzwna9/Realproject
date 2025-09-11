<template>
  <v-app class="admin-luxury-app">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
      color="#111111"
      dark
      width="260"
    >
      <v-list nav dense class="py-4">
        <v-list-item-group
          v-model="selectedItem"
          active-class="gold--text"
        >
          <v-list-item
            v-for="(item, i) in items"
            :key="i"
            :to="item.to"
            router
            exact
            class="nav-item"
          >
            <v-list-item-icon class="mr-4">
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title class="font-weight-regular">{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
      color="#1A1A1A"
      flat
      dark
      height="65"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title class="brand-title" v-text="title" />
      <v-spacer />
      <span class="mr-4 font-weight-light">Welcome, {{ $store.state.user ? $store.state.user.name : 'Admin' }}</span>
      <v-btn icon @click="handleLogout" title="Logout">
        <v-icon>mdi-logout-variant</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main class="admin-main-content">
      <v-container fluid>
        <nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      drawer: true,
      selectedItem: 0,
      items: [
        { icon: 'mdi-view-dashboard-outline', title: 'Dashboard', to: '/admin/dashboard' },
        { icon: 'mdi-account-group-outline', title: 'User Management', to: '/admin/users' },
        { icon: 'mdi-package-variant-closed', title: 'Product Management', to: '/admin/products' },
        { icon: 'mdi-receipt-text-outline', title: 'Order Management', to: '/admin/orders' },
      ],
      title: 'ELVURE'
    }
  },
  methods: {
    async handleLogout() {
      await this.$store.dispatch('logout');
      this.$router.push('/Login');
    }
  }
}
</script>

  <style>
  .admin-luxury-app {
    font-family: 'Lato', sans-serif;
    background-color: #0A0A0A;
  }
  .admin-main-content {
    background-color: #F8F8F8;
  }
  .brand-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 2px;
  }
  .nav-item.v-list-item--active {
    background-color: rgba(212, 175, 55, 0.1);
    border-left: 3px solid #D4AF37; /* Gold Accent */
  }
  .gold--text .v-icon, .gold--text .v-list-item__title {
    color: #D4AF37 !important;
  }
  </style>