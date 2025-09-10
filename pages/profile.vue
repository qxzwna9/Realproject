<template>
  <div class="profile-bg">
    <v-container class="py-16">
      <v-row justify="center">
        <v-col cols="12" md="8" lg="7">
          <v-card class="card-glassmorphism">
            <v-card-title class="card-title">
              <v-icon left large color="primary">mdi-account-edit-outline</v-icon>
              Manage Your Profile
            </v-card-title>
            <v-card-subtitle class="card-subtitle">
              Keep your personal details up to date.
            </v-card-subtitle>

            <v-divider class="mx-6"></v-divider>

            <div v-if="loading" class="text-center pa-12">
              <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
              <p class="mt-4 white--text">Loading your profile...</p>
            </div>

            <v-card-text v-else class="pa-6 pa-md-8">
              <v-form @submit.prevent="updateProfile" ref="form">
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="user.name"
                      label="Full Name"
                      required
                      outlined
                      dark
                      prepend-inner-icon="mdi-account-outline"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="user.email"
                      label="Email Address"
                      type="email"
                      required
                      outlined
                      dark
                      prepend-inner-icon="mdi-email-outline"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="user.phone"
                      label="Phone Number"
                      outlined
                      dark
                      prepend-inner-icon="mdi-phone-outline"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-textarea
                      v-model="user.address"
                      label="Shipping Address"
                      rows="3"
                      outlined
                      dark
                      prepend-inner-icon="mdi-map-marker-outline"
                    ></v-textarea>
                  </v-col>
                </v-row>

                <v-alert v-if="message" :type="messageType" text class="mt-4">
                  {{ message }}
                </v-alert>

                <v-btn
                  color="primary"
                  dark
                  type="submit"
                  :loading="submitting"
                  x-large
                  block
                  class="mt-6 font-weight-bold"
                >
                  Save Changes
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
export default {
  middleware: 'auth',
  head() {
    return {
      title: 'My Profile'
    }
  },
  data() {
    return {
      user: {
        name: '',
        email: '',
        phone: '',
        address: ''
      },
      loading: true,
      submitting: false,
      message: '',
      messageType: 'success'
    }
  },
  async mounted() {
    await this.fetchUserData();
  },
  methods: {
    async fetchUserData() {
      this.loading = true;
      try {
        const response = await this.$axios.get('/user_get.php');
        if (response.data.status === 'success') {
          this.user = response.data.user;
        } else {
          this.$router.push('/Login');
        }
      } catch (error) {
        this.message = 'ไม่สามารถโหลดข้อมูลผู้ใช้ได้';
        this.messageType = 'error';
        if (error.response && error.response.status === 401) {
            this.$router.push('/Login');
        }
      } finally {
        this.loading = false;
      }
    },
    async updateProfile() {
      if (!this.$refs.form.validate()) return;
      this.submitting = true;
      this.message = '';
      try {
        const response = await this.$axios.post('/user_update.php', this.user);
        if (response.data.status === 'success') {
          this.message = response.data.message;
          this.messageType = 'success';
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        this.message = error.response ? error.response.data.message : 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
        this.messageType = 'error';
      } finally {
        this.submitting = false;
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@300;400&display=swap');

.profile-bg {
  min-height: 100vh;
  width: 100%;
  background: #0A0A0A;
  padding-top: 60px;
}

.card-glassmorphism {
  background: rgba(17, 17, 17, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212, 175, 55, 0.2);
  border-radius: 16px !important;
}

.card-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.2rem;
  font-weight: 700;
  color: #D4AF37 !important; /* Gold color */
  padding: 24px 24px 0;
}

.card-subtitle {
  font-family: 'Jost', sans-serif;
  color: #a0a0a0 !important;
  padding: 0 24px 16px;
}

:deep(.v-text-field--outlined .v-field__slot),
:deep(.v-text-field--outlined .v-input__control) {
  background: rgba(0, 0, 0, 0.2) !important;
}
</style>