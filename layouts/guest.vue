<template>
  <v-app dark>
    <v-app-bar
      app
      color="rgba(17, 24, 39, 0.8)"
      flat
      dark
      height="60px"
    >
      <v-toolbar-title>ELVURE SHOP</v-toolbar-title>
      <v-spacer />
      <v-btn text to="/">หน้าหลัก</v-btn>
      <v-btn text to="/shirt">สินค้า</v-btn>
      <v-btn text to="/contact">ติดต่อเรา</v-btn>
      <v-btn text to="/about">เกี่ยวกับเรา</v-btn>
      <v-btn outlined to="/Login">เข้าสู่ระบบ</v-btn>
    </v-app-bar>
    <v-main>
      <nuxt />
    </v-main>
  </v-app>
</template>

<script>
export default {}
</script>