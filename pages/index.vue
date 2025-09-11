<template>
  <div class="welcome-bg-new">
    <div class="welcome-title-inside">
      ELVURE SHOP
      <div class="welcome-subtitle-inside">
        ไม่เชื่อคับโม้
      </div>
    </div>
    <v-img
    />
    <v-row class="justify-center mb-8 mt-n12">
      <v-col cols="12" sm="4">
        <v-img
          src="s2.png"
          height="500"
          class="rounded-lg mb-2 product-img-large"
          contain
        />
      </v-col>
      <v-col cols="12" sm="4">
        <v-img
          src="s1.png"
          height="500"
          class="rounded-lg mb-2 product-img-large"
          contain
        />
      </v-col>
      <v-col cols="12" sm="4">
        <v-img
          src="s3.png"
          height="500"
          class="rounded-lg mb-2 product-img-large"
          contain
        />
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {}
</script>

<style scoped>
.welcome-bg-new {
  background: #0A0A0A;
  min-height: 100vh;
  width: 100vw;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 0;
  padding: 32px 0 0 0;
}
.welcome-title-inside {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  font-weight: bold;
  color: #D4AF37; /* Gold color */
  margin-bottom: 130px;
  margin-top: 40px;
  text-align: center;
  letter-spacing: 2px;
}
.welcome-subtitle-inside {
  font-size: 1.5rem;
  color: #F9FAFB;
  margin-top: 10px;
  margin-bottom: 10;
  font-weight: 500;
}
.product-img-large {
  max-width: 80%;
  object-fit: contain;
  border: 3px solid #D4AF37; /* Gold color */
  background: #111111;
  box-shadow: 0 4px 14px 0 rgba(212, 175, 55, 0.1);
  margin-left: auto;
  margin-right: auto;
}
.mb-6 {
  margin-bottom: 10px !important;
}
</style>