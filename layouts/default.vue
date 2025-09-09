<template>
  <v-app dark>
    <v-app-bar
      app
      :clipped-left="clipped"
      color="rgba(17, 24, 39, 0.8)"
      flat
      dark
      height="60px"
      class="navbar-style"
    >
      <v-toolbar-title class="brand-title" v-text="title" />
      <v-spacer />

      <div v-if="$store.state.user && $store.state.user.user_type === 'admin'">
        <v-btn text to="/admin/dashboard" class="nav-btn">Dashboard</v-btn>
        <v-btn text to="/admin/users" class="nav-btn">Manage Users</v-btn>
        <v-btn text to="/admin/products" class="nav-btn">Manage Products</v-btn>
        <v-btn color="error" @click="handleLogout" class="logout-btn ml-2">
          <v-icon left>mdi-logout</v-icon>
          ออกจากระบบ
        </v-btn>
      </div>

      <div v-else-if="$store.state.isAuthenticated">
        <v-btn text to="/" class="nav-btn">หน้าหลัก</v-btn>
        <v-btn text to="/about" class="nav-btn">เกี่ยวกับเรา</v-btn>
        <v-btn text to="/info" class="nav-btn">ข้อมูล</v-btn>
        <v-btn text to="/contact" class="nav-btn">ติดต่อ</v-btn>
        <v-btn text to="/shirt" class="nav-btn">สินค้า</v-btn>

        <v-btn icon to="/cart" class="ml-2" title="ตะกร้าสินค้า">
          <v-icon>mdi-cart</v-icon>
        </v-btn>

        <v-btn color="error" @click="handleLogout" class="logout-btn ml-2">
          <v-icon left>mdi-logout</v-icon>
          ออกจากระบบ
        </v-btn>
      </div>

      <div v-else>
        <v-btn text to="/" class="nav-btn">หน้าหลัก</v-btn>
        <v-btn text to="/shirt" class="nav-btn">สินค้า</v-btn>
        <v-btn text to="/contact" class="nav-btn">ติดต่อเรา</v-btn>
        <v-btn text to="/about" class="nav-btn">เกี่ยวกับเรา</v-btn>
        <v-btn outlined to="/Login" class="login-btn">เข้าสู่ระบบ</v-btn>
      </div>
    </v-app-bar>

    <v-main>
      <nuxt />
    </v-main>

    <v-footer app absolute color="transparent">
       <v-spacer></v-spacer>
      <span class="white--text">&copy; {{ new Date().getFullYear() }} ELVURE SHOP</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      clipped: false,
      title: 'ELVURE SHOP',
    }
  },
  mounted() {
    this.$store.dispatch('initializeCart');
  },
  methods: {
    async handleLogout() {
      await this.$store.dispatch('logout');
    }
  }
}
</script>

<style>
/* Style ไม่มีการเปลี่ยนแปลง */
</style>