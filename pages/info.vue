<template>
  <div class="info-bg">
    <v-container>
      <v-card class="mt-8 pa-4 card-glassmorphism">
        <v-card-title class="headline white--text">
          <v-icon left color="white">mdi-tshirt-crew</v-icon>
          รายการสินค้าทั้งหมด
        </v-card-title>

        <v-card-text>
          <div v-if="isLoading" class="text-center pa-8">
            <v-progress-circular indeterminate color="white" size="64"></v-progress-circular>
            <p class="mt-4 white--text">กำลังโหลดข้อมูลสินค้า...</p>
          </div>

          <div v-else-if="error" class="error-message">
            <v-alert type="error" dense>
              {{ error }}
            </v-alert>
          </div>

          <v-data-table
            v-else
            :headers="headers"
            :items="products"
            :items-per-page="5"
            class="elevation-1 transparent-table"
            dark
          >
            <template v-slot:item.image="{ item }">
              <v-img
                :src="'http://localhost:8080/ProjectReal/images/' + item.image"
                :alt="item.product_name"
                class="product-image my-2"
                height="70"
                width="70"
                contain
              ></v-img>
            </template>
            <template v-slot:item.price="{ item }">
              <span>{{ parseFloat(item.price).toFixed(2) }} ฿</span>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      isLoading: true,
      error: null,
      headers: [
        { text: 'รูปภาพ', value: 'image', sortable: false, width: '100px' },
        { text: 'ชื่อสินค้า', value: 'product_name' },
        { text: 'หมวดหมู่', value: 'category_name' },
        { text: 'ราคา', value: 'price' },
        { text: 'สต็อก', value: 'stock' },
      ],
    };
  },
  async mounted() {
    try {
      const apiUrl = 'http://localhost:8080/ProjectReal/db/shirt_select.php';
      const response = await fetch(apiUrl);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      this.products = await response.json();
    } catch (e) {
      this.error = 'ไม่สามารถดึงข้อมูลได้: ' + e.message;
      console.error("Fetch error:", e);
    } finally {
      this.isLoading = false;
    }
  },
};
</script>

<style scoped>
.info-bg {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
  padding-bottom: 40px;
}

.card-glassmorphism {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
}

.headline {
  font-weight: bold;
}

.transparent-table {
  background: transparent !important;
}

.transparent-table >>> thead, .transparent-table >>> tbody, .transparent-table >>> tfoot {
  background: transparent !important;
}

.product-image {
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.error-message {
  max-width: 600px;
  margin: 2rem auto;
}
</style>