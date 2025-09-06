<template>
  <v-app dark>
    <v-app-bar :clipped-left="clipped" fixed app color="transparent" flat dark height="60px" style="background:rgba(255,255,255,0); box-shadow: none; border: none;">
      <v-toolbar-title v-text="title" />
      <v-spacer />
      <v-btn text to="/">หน้าหลัก</v-btn>
      <v-btn text to="/info">ข้อมูล</v-btn>
      <v-btn text to="/contact">ติดต่อ</v-btn>
      <v-btn text to="/shirt">สินค้า</v-btn>
      
      <v-spacer />

      <v-btn color="error" @click="handleLogout">
        <v-icon left>mdi-logout</v-icon>
        ออกจากระบบ
      </v-btn>

    </v-app-bar>
    
    <v-main>
      <v-container fluid style="padding: 0; margin: 0; max-width: 100vw; background: transparent;">
        <nuxt />
      </v-container>
    </v-main>

    <v-footer>
      <span>&copy; {{ new Date().getFullYear() }}</span>
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
  methods: {
    async handleLogout() {
      try {
        // เรียก API logout
        await this.$axios.post('/logout.php');
        
        // หลังจาก logout สำเร็จ ให้ redirect ไปหน้า Login
        // แนะนำให้ใช้ window.location เพื่อให้แน่ใจว่า state ทั้งหมดถูกล้าง
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