<template>
  <div class="register-bg">
    <div class="register-content">
      <h2>สมัครสมาชิก</h2>
      <div v-if="message" :class="['message', messageType]">{{ message }}</div>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>ชื่อผู้ใช้</label>
          <input v-model="form.name" required />
        </div>
        <div class="form-group">
          <label>อีเมล</label>
          <input v-model="form.email" type="email" required />
        </div>
        <div class="form-group">
          <label>รหัสผ่าน</label>
          <input v-model="form.password" type="password" required />
        </div>
        <button type="submit" :disabled="loading">
          {{ loading ? 'กำลังสมัคร...' : 'สมัครสมาชิก' }}
        </button>
      </form>
      <p class="toggle-link">
        มีบัญชีแล้ว? <nuxt-link to="/Login">เข้าสู่ระบบ</nuxt-link>
      </p>
    </div>
  </div>
</template>

<script>
// ใช้ Vue 2 Options API ที่ถูกต้องสำหรับ Nuxt 2
export default {
  data() {
    return {
      form: { name: '', email: '', password: '' },
      message: '',
      messageType: '',
      loading: false
    }
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.message = '';
      this.messageType = '';
      try {
        const response = await this.$axios.post('/register.php', this.form);
        if (response.data.status === 'success') {
          this.message = response.data.message + ' กำลังนำท่านไปหน้าเข้าสู่ระบบ...';
          this.messageType = 'success';
          setTimeout(() => {
            this.$router.push('/Login');
          }, 2000);
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        this.message = error.response ? error.response.data.message : error.message;
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
/* Styles from your original file, with message styles added */
.register-bg { min-height: 100vh; width: 100vw; background: linear-gradient(135deg, #F43F5E 0%, #F9A8D4 100%); display: flex; align-items: center; justify-content: center; }
.register-content { width: 100%; max-width: 400px; padding: 32px 24px 24px 24px; box-sizing: border-box; background: rgba(255,255,255,0.92); border-radius: 18px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10); }
h2 { text-align: center; margin-bottom: 24px; color: #F43F5E; font-size: 2rem; font-weight: bold; }
label { display: block; margin-top: 12px; color: #333; font-weight: bold; }
input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border-radius: 4px; border: 1px solid #F43F5E; background: #fff; }
button { margin-top: 20px; width: 100%; padding: 10px; background: #F43F5E; color: #fff; border: none; border-radius: 4px; font-weight: bold; font-size: 1.1rem; cursor: pointer; }
a { color: #F43F5E; cursor: pointer; font-weight: bold; }
.toggle-link { text-align: center; margin-top: 1rem; }
.form-group { margin-bottom: 1rem; }
.message { padding: 10px; margin-bottom: 1rem; border-radius: 4px; text-align: center; font-weight: bold; }
.success { background-color: #d4edda; color: #155724; }
.error { background-color: #f8d7da; color: #721c24; }
</style>