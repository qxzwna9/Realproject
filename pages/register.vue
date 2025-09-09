<template>
  <div class="register-container">
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
            <h2>Create Account</h2>
          </v-card-title>
          <v-card-subtitle>Join us and discover a new world of style.</v-card-subtitle>
          
          <v-card-text class="mt-4">
            <v-form @submit.prevent="handleSubmit">
              <v-text-field
                v-model="form.name"
                label="Full Name"
                name="name"
                type="text"
                required
                outlined
                class="mb-4"
                placeholder="Your Name"
              ></v-text-field>

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
                class="mb-4"
                placeholder="••••••••••••"
              ></v-text-field>

              <v-text-field
                v-model="form.confirm_password"
                label="Confirm Password"
                name="confirm_password"
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
                  class="register-button black--text"
                >
                  Create Account
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card-text>

           <v-card-text class="text-center footer-text">
            Already have an account? <nuxt-link to="/Login" class="login-link">Sign In</nuxt-link>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  // No layout specified to use the custom full-page design
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        confirm_password: ''
      },
      message: '',
      messageType: 'error',
      loading: false
    }
  },
  methods: {
    async handleSubmit() {
      if (this.form.password !== this.form.confirm_password) {
        this.message = 'รหัสผ่านไม่ตรงกัน';
        this.messageType = 'error';
        return;
      }

      this.loading = true;
      this.message = '';
      try {
        const response = await this.$store.dispatch('register', this.form);
        this.message = response.message;
        this.messageType = 'success';
        setTimeout(() => {
          this.$router.push('/Login');
        }, 2000); // Redirect to login after 2 seconds
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
/* Import Fonts for luxury feel */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@300;400&display=swap');

.register-container {
  height: 100vh;
  width: 100vw;
  background: #111827; /* <-- เปลี่ยนสีตรงนี้ */
  font-family: 'Jost', sans-serif;
  overflow: hidden; /* Prevent scrollbars */
}

.branding-section {
  background-image: 
    url('https://images.unsplash.com/photo-1627876116342-a50d1887019d?auto=format&fit=crop&w=700&q=80'), /* Hoodies */
    url('https://images.unsplash.com/photo-1579758782065-276634d58dfc?auto=format&fit=crop&w=700&q=80'), /* T-Shirt folded */
    url('https://images.unsplash.com/photo-1616641885871-007a514d02b8?auto=format&fit=crop&w=700&q=80'); /* Polo shirt texture */
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

.register-button {
  font-weight: bold;
  letter-spacing: 1px;
  border-radius: 4px;
}

.footer-text {
  color: #a0a0a0;
}

.login-link {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.login-link:hover {
  text-decoration: underline;
}
</style>