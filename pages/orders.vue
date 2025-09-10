<template>
  <div class="orders-bg">
    <v-container class="py-8 pt-16">
      <h1 class="page-title text-center mb-8">ประวัติคำสั่งซื้อ</h1>

      <v-card class="mx-auto card-glassmorphism" max-width="900">
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="orders"
            :loading="loading"
            loading-text="กำลังโหลดข้อมูล..."
            class="elevation-0 transparent"
            dark
          >
            <template v-slot:item.created_at="{ item }">
              {{ new Date(item.created_at).toLocaleString() }}
            </template>
            <template v-slot:item.total="{ item }">
              {{ parseFloat(item.total).toFixed(2) }} ฿
            </template>
            <template v-slot:item.status="{ item }">
              <v-chip :color="getStatusColor(item.status)" dark small>
                {{ translateStatus(item.status) }}
              </v-chip>
            </template>
             <template v-slot:no-data>
                <div class="text-center py-8">
                    <p class="grey--text">คุณยังไม่มีคำสั่งซื้อ</p>
                    <v-btn color="primary" to="/shirt">เลือกซื้อสินค้า</v-btn>
                </div>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  middleware: 'auth',
  head() {
    return {
      title: 'ประวัติคำสั่งซื้อ'
    }
  },
  data() {
    return {
      loading: true,
      orders: [],
      headers: [
        { text: 'หมายเลขออเดอร์', value: 'order_id', sortable: false },
        { text: 'วันที่', value: 'created_at' },
        { text: 'ยอดรวม', value: 'total' },
        { text: 'สถานะ', value: 'status' },
      ],
    };
  },
  async mounted() {
    await this.fetchOrders();
  },
  methods: {
    async fetchOrders() {
      this.loading = true;
      try {
        const { data } = await this.$axios.get('/get_user_orders.php');
        if (data.status === 'success') {
          this.orders = data.orders;
        } else {
          throw new Error(data.message);
        }
      } catch (error) {
        console.error('Could not fetch orders', error);
        alert('เกิดข้อผิดพลาดในการโหลดข้อมูลคำสั่งซื้อ');
      } finally {
        this.loading = false;
      }
    },
    getStatusColor(status) {
      switch (status) {
        case 'completed': return 'success';
        case 'shipped': return 'info';
        case 'processing': return 'primary';
        case 'cancelled': return 'error';
        default: return 'grey';
      }
    },
    translateStatus(status) {
      const translations = {
        pending: 'รอดำเนินการ',
        processing: 'กำลังเตรียมจัดส่ง',
        shipped: 'จัดส่งแล้ว',
        completed: 'จัดส่งสำเร็จ',
        cancelled: 'ยกเลิกแล้ว'
      };
      return translations[status] || status;
    }
  },
};
</script>

<style scoped>
.orders-bg {
  min-height: 100vh;
  width: 100%;
  background: #0A0A0A;
}
.page-title {
  color: #D4AF37; /* Gold color */
  font-family: 'Playfair Display', serif;
  font-weight: 700;
}
.card-glassmorphism {
  background: rgba(17, 17, 17, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212, 175, 55, 0.2);
  border-radius: 16px !important;
}
</style>