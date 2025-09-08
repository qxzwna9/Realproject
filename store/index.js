// store/index.js

export const state = () => ({
  isAuthenticated: false,
  user: null
})

export const mutations = {
  SET_USER(state, user) {
    state.isAuthenticated = true;
    state.user = user;
  },
  LOGOUT(state) {
    state.isAuthenticated = false;
    state.user = null;
  }
}

export const actions = {
  async login({ commit }, credentials) {
    try {
      const response = await this.$axios.post('/login.php', credentials);
      if (response.data.status === 'success' && response.data.user) {
        commit('SET_USER', response.data.user);
        return response.data.user; // <-- เพิ่มบรรทัดนี้เพื่อคืนค่า user object
      } else {
        throw new Error(response.data.message || 'An unknown error occurred');
      }
    } catch (error) {
      // ส่ง error ต่อเพื่อให้ component จัดการ
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
      this.$router.push('/Login');
    }
  }
}