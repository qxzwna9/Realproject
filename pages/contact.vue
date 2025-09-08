<template>
  <div class="contact-bg">
    <v-container class="py-16">
      <v-row justify="center">
        <v-col cols="12" md="6">
          <v-card class="card-glassmorphism pa-6 pa-md-8">
            <h1 class="page-title text-center mb-6">ติดต่อเรา</h1>
            <v-form @submit.prevent="submitForm" ref="form">
              <v-alert v-if="submitted" type="success" class="mb-6">
                ขอบคุณสำหรับการติดต่อ! เราจะตอบกลับโดยเร็วที่สุด
              </v-alert>

              <v-text-field
                v-model="form.name"
                label="ชื่อ"
                required
                outlined
                dark
                prepend-inner-icon="mdi-account"
                class="mb-4"
              ></v-text-field>

              <v-text-field
                v-model="form.email"
                label="อีเมล"
                type="email"
                required
                outlined
                dark
                prepend-inner-icon="mdi-email"
                class="mb-4"
              ></v-text-field>

              <v-textarea
                v-model="form.message"
                label="ข้อความ"
                required
                outlined
                dark
                rows="4"
                prepend-inner-icon="mdi-message-text"
                class="mb-4"
              ></v-textarea>

              <v-btn type="submit" color="primary" block large>
                ส่งข้อความ
              </v-btn>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                message: ''
            },
            submitted: false
        }
    },
    methods: {
        submitForm() {
            // ในส่วนนี้สามารถเพิ่ม logic ส่งข้อมูลไป backend ได้
            console.log('Form submitted:', this.form);
            this.submitted = true;
            this.$refs.form.reset(); // รีเซ็ตฟอร์ม

            // ซ่อนข้อความแจ้งเตือนหลังจากผ่านไป 5 วินาที
            setTimeout(() => {
                this.submitted = false;
            }, 5000);
        }
    },
    head() {
      return {
        title: 'ติดต่อเรา'
      }
    }
}
</script>

<style scoped>
.contact-bg {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
  padding-top: 60px; /* เว้นที่สำหรับ Navbar */
}

.page-title {
  color: #FFFFFF;
  font-weight: 700;
  font-size: 2.5rem;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.card-glassmorphism {
  background: rgba(255, 255, 255, 0.1) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px !important;
}

/* ทำให้ช่องกรอกข้อมูลดูโดดเด่นขึ้นบนพื้นหลังโปร่งแสง */
:deep(.v-text-field--outlined .v-field__slot), 
:deep(.v-text-field--outlined .v-input__control) {
  background: rgba(0, 0, 0, 0.2) !important;
}
</style>