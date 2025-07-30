<template>
  <v-app dark>
    <v-app-bar
      :clipped-left="clipped"
      fixed
      app
      color="transparent"
      flat
      dark
      height="60px"
      style="background:rgba(255,255,255,0); box-shadow: none; border: none;"
    >
      <v-toolbar-title v-text="title" />
      <v-spacer />
      <v-btn text to="/">หน้าหลัก</v-btn>
      <v-btn text to="/info">ข้อมูล</v-btn>
      <v-btn text to="/contact">ติดต่อ</v-btn>
      <v-btn text to="/register">สมัครสมาชิก</v-btn>
    </v-app-bar>
    <v-content>
      <v-container
        fluid
        style="padding: 0; margin: 0; max-width: 100vw; background: transparent;"
      >
        <nuxt />
      </v-container>
    </v-content>

    <v-footer
    >
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      fixed: false,
      title: 'ELVURE SHOP',
      miniVariant: false,
      right: true,
      rightDrawer: false
    }
  }
}
</script>

<style>
.v-app-bar .v-btn {
  font-size: 1rem !important;
  padding: 0 32px !important;
  align-items: center;
  justify-content: center;
}
</style>
