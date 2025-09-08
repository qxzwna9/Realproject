<template>
  <div class="shirt-page-bg">
    <v-container class="py-8 pt-16">
      <h1 class="page-title text-center mb-8">สินค้าของเรา</h1>

      <v-row v-if="loading">
        <v-col v-for="n in 6" :key="n" cols="12" sm="6" md="4">
          <v-skeleton-loader type="card, article"></v-skeleton-loader>
        </v-col>
      </v-row>

      <v-alert v-else-if="error" type="error" prominent class="mx-auto" max-width="800">
        {{ error }}
      </v-alert>

      <v-row v-else>
        <v-col
          v-for="shirt in shirts"
          :key="shirt.product_id"
          cols="12"
          sm="6"
          md="4"
        >
          <v-card class="product-card" hover>
            <v-img
              :src="'http://localhost:8080/ProjectReal/images/' + shirt.image"
              height="300px"
              class="white--text align-end"
              gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.7)"
            >
              <v-card-title class="product-title">{{ shirt.product_name }}</v-card-title>
            </v-img>

            <v-card-subtitle class="pb-2 pt-4">
              <v-chip small color="secondary" dark>{{ shirt.category_name }}</v-chip>
            </v-card-subtitle>

            <v-card-text class="text--primary pb-4">
              <div>{{ shirt.description || 'ไม่มีคำอธิบาย' }}</div>
              <div class="price mt-3">{{ parseFloat(shirt.price).toFixed(2) }} ฿</div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      shirts: [],
      loading: true,
      error: null,
    };
  },
  async mounted() {
    this.fetchShirts();
  },
  methods: {
    async fetchShirts() {
      this.loading = true;
      this.error = null;
      try {
        const apiUrl = 'http://localhost:8080/ProjectReal/db/shirt_select.php';
        const response = await fetch(apiUrl);
        if (!response.ok) {
          throw new Error('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
        }
        const data = await response.json();
        if (Array.isArray(data)) {
          this.shirts = data;
        } else {
          throw new Error(data.message || 'รูปแบบข้อมูลไม่ถูกต้อง');
        }
      } catch (err) {
        this.error = 'เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า: ' + err.message;
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.shirt-page-bg {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(135deg, #111827 0%, #111827 100%);
}

.page-title {
  color: #FFFFFF;
  font-weight: 700;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.product-card {
  transition: all 0.3s ease-in-out;
  border-radius: 12px !important;
  overflow: hidden;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
}

.product-title {
  word-break: break-word;
  line-height: 1.2;
}

.price {
  font-size: 1.4rem;
  font-weight: bold;
  color: var(--v-success-base, #4CAF50);
}
</style>