<script setup>
// ... import other components like product list ...
</script>
<template>
  <div class="member-bg">
    <main class="container member-main">
      <div class="welcome-section">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Welcome" class="welcome-img" />
        <div>
          <h1 class="welcome-title">Welcome to our Shop!</h1>
          <p class="welcome-desc">ยินดีต้อนรับสมาชิกทุกท่าน เลือกชมสินค้าและโปรโมชั่นพิเศษได้เลย!</p>
        </div>
      </div>
      <!-- เพิ่มส่วนแนะนำสินค้า หรือปุ่มลิงก์ไปหน้าสินค้า -->
      <div class="action-section">
        <v-btn color="primary" large href="/products">ดูสินค้าทั้งหมด</v-btn>
      </div>
    </main>
  </div>
</template>

<style scoped>
.member-bg {
  min-height: 100vh;
  /* เปลี่ยนสีพื้นหลังให้สดใสสไตล์ ELVURE SHOP */
  background: linear-gradient(120deg, #f8fafc 0%, #ffe3ec 50%, #e0c3fc 100%);
  padding-bottom: 40px;
}

.member-main {
  background: #fff;
  border-radius: 22px;
  padding: 40px 32px;
  margin-top: 36px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.welcome-section {
  display: flex;
  align-items: center;
  gap: 24px;
  margin-bottom: 32px;
}

.welcome-img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: #f0f4ff;
  box-shadow: 0 2px 8px rgba(31, 38, 135, 0.08);
}

.welcome-title {
  font-size: 2.1rem;
  font-weight: 700;
  color: #2d3a4b;
  margin-bottom: 8px;
}

.welcome-desc {
  font-size: 1.1rem;
  color: #5c6f7c;
}

.action-section {
  display: flex;
  justify-content: flex-end;
  margin-top: 24px;
}

/* ลบสีแถบ Navbar ด้านบน */
:deep(.v-app-bar),
:deep(.navbar) {
  background: none !important;
  color: inherit !important;
}
</style>