<template>
  <v-app dark>
    <v-app-bar 
      :clipped-left="clipped" 
      absolute
      color="transparent" 
      flat 
      dark 
      height="60px"
    >
      <v-toolbar-title v-text="title" />
      <v-spacer />

      <div v-if="user">
        <v-btn text to="/">หน้าหลัก</v-btn>
        <v-btn text to="/info">ข้อมูล</v-btn>
        <v-btn text to="/contact">ติดต่อ</v-btn>
        <v-btn text to="/shirt">สินค้า</v-btn>
        <v-btn color="error" @click="handleLogout">
          <v-icon left>mdi-logout</v-icon>
          ออกจากระบบ
        </v-btn>
      </div>

      <div v-else>
        <v-btn text to="/">หน้าหลัก</v-btn>
        <v-btn text to="/shirt">สินค้า</v-btn>
        <v-btn text to="/contact">ติดต่อเรา</v-btn>
        <v-btn outlined to="/Login">เข้าสู่ระบบ</v-btn>
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
      user: null
    }
  },
  mounted() {
    this.checkUser();
  },
  methods: {
    async checkUser() {
      try {
        const response = await this.$axios.get('/check_session.php');
        if (response.data.loggedin) {
          this.user = response.data.user;
        }
      } catch (error) {
        // console.error('Error checking user session:', error);
      }
    },
    async handleLogout() {
      try {
        await this.$axios.post('/logout.php');
        window.location.href = '/Login';
      } catch (error) {
        console.error('Logout failed:', error);
        alert('เกิดข้อผิดพลาดในการออกจากระบบ');
      }
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
</style>