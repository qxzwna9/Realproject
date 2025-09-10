<template>
  <div class="checkout-bg">
    <v-container class="py-8 pt-16">
      <h1 class="page-title text-center mb-8">ยืนยันการสั่งซื้อและชำระเงิน</h1>
      
      <v-row justify="center">
        <v-col cols="12" md="5">
          <v-card class="card-glassmorphism">
            <v-card-title>สรุปรายการสั่งซื้อ</v-card-title>
            <v-list color="transparent" dark>
              <v-list-item v-for="item in cart" :key="item.product_id">
                <v-list-item-avatar tile size="60" class="mr-3">
                  <v-img :src="'http://localhost:8080/ProjectReal/images/' + item.image"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ item.product_name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ item.quantity }} x {{ Number(item.price).toFixed(2) }} ฿</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                   <span class="font-weight-bold">{{ (item.quantity * Number(item.price)).toFixed(2) }} ฿</span>
                </v-list-item-action>
              </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-card-text class="d-flex justify-space-between headline">
              <span>ยอดรวม:</span>
              <span class="font-weight-bold primary--text">{{ cartTotal.toFixed(2) }} ฿</span>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="7">
          <v-card class="card-glassmorphism pa-4">
            <v-card-title>ข้อมูลสำหรับจัดส่ง</v-card-title>
            <v-card-text>
              <v-alert v-if="error" type="error" text>{{ error }}</v-alert>
              <v-form @submit.prevent="handleCheckout">
                <v-text-field v-model="shipping.name" label="ชื่อ-นามสกุลผู้รับ" outlined dark required></v-text-field>
                <v-textarea v-model="shipping.address" label="ที่อยู่สำหรับจัดส่ง" outlined dark required rows="3"></v-textarea>
                <v-text-field v-model="shipping.phone" label="เบอร์โทรศัพท์" outlined dark required></v-text-field>
                <v-btn type="submit" x-large color="primary" :loading="loading" block>
                  <v-icon left>mdi-credit-card-check-outline</v-icon>
                  ยืนยันและชำระเงิน
                </v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  middleware: 'auth',
  head() {
    return { title: 'ชำระเงิน' }
  },
  data() {
    return {
      shipping: {
        name: '',
        address: '',
        phone: ''
      },
      loading: false,
      error: ''
    }
  },
  computed: {
    ...mapState(['cart', 'user']),
    cartTotal() {
      return this.cart.reduce((total, item) => total + (Number(item.price) * item.quantity), 0);
    }
  },
  mounted() {
    if (this.cart.length === 0) {
      this.$router.push('/shirt');
      return;
    }
    this.fetchFullUserData();
  },
  methods: {
    async fetchFullUserData() {
      try {
        const { data } = await this.$axios.get('/user_get.php');
        if (data.status === 'success' && data.user) {
          this.shipping.name = data.user.name || '';
          this.shipping.address = data.user.address || '';
          this.shipping.phone = data.user.phone || '';
        }
      } catch (e) {
        console.error('Could not fetch full user data', e);
        this.error = 'ไม่สามารถดึงข้อมูลผู้ใช้ได้';
      }
    },
    async handleCheckout() {
      this.loading = true;
      this.error = '';
      try {
        const payload = {
          shipping_details: this.shipping,
          cart: this.cart
        };
        const { data } = await this.$axios.post('/order_create.php', payload);
        if (data.status === 'success') {
          this.$store.commit('CLEAR_CART');
          this.$router.push(`/order-success?id=${data.order_id}`);
        } else {
          throw new Error(data.message);
        }
      } catch (err) {
        this.error = err.response ? err.response.data.message : 'เกิดข้อผิดพลาดร้ายแรง';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
.checkout-bg {
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