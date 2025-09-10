<template>
  <div class="success-bg">
    <v-container class="fill-height">
      <v-row justify="center" align="center">
        <v-col cols="12" md="6">
          <v-card class="text-center pa-8 card-glassmorphism">
            <v-icon size="80" color="primary">mdi-check-decagram</v-icon>
            <h1 class="mt-4 primary--text">สั่งซื้อสินค้าสำเร็จ!</h1>
            <p class="mt-2 white--text">
              ขอบคุณสำหรับการสั่งซื้อ<br>
              หมายเลขออเดอร์ของคุณคือ: <strong>#{{ $route.query.id }}</strong>
            </p>
            <p class="white--text">เราจะดำเนินการจัดส่งสินค้าให้คุณโดยเร็วที่สุด</p>
            <v-btn color="primary" to="/shirt" class="mt-6">กลับไปหน้าสินค้า</v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  head() {
    return {
      title: 'สั่งซื้อสำเร็จ'
    }
  },
}
</script>

<style scoped>
.success-bg {
  min-height: 100vh;
  width: 100%;
  background: #0A0A0A;
}
.card-glassmorphism {
  background: rgba(17, 17, 17, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212, 175, 55, 0.2);
  border-radius: 16px !important;
}
</style>