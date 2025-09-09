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
    const item = state.cart.find(i => i.product_id === product.product_id);
    if (item) {
      item.quantity++;
    } else {
      state.cart.push({ ...product, quantity: 1 });
    }
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.cart));
    }
  },
  REMOVE_FROM_CART(state, productId) {
    state.cart = state.cart.filter(i => i.product_id !== productId);
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.cart));
    }
  },
  UPDATE_ITEM_QUANTITY(state, { productId, quantity }) {
    const item = state.cart.find(i => i.product_id === productId);
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
      // ใช้ $axios ที่เราตั้งค่าไว้ใน nuxt.config.js
      const response = await this.$axios.post('/register.php', form);
      if (response.data.status === 'success') {
        return response.data; // ส่ง response กลับไปให้ component
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
      // Redirect to login page after logout
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
  removeFromCart({ commit }, productId) {
    commit('REMOVE_FROM_CART', productId);
  },
  updateQuantity({ commit }, payload) {
    commit('UPDATE_ITEM_QUANTITY', payload);
  }
}