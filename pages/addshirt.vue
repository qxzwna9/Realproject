<template>
  <v-container class="py-8">
    <v-card class="pa-6 mx-auto" max-width="500">
      <v-card-title class="headline">เพิ่มสินค้าเสื้อผ้า</v-card-title>
      <v-card-text>
        <v-form @submit.prevent="submitForm" ref="form">
          <v-text-field v-model="form.product_name" label="ชื่อสินค้า" required />
          <v-textarea v-model="form.description" label="รายละเอียด" rows="2" required />
          <v-text-field v-model="form.price" label="ราคา" type="number" min="0" required />
          <v-text-field v-model="form.stock" label="คงเหลือ" type="number" min="0" required />
          <v-text-field v-model="form.category_id" label="รหัสหมวดหมู่ (category_id)" type="number" min="1" required />
          <v-btn color="primary" type="submit" :loading="loading" block>บันทึกสินค้า</v-btn>
        </v-form>
        <v-alert v-if="message" type="success" class="mt-4">{{ message }}</v-alert>
        <v-alert v-if="error" type="error" class="mt-4">{{ error }}</v-alert>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  product_name: '',
  description: '',
  price: '',
  stock: '',
  category_id: ''
})
const loading = ref(false)
const message = ref('')
const error = ref('')

const submitForm = async () => {
  loading.value = true
  message.value = ''
  error.value = ''
  try {
    // กำหนด key ให้ตรงกับ shirt_insert.php
    const formData = new FormData()
    formData.append('product_name', form.value.product_name)
    formData.append('description', form.value.description)
    formData.append('price', form.value.price)
    formData.append('stock', form.value.stock)
    formData.append('category_id', form.value.category_id)

    const res = await fetch('http://localhost:8080/shirtshop/shirt_insert.php', {
      method: 'POST',
      body: formData
    })
    const text = await res.text()
    if (res.ok && text.includes('เพิ่มสินค้าเรียบร้อย')) {
      message.value = text
      Object.keys(form.value).forEach(k => form.value[k] = '')
      if (typeof $refs?.form?.reset === 'function') $refs.form.reset()
    } else {
      error.value = text || 'เกิดข้อผิดพลาดในการบันทึก'
    }
  } catch (err) {
    error.value = 'ไม่สามารถเชื่อมต่อ API ได้: ' + err
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.v-card {
  border-radius: 18px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
}
</style>
