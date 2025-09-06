// store/index.js

// ... (ส่วน state, mutations, getters เหมือนเดิม) ...

export const actions = {
  // ... (action 'login' และ 'checkAuth' เหมือนเดิม) ...

  async logout({ commit }) {
    try {
      // เรียก API logout.php ที่เราเพิ่งแก้ไข
      await this.$axios.post('/logout.php');
    } catch (error) {
      // แม้ว่า API จะ error (เช่น network ขาด) เราก็ควรจะ log user ออกจากฝั่ง frontend อยู่ดี
      console.error("Logout API call failed, but logging out client-side anyway.", error);
    } finally {
      // ทำส่วนนี้เสมอ ไม่ว่า API จะสำเร็จหรือล้มเหลว
      commit('LOGOUT'); // ล้างข้อมูล user ใน store
      this.$router.push('/Login'); // ส่งผู้ใช้ไปหน้า Login
    }
  }
}