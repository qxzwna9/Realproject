<script setup>
import { ref, onMounted } from 'vue';

// State สำหรับผูกกับฟอร์ม
const product = ref({
  name: '',
  description: '',
  price: '',
  stock: '',
  categoryId: '',
  imageFile: null
});

const categories = ref([]); // State สำหรับเก็บหมวดหมู่
const message = ref(''); // State สำหรับแสดงข้อความตอบกลับ
const messageType = ref(''); // 'success' or 'error'

// ดึงข้อมูลหมวดหมู่เมื่อ component ถูกสร้าง
onMounted(async () => {
  try {
    const response = await fetch('http://localhost:8080/ProjectReal/db/category_select.php');
    categories.value = await response.json();
  } catch (error) {
    console.error('Could not fetch categories:', error);
  }
});

// ฟังก์ชันเมื่อมีการเลือกไฟล์
const handleFileChange = (event) => {
  product.value.imageFile = event.target.files[0];
};

// ฟังก์ชันสำหรับ submit ฟอร์ม
const addProduct = async () => {
  // ตรวจสอบข้อมูลเบื้องต้น
  if (!product.value.name || !product.value.categoryId || !product.value.imageFile) {
    message.value = 'กรุณากรอกข้อมูลให้ครบ: ชื่อสินค้า, หมวดหมู่ และรูปภาพ';
    messageType.value = 'error';
    return;
  }

  // ใช้ FormData เพราะเรามีการส่งไฟล์ไปด้วย
  const formData = new FormData();
  formData.append('product_name', product.value.name);
  formData.append('description', product.value.description);
  formData.append('price', product.value.price);
  formData.append('stock', product.value.stock);
  formData.append('category_id', product.value.categoryId);
  formData.append('image', product.value.imageFile);

  try {
    const apiUrl = 'http://localhost:8080/ProjectReal/db/product_add.php';
    const response = await fetch(apiUrl, {
      method: 'POST',
      body: formData // ส่ง FormData ไปกับ request
    });

    const result = await response.json();
    
    if (result.status === 'success') {
      message.value = 'เพิ่มสินค้าสำเร็จ!';
      messageType.value = 'success';
      // ล้างฟอร์ม (ทางเลือก)
      product.value = { name: '', description: '', price: '', stock: '', categoryId: '', imageFile: null };
      document.querySelector('input[type="file"]').value = ''; // ล้าง input file
    } else {
      message.value = 'เกิดข้อผิดพลาด: ' + result.message;
      messageType.value = 'error';
    }
  } catch (error) {
    message.value = 'การเชื่อมต่อล้มเหลว: ' + error.message;
    messageType.value = 'error';
  }
};
</script>

<template>
  <div class="add-product-container">
    <h1>เพิ่มสินค้าใหม่</h1>
    <form @submit.prevent="addProduct" class="add-product-form">
      <div v-if="message" :class="['message', messageType]">
        {{ message }}
      </div>

      <div class="form-group">
        <label for="name">ชื่อสินค้า</label>
        <input type="text" id="name" v-model="product.name" required>
      </div>

      <div class="form-group">
        <label for="description">คำอธิบาย</label>
        <textarea id="description" v-model="product.description"></textarea>
      </div>

      <div class="form-group">
        <label for="price">ราคา</label>
        <input type="number" id="price" step="0.01" v-model="product.price" required>
      </div>

      <div class="form-group">
        <label for="stock">จำนวนสต็อก</label>
        <input type="number" id="stock" v-model="product.stock" required>
      </div>

      <div class="form-group">
        <label for="category">หมวดหมู่</label>
        <select id="category" v-model="product.categoryId" required>
          <option disabled value="">กรุณาเลือกหมวดหมู่</option>
          <option v-for="cat in categories" :key="cat.category_id" :value="cat.category_id">
            {{ cat.category_name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="image">รูปภาพ</label>
        <input type="file" id="image" @change="handleFileChange" accept="image/*" required>
      </div>

      <button type="submit" class="submit-btn">เพิ่มสินค้า</button>
    </form>
  </div>
</template>

<style scoped>
.add-product-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.add-product-form .form-group {
  margin-bottom: 1.5rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}
input, textarea, select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
}
.submit-btn {
  width: 100%;
  padding: 0.8rem;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1.1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}
.submit-btn:hover {
  background-color: #218838;
}
.message {
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
  text-align: center;
}
.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
</style>