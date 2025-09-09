<template>
  <v-app class="admin-app">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
      color="#111827"
      dark
    >
      <v-list nav dense>
        <v-list-item-group
          v-model="selectedItem"
          active-class="primary--text text--accent-4"
        >
          <v-list-item
            v-for="(item, i) in items"
            :key="i"
            :to="item.to"
            router
            exact
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
      color="#1F2937"
      flat
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title class="font-weight-bold" v-text="title" />
      <v-spacer />
      <span class="mr-3">Welcome, {{ $store.state.user ? $store.state.user.name : 'Admin' }}</span>
      <v-btn icon @click="handleLogout" title="Logout">
        <v-icon>mdi-logout</v-icon>
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
        { icon: 'mdi-account-group-outline', title: 'Manage Users', to: '/admin/users' },
        { icon: 'mdi-tshirt-crew-outline', title: 'Manage Products', to: '/admin/products' },
        { icon: 'mdi-plus-box-outline', title: 'Add New Product', to: '/admin/add-product' },
      ],
      title: 'ELVURE :: Admin Panel'
    }
  },
  methods: {
    async handleLogout() {
      await this.$store.dispatch('logout');
      this.$router.push('/Login'); // เปลี่ยนให้ไปหน้า Login หลัก
    }
  }
}
</script>

<style>
.admin-app {
  font-family: 'Inter', sans-serif; /* แนะนำให้เพิ่ม Font นี้ใน nuxt.config.js */
}
.admin-main-content {
  background-color: #F3F4F6; /* สีพื้นหลังอ่อนๆ */
}
.v-list-item--active {
  background-color: rgba(255, 255, 255, 0.1);
  border-left: 4px solid #42a5f5; /* สี primary */
}
</style>