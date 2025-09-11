// store/index.js

export const state = () => ({
  isAuthenticated: false,
  user: null,
  cart: []
})

export const mutations = {
  SET_USER(state, user) {
    state.isAuthenticated = true;
    state.user = user;
  },
  LOGOUT(state) {
    state.isAuthenticated = false;
    state.user = null;
  },
  INITIALIZE_CART(state, cart) {
    state.cart = cart;
  },
  ADD_TO_CART(state, product) {
    // ตรวจสอบว่ามีสินค้าชิ้นเดียวกัน และไซซ์เดียวกันอยู่ในตะกร้าหรือไม่
    const item = state.cart.find(i => i.product_id === product.product_id && i.size === product.size);
    if (item) {
      // ถ้ามี ให้อัปเดตจำนวน
      item.quantity += product.quantity;
    } else {
      // ถ้าไม่มี ให้เพิ่มสินค้าใหม่ลงตะกร้า พร้อมสร้าง ID เฉพาะสำหรับรายการในตะกร้า
      state.cart.push({
        ...product,
        cart_item_id: `${product.product_id}_${product.size}` // ID เฉพาะสำหรับสินค้า + ไซซ์
      });
    }
    // บันทึกตะกร้าลง Local Storage
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.cart));
    }
  },
  REMOVE_FROM_CART(state, cartItemId) {
    // ลบสินค้าด้วย cart_item_id ที่ไม่ซ้ำกัน
    state.cart = state.cart.filter(i => i.cart_item_id !== cartItemId);
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.cart));
    }
  },
  UPDATE_ITEM_QUANTITY(state, { cartItemId, quantity }) {
    // อัปเดตจำนวนด้วย cart_item_id
    const item = state.cart.find(i => i.cart_item_id === cartItemId);
    if (item) {
      item.quantity = quantity;
    }
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.cart));
    }
  },
  CLEAR_CART(state) {
    state.cart = [];
    if (process.client) {
        localStorage.removeItem('cart');
    }
  }
}

export const actions = {
  async register({ commit }, form) {
    try {
      const response = await this.$axios.post('/register.php', form);
      if (response.data.status === 'success') {
        return response.data;
      } else {
        throw new Error(response.data.message || 'An unknown error occurred');
      }
    } catch (error) {
      throw error;
    }
  },
  async login({ commit }, credentials) {
    try {
      const response = await this.$axios.post('/login.php', credentials);
      if (response.data.status === 'success' && response.data.user) {
        commit('SET_USER', response.data.user);
        return response.data.user;
      } else {
        throw new Error(response.data.message || 'An unknown error occurred');
      }
    } catch (error) {
      throw error;
    }
  },

  async checkAuth({ commit }) {
    try {
      const response = await this.$axios.get('/check_session.php');
      if (response.data.loggedin) {
        commit('SET_USER', response.data.user);
      } else {
        commit('LOGOUT');
      }
    } catch (error) {
      commit('LOGOUT');
    }
  },

  async logout({ commit }) {
    try {
      await this.$axios.post('/logout.php');
    } catch (error) {
      console.error("Logout API call failed, but logging out client-side anyway.", error);
    } finally {
      commit('LOGOUT');
      commit('CLEAR_CART');
      if (this.$router.currentRoute.path !== '/Login') {
        this.$router.push('/Login');
      }
    }
  },

  initializeCart({ commit }) {
    if (process.client) {
      const cart = localStorage.getItem('cart');
      if (cart) {
        commit('INITIALIZE_CART', JSON.parse(cart));
      }
    }
  },
  addToCart({ commit }, product) {
    commit('ADD_TO_CART', product);
  },
  removeFromCart({ commit }, cartItemId) {
    commit('REMOVE_FROM_CART', cartItemId);
  },
  updateQuantity({ commit }, payload) {
    commit('UPDATE_ITEM_QUANTITY', payload);
  }
}