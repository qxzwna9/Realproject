<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>เข้าสู่ระบบ</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form @submit.prevent="handleSubmit">
              <v-text-field
                v-model="form.email"
                label="อีเมล"
                name="email"
                prepend-icon="mdi-account"
                type="email"
                required
              ></v-text-field>
              <v-text-field
                v-model="form.password"
                label="รหัสผ่าน"
                name="password"
                prepend-icon="mdi-lock"
                type="password"
                required
              ></v-text-field>
              <v-alert v-if="message" :type="messageType" text class="mb-4">
                {{ message }}
              </v-alert>
              <v-card-actions>
                <v-spacer />
                <v-btn color="primary" type="submit" :loading="loading">
                  เข้าสู่ระบบ
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card-text>
           <v-card-text class="text-center">
            ยังไม่มีบัญชี? <nuxt-link to="/register">สมัครสมาชิก</nuxt-link>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  layout: 'guest', // <-- บอกให้หน้านี้ใช้ guest layout
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
        // --- ส่วนที่แก้ไข ---
        const user = await this.$store.dispatch('login', this.form);
        this.message = 'เข้าสู่ระบบสำเร็จ!';
        this.messageType = 'success';
        const destination = user.user_type === 'admin' ? '/AdminIndex' : '/MemberIndex';
        this.$router.push(destination);
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
.fill-height {
  min-height: 90vh;
}
</style>