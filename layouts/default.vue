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

      <div v-if="$store.state.isAuthenticated">
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
      // ไม่จำเป็นต้องใช้ user: null ในนี้แล้ว เพราะเราจะใช้ข้อมูลจาก store โดยตรง
    }
  },
  mounted() {
    // ปลั๊กอิน auth-init.js จะจัดการเรื่องนี้แทน เราจึงไม่จำเป็นต้องเรียก checkUser() ที่นี่แล้ว
    this.$store.dispatch('initializeCart');
  },
  methods: {
    // ลบ method checkUser() เดิมออกไป และเปลี่ยน handleLogout ให้เรียก action จาก store
    async handleLogout() {
      await this.$store.dispatch('logout');
    }
  }
}
</script>

<style>
/* ... Styles เดิมของคุณ ... */
.v-app-bar .v-btn {
  font-size: 1rem !important;
  padding: 0 32px !important;
  align-items: center;
  justify-content: center;
}

.navbar-style {
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.brand-title {
  font-weight: bold;
  letter-spacing: 1px;
}

.nav-btn {
  font-size: 1rem !important;
  padding: 0 24px !important;
  transition: all 0.2s ease-in-out;
}

.nav-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.login-btn, .logout-btn {
  font-weight: bold !important;
}
</style>