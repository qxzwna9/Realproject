<template>
  <div class="info-bg">
    <v-container class="py-16">
      <h1 class="page-title text-center mb-4">Product Information</h1>
      <p class="page-subtitle text-center mb-10">
        Detailed specifications of our exclusive collection.
      </p>

      <v-card class="card-glassmorphism mx-auto" max-width="950">
        <v-card-text class="pa-0">
          <div v-if="loading" class="text-center pa-12">
            <v-progress-circular indeterminate color="white" size="64"></v-progress-circular>
            <p class="mt-4 white--text">Loading product data...</p>
          </div>

          <v-alert v-else-if="error" type="error" prominent tile class="ma-0">
            {{ error }}
          </v-alert>

          <v-list v-else color="transparent" dark two-line>
            <template v-for="(item, index) in products">
              <v-list-item :key="item.product_id" class="product-list-item">
                <v-list-item-avatar tile size="80" class="mr-6 rounded">
                  <v-img :src="'http://localhost:8080/ProjectReal/images/' + item.image"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                  <v-list-item-title class="list-item-title">{{ item.product_name }}</v-list-item-title>
                  <v-list-item-subtitle class="grey--text text--lighten-1">
                    Category: {{ item.category_name || 'N/A' }}
                  </v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action class="mr-4 text-center" style="width: 100px;">
                  <v-list-item-action-text class="grey--text">Price</v-list-item-action-text>
                  <div class="list-item-price">{{ parseFloat(item.price).toFixed(2) }} ฿</div>
                </v-list-item-action>

                <v-list-item-action class="text-center" style="width: 100px;">
                  <v-list-item-action-text class="grey--text">Stock</v-list-item-action-text>
                  <div class="font-weight-bold">{{ item.stock }}</div>
                </v-list-item-action>
              </v-list-item>
              <v-divider v-if="index < products.length - 1" :key="'divider-' + index" class="mx-4"></v-divider>
            </template>
          </v-list>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  head() {
    return {
      title: 'Product Information'
    }
  },
  async asyncData({ $axios, error }) {
    try {
      // ใช้ $axios ที่มาจาก nuxt.config.js โดยตรง
      const response = await $axios.get('http://localhost:8080/ProjectReal/db/shirt_select.php');
      if (Array.isArray(response.data)) {
        return { products: response.data, loading: false, error: null };
      } else {
        throw new Error('Invalid data format received from server');
      }
    } catch (e) {
      console.error("Fetch error:", e);
      error({ statusCode: 500, message: 'ไม่สามารถดึงข้อมูลได้: ' + e.message });
      return { products: [], loading: false, error: 'ไม่สามารถดึงข้อมูลได้: ' + e.message };
    }
  },
  data() {
    return {
      products: [],
      loading: true,
      error: null
    };
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@300;400;700&display=swap');

.info-bg {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
  padding-top: 60px;
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.8rem;
  color: #FFFFFF;
  font-weight: 700;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.page-subtitle {
  font-family: 'Jost', sans-serif;
  color: #a0a0a0;
}

.card-glassmorphism {
  background: rgba(255, 255, 255, 0.1) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px !important;
  overflow: hidden;
}

.product-list-item {
  padding: 20px 24px;
}

.list-item-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #FFFFFF;
}

.list-item-price {
  font-size: 1.1rem;
  font-weight: bold;
  color: var(--v-success-base, #4CAF50);
}

</style>