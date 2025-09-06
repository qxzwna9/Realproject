<template>
  <v-container class="py-8">
    <v-card class="pa-6 mx-auto" max-width="600">
      <v-card-title class="headline">แก้ไขข้อมูลส่วนตัว</v-card-title>
      
      <div v-if="loading" class="text-center pa-4">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
        <p>กำลังโหลดข้อมูล...</p>
      </div>

      <v-card-text v-else>
        <v-alert v-if="message" :type="messageType" class="mb-4">{{ message }}</v-alert>
        
        <v-form @submit.prevent="updateProfile" ref="form">
          <v-text-field
            v-model="user.name"
            label="ชื่อ-นามสกุล"
            required
            outlined
            dense
          ></v-text-field>
          
          <v-text-field
            v-model="user.email"
            label="อีเมล"
            type="email"
            required
            outlined
            dense
          ></v-text-field>

          <v-text-field
            v-model="user.phone"
            label="เบอร์โทรศัพท์"
            outlined
            dense
          ></v-text-field>

          <v-textarea
            v-model="user.address"
            label="ที่อยู่"
            rows="3"
            outlined
            dense
          ></v-textarea>

          <v-btn color="primary" type="submit" :loading="submitting" block>
            บันทึกการเปลี่ยนแปลง
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
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
      messageType: 'success' // or 'error'
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
          // ถ้ายังไม่ได้ login ให้ redirect ไปหน้า register/login
          this.$router.push('/register');
        }
      } catch (error) {
        this.message = 'ไม่สามารถโหลดข้อมูลผู้ใช้ได้';
        this.messageType = 'error';
        // อาจจะ redirect ถ้าไม่ได้รับอนุญาต (401 Unauthorized)
        if (error.response && error.response.status === 401) {
            this.$router.push('/register');
        }
      } finally {
        this.loading = false;
      }
    },
    async updateProfile() {
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
.v-card {
  border-radius: 12px;
}
</style>