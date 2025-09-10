<template>
  <v-app dark>
    <v-app-bar
      app
      :clipped-left="clipped"
      color="rgba(17, 24, 39, 0.8)"
      flat
      dark
      height="80px"  
      class="navbar-style"
    >
      <v-toolbar-title class="brand-title" v-text="title" />
      <v-spacer />

      <div v-if="$store.state.user && $store.state.user.user_type === 'admin'">
        <v-btn text to="/admin/dashboard" class="nav-btn-large">Dashboard</v-btn>
        <v-btn text to="/admin/users" class="nav-btn-large">Manage Users</v-btn>
        <v-btn text to="/admin/products" class="nav-btn-large">Manage Products</v-btn>
        <v-btn color="error" @click="handleLogout" class="logout-btn ml-4">
          <v-icon left>mdi-logout</v-icon>
          ออกจากระบบ
        </v-btn>
      </div>

      <div v-else-if="$store.state.isAuthenticated">
        <v-btn text to="/MemberIndex" class="nav-btn-large">หน้าหลัก</v-btn>
        <v-btn text to="/shirt" class="nav-btn-large">สินค้า</v-btn>
        <v-btn text to="/info" class="nav-btn-large">ข้อมูล</v-btn>
        <v-btn text to="/contact" class="nav-btn-large">ติดต่อ</v-btn>
        <v-btn text to="/about" class="nav-btn-large">เกี่ยวกับเรา</v-btn>
        
        <v-spacer />

        <v-btn icon to="/cart" class="ml-2" title="ตะกร้าสินค้า">
          <v-icon size="28">mdi-cart</v-icon>
        </v-btn>
        
        <v-btn text to="/profile" class="nav-btn-large ml-2">
          <v-icon left size="28">mdi-account-circle-outline</v-icon>
          {{ $store.state.user ? $store.state.user.name : 'Profile' }}
        </v-btn>

        <v-btn color="error" @click="handleLogout" class="logout-btn ml-3">
          <v-icon left>mdi-logout</v-icon>
          ออกจากระบบ
        </v-btn>
      </div>

      <div v-else>
        <v-btn text to="/" class="nav-btn-large">หน้าหลัก</v-btn>
        <v-btn text to="/shirt" class="nav-btn-large">สินค้า</v-btn>
        <v-btn text to="/contact" class="nav-btn-large">ติดต่อเรา</v-btn>
        <v-btn text to="/about" class="nav-btn-large">เกี่ยวกับเรา</v-btn>
        <v-btn outlined to="/Login" class="login-btn-large">เข้าสู่ระบบ</v-btn>
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

<style scoped>
.brand-title {
  font-size: 1.8rem; /* เพิ่มขนาดฟอนต์ของชื่อแบรนด์ */
}
.nav-btn-large {
  font-size: 1rem !important; /* เพิ่มขนาดฟอนต์ของปุ่มเมนู */
  text-transform: none;
}
.login-btn-large {
  font-size: 1rem !important;
  text-transform: none;
}
</style>