<template>
  <div class="cart-bg">
    <v-container class="py-8 pt-16">
      <h1 class="page-title text-center mb-8">ตะกร้าสินค้าของคุณ</h1>

      <v-card class="mx-auto card-glassmorphism" max-width="900">
        <v-card-text>
          <div v-if="!cartItems || cartItems.length === 0" class="text-center py-16">
            <v-icon size="64" color="grey lighten-1">mdi-cart-off</v-icon>
            <p class="mt-4 headline grey--text text--lighten-1">ตะกร้าของคุณว่างเปล่า</p>
            <v-btn color="primary" to="/shirt" class="mt-4">เลือกซื้อสินค้า</v-btn>
          </div>

          <div v-else>
            <v-list color="transparent" dark>
              <template v-for="(item, index) in cartItems">
                <v-list-item :key="item.cart_item_id">
                  <v-list-item-avatar tile size="80" class="mr-4">
                    <v-img :src="'http://localhost:8080/ProjectReal/images/' + item.image"></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title class="font-weight-bold white--text">{{ item.product_name }}</v-list-item-title>
                    <v-list-item-subtitle class="white--text">ไซซ์: {{ item.size }}</v-list-item-subtitle>
                    <v-list-item-subtitle class="white--text">{{ Number(item.price).toFixed(2) }} ฿</v-list-item-subtitle>
                  </v-list-item-content>

                  <v-list-item-action class="d-flex flex-row align-center" style="gap: 10px;">
                    <v-text-field
                      v-model.number="item.quantity"
                      type="number"
                      min="1"
                      @change="updateQty(item.cart_item_id, $event.target.value)"
                      style="width: 70px;"
                      dense
                      outlined
                      hide-details
                    ></v-text-field>
                    <span class="subtotal mx-4">= {{ (Number(item.price) * item.quantity).toFixed(2) }} ฿</span>
                    <v-btn icon color="red lighten-1" @click="removeItem(item.cart_item_id)">
                      <v-icon>mdi-delete</v-icon>
                    </v-btn>
                  </v-list-item-action>
                </v-list-item>
                <v-divider v-if="index < cartItems.length - 1" :key="'divider-' + item.cart_item_id"></v-divider>
              </template>
            </v-list>

            <v-divider class="my-4"></v-divider>

            <div class="d-flex justify-end align-center px-4">
              <h2 class="total-title mr-4">ยอดรวมทั้งหมด:</h2>
              <span class="total-price">{{ cartTotal.toFixed(2) }} ฿</span>
            </div>

            <div class="text-right mt-6 px-4">
              <v-btn x-large color="success" @click="checkout">
                <v-icon left>mdi-check-circle-outline</v-icon>
                ดำเนินการชำระเงิน
              </v-btn>
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  head() {
    return {
      title: 'ตะกร้าสินค้า'
    }
  },
  computed: {
    ...mapState(['cart']),
    cartItems() {
      // Use JSON.parse and stringify to create a deep copy for local manipulation
      return JSON.parse(JSON.stringify(this.cart));
    },
    cartTotal() {
      return this.cart.reduce((total, item) => total + (Number(item.price) * item.quantity), 0);
    }
  },
  methods: {
    removeItem(cartItemId) {
      if (confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?')) {
        this.$store.dispatch('removeFromCart', cartItemId);
      }
    },
    updateQty(cartItemId, newQuantity) {
      const quantity = parseInt(newQuantity, 10);
      if (quantity > 0) {
        this.$store.dispatch('updateQuantity', { cartItemId, quantity });
      } else {
        // Revert to original quantity if input is invalid
        const originalItem = this.cart.find(i => i.cart_item_id === cartItemId);
        const itemInComponent = this.cartItems.find(i => i.cart_item_id === cartItemId);
        if (itemInComponent && originalItem) {
            itemInComponent.quantity = originalItem.quantity;
        }
      }
    },
    checkout() {
      this.$router.push('/checkout');
    }
  }
}
</script>

<style scoped>
.cart-bg {
  min-height: 100vh;
  width: 100%;
  background: #0A0A0A;
}
.page-title {
  color: #D4AF37; /* Gold color */
  font-family: 'Playfair Display', serif;
  font-weight: 700;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}
.card-glassmorphism {
  background: rgba(17, 17, 17, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212, 175, 55, 0.2);
  border-radius: 16px !important;
  padding: 16px;
}
.v-list-item__action {
    min-width: 250px;
}
.subtotal {
    min-width: 100px;
    text-align: right;
    font-size: 1rem;
    color: #E5E7EB;
}
.total-title {
    color: #E5E7EB;
}
.total-price {
    font-size: 1.8rem;
    font-weight: bold;
    color: #D4AF37; /* Gold color */
}
</style>