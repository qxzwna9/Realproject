<script setup>
import { ref, onMounted } from 'vue';

// สร้างตัวแปรแบบ reactive เพื่อเก็บข้อมูลสินค้า
const products = ref([]);
const isLoading = ref(true); // ตัวแปรสำหรับสถานะ Loading
const error = ref(null); // ตัวแปรสำหรับเก็บ Error

// onMounted คือฟังก์ชันที่จะทำงานอัตโนมัติเมื่อ Component ถูกสร้างขึ้น
onMounted(async () => {
  try {
    // URL ของไฟล์ PHP ที่เราสร้างไว้ (ต้องใส่ path ให้ถูกต้อง)
    const apiUrl = 'http://localhost:8080/ProjectReal/db/shirt_select.php'; // **<-- แก้ไข path ตรงนี้ให้ตรงกับโปรเจกต์ของคุณ**

    const response = await fetch(apiUrl);

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    // แปลงข้อมูล JSON ที่ได้จาก PHP มาเก็บในตัวแปร products
    products.value = await response.json();

  } catch (e) {
    // หากเกิดข้อผิดพลาดในการดึงข้อมูล
    error.value = 'ไม่สามารถดึงข้อมูลได้: ' + e.message;
    console.error("Fetch error:", e);
  } finally {
    // ไม่ว่าจะสำเร็จหรือล้มเหลว ให้ปิดสถานะ Loading
    isLoading.value = false;
  }
});
</script>

<template>
  <div class="product-container">
    <h1>รายการสินค้าทั้งหมด</h1>

    <div v-if="isLoading" class="loading">
      <p>กำลังโหลดข้อมูลสินค้า...</p>
    </div>

    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
    </div>

    <table v-else-if="products.length > 0" class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>รูปภาพ</th>
          <th>ชื่อสินค้า</th>
          <th>หมวดหมู่</th>
          <th>ราคา (บาท)</th>
          <th>สต็อก</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.product_id">
          <td>{{ product.product_id }}</td>
          <td>
            <img :src="'http://localhost:8080/ProjectReal/images/' + product.image" :alt="product.product_name" class="product-image">
          </td>
          <td>{{ product.product_name }}</td>
          <td>{{ product.category_name }}</td>
          <td>{{ parseFloat(product.price).toFixed(2) }}</td>
          <td>{{ product.stock }}</td>
        </tr>
      </tbody>
    </table>
    
    <div v-else class="no-data">
      <p>ไม่พบข้อมูลสินค้าในฐานข้อมูล</p>
    </div>

  </div>
</template>

<style scoped>
.product-container {
  font-family: Arial, sans-serif;
  max-width: 1000px;
  margin: 2rem auto;
  padding: 1rem;
}

.product-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-table th, .product-table td {
  border: 1px solid #64626273;
  padding: 12px;
  text-align: left;
}

.product-table th {
  background-color: #3b3b3b;
  font-weight: bold;
}

.product-table tbody tr:hover {
  background-color: #4e4e4e;
}

.product-image {
  max-width: 60px;
  border-radius: 4px;
}

.loading, .error, .no-data {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
  color: #666;
}

.error {
  color: red;
}
</style>