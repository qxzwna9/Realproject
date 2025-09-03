<template>
  <v-container>
    <v-card class="pa-4">
      <v-card-title>
        รายการสินค้าเสื้อผ้า
        <v-spacer />
        <v-btn color="primary" @click="fetchShirts">รีเฟรช</v-btn>
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="shirts"
        :loading="loading"
        loading-text="กำลังโหลดข้อมูล..."
        class="elevation-1 mt-4"
      >
        <template v-slot:item.price="{ item }">
          {{ Number(item.price).toFixed(2) }} ฿
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// ✅ API URL ของคุณ (แก้ให้ตรง path จริงบนเซิร์ฟเวอร์)
const API_URL = 'http://localhost:8080/shirtshop/shirt_select.php'

const shirts = ref([])
const loading = ref(false)

const headers = [
  { title: 'รหัส', key: 'product_id' },
  { title: 'ชื่อสินค้า', key: 'product_name' },
  { title: 'หมวดหมู่', key: 'category_name' },
  { title: 'ราคา', key: 'price' },
  { title: 'คงเหลือ', key: 'stock' },
]
  
const fetchShirts = async () => {
  loading.value = true
  try {
    const res = await fetch('http://localhost:8080/shirtshop/shirt_select.php')
    const data = await res.json()
    if (data.status === 'success') {
      shirts.value = data.data
    } else {
      console.error('เกิดข้อผิดพลาด:', data.message)
    }
  } catch (err) {
    console.error('ไม่สามารถเชื่อมต่อ API ได้:', err)
  } finally {
    loading.value = false
  }
}

// ดึงข้อมูลทันทีเมื่อโหลดหน้า
onMounted(() => {
  fetchShirts()
})
</script>
