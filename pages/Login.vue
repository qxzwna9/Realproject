<template>
  <div class="login-container">
    <v-row no-gutters class="fill-height">
      <v-col cols="12" md="7" class="branding-section d-none d-md-flex">
        <div class="branding-content">
          <h1 class="brand-name">ELVURE SHOP</h1>
          <p class="brand-tagline">Where Style Meets Substance.</p>
        </div>
      </v-col>

      <v-col cols="12" md="5" class="form-section">
        <v-card flat dark color="transparent" max-width="450" class="mx-auto">
          <v-card-title class="form-title">
            <h2>Welcome Back</h2>
          </v-card-title>
          <v-card-subtitle>Enter your credentials to access your account.</v-card-subtitle>

          <v-card-text class="mt-4">
            <v-form @submit.prevent="handleSubmit">
              <v-text-field
                v-model="form.email"
                label="Email Address"
                name="email"
                type="email"
                required
                outlined
                class="mb-4"
                placeholder="you@example.com"
              ></v-text-field>

              <v-text-field
                v-model="form.password"
                label="Password"
                name="password"
                type="password"
                required
                outlined
                placeholder="••••••••••••"
              ></v-text-field>

              <v-alert v-if="message" :type="messageType" text class="mt-4 mb-0">
                {{ message }}
              </v-alert>

              <v-card-actions class="pa-0 mt-6">
                <v-btn
                  color="white"
                  light
                  block
                  x-large
                  type="submit"
                  :loading="loading"
                  class="login-button black--text"
                >
                  Sign In
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card-text>

           <v-card-text class="text-center footer-text">
            Don't have an account? <nuxt-link to="/register" class="register-link">Sign Up</nuxt-link>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  layout: 'guest', // ใช้ layout ที่ไม่มีแถบนำทางหลัก
  data() {
    return {
      form: { email: '', password: '' },
      message: '',
      messageType: '',
      loading: false
    }
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.message = '';
      try {
        // เรียกใช้ login action จาก Vuex store
        const user = await this.$store.dispatch('login', this.form);

        this.message = 'Login successful! Redirecting...';
        this.messageType = 'success';

        // ตรวจสอบ user_type และ redirect ไปยังหน้าที่ถูกต้อง
        const destination = user.user_type === 'admin' ? '/admin/dashboard' : '/MemberIndex';

        // ใช้ setTimeout เพื่อให้ผู้ใช้เห็นข้อความ success ก่อน
        setTimeout(() => {
            this.$router.push(destination);
        }, 1000);

      } catch (error) {
        this.message = error.response ? error.response.data.message : 'Invalid credentials.';
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@300;400&display=swap');

.login-container {
  height: 100vh;
  width: 100vw;
  background: #111827;
  font-family: 'Jost', sans-serif;
  overflow: hidden;
}
.branding-section {
  background-image:
    url('https://images.unsplash.com/photo-1627876116342-a50d1887019d?auto=format&fit=crop&w=700&q=80'),
    url('https://images.unsplash.com/photo-1579758782065-276634d58dfc?auto=format&fit=crop&w=700&q=80'),
    url('https://images.unsplash.com/photo-1616641885871-007a514d02b8?auto=format&fit=crop&w=700&q=80');
  background-position:
    left top,
    center bottom,
    right top;
  background-size:
    45% auto,
    40% auto,
    45% auto;
  background-repeat: no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  position: relative;
}
.branding-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
}
.branding-content {
  position: relative;
  z-index: 1;
  color: white;
}
.brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 4rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}
.brand-tagline {
  font-size: 1.25rem;
  font-weight: 300;
  margin-top: 1rem;
  opacity: 0.8;
}
.form-section {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 2rem;
}
.form-title h2 {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: white;
}
.v-card__subtitle {
  color: #a0a0a0;
}
:deep(.v-text-field--outlined .v-label) {
  color: rgba(255, 255, 255, 0.7) !important;
}
:deep(.v-text-field--outlined fieldset) {
  border-color: rgba(255, 255, 255, 0.4);
}
:deep(.v-text-field--outlined:hover fieldset) {
  border-color: white;
}
.login-button {
  font-weight: bold;
  letter-spacing: 1px;
  border-radius: 4px;
}
.footer-text {
  color: #a0a0a0;
}
.register-link {
  color: white;
  text-decoration: none;
  font-weight: bold;
}
.register-link:hover {
  text-decoration: underline;
}
</style>