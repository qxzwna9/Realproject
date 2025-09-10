<template>
  <v-app dark>
    <v-app-bar
      app
      color="rgba(17, 17, 17, 0.8)"
      flat
      dark
      height="60px"
    >
      <v-toolbar-title class="brand-title">ELVURE SHOP</v-toolbar-title>
      <v-spacer />
      <v-btn text to="/" class="nav-btn">หน้าหลัก</v-btn>
      <v-btn text to="/shirt" class="nav-btn">สินค้า</v-btn>
      <v-btn text to="/contact" class="nav-btn">ติดต่อเรา</v-btn>
      <v-btn text to="/about" class="nav-btn">เกี่ยวกับเรา</v-btn>
      <v-btn outlined color="primary" to="/Login">เข้าสู่ระบบ</v-btn>
    </v-app-bar>
    <v-main>
      <nuxt />
    </v-main>
  </v-app>
</template>

<script>
export default {}
</script>

<style scoped>
.brand-title {
  font-family: 'Playfair Display', serif;
  font-weight: 700;
  letter-spacing: 2px;
  color: #D4AF37; /* Gold color */
}
.nav-btn {
  color: white;
}
</style>