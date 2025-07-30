<template>
    <div class="register-bg">
        <div class="register-content">
            <h2 v-if="isRegister">สมัครสมาชิก</h2>
            <h2 v-else>เข้าสู่ระบบ</h2>
            <form @submit.prevent="handleSubmit">
                <div v-if="isRegister">
                    <label>ชื่อผู้ใช้</label>
                    <input v-model="form.username" required />
                </div>
                <label>อีเมล</label>
                <input v-model="form.email" type="email" required />
                <label>รหัสผ่าน</label>
                <input v-model="form.password" type="password" required />
                <button type="submit">{{ isRegister ? 'สมัครสมาชิก' : 'เข้าสู่ระบบ' }}</button>
            </form>
            <p>
                <span v-if="isRegister">
                    มีบัญชีแล้ว? <a href="#" @click.prevent="toggleForm">เข้าสู่ระบบ</a>
                </span>
                <span v-else>
                    ยังไม่มีบัญชี? <a href="#" @click.prevent="toggleForm">สมัครสมาชิก</a>
                </span>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const isRegister = ref(true)
const form = reactive({
    username: '',
    email: '',
    password: ''
})

function toggleForm() {
    isRegister.value = !isRegister.value
    form.username = ''
    form.email = ''
    form.password = ''
}

function handleSubmit() {
    if (isRegister.value) {
        // สมัครสมาชิก
        alert('สมัครสมาชิกสำเร็จ')
    } else {
        // เข้าสู่ระบบ
        alert('เข้าสู่ระบบสำเร็จ')
    }
}
</script>

<style scoped>
.register-bg {
    min-height: 100vh;
    width: 100vw;
    background: linear-gradient(135deg, #F43F5E 0%, #F9A8D4 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.register-content {
    width: 100%;
    max-width: 400px;
    padding: 32px 24px 24px 24px;
    box-sizing: border-box;
    background: rgba(255,255,255,0.92);
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
}
h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #F43F5E;
    font-size: 2rem;
    font-weight: bold;
}
label {
    display: block;
    margin-top: 12px;
    color: #333;
    font-weight: bold;
}
input {
    width: 100%;
    padding: 8px;
    margin-top: 4px;
    box-sizing: border-box;
    border-radius: 4px;
    border: 1px solid #F43F5E;
    background: #fff;
}
button {
    margin-top: 20px;
    width: 100%;
    padding: 10px;
    background: #F43F5E;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(244,63,94,0.08);
    cursor: pointer;
}
button:hover {
    background: #F9A8D4;
    color: #F43F5E;
}
a {
    color: #F43F5E;
    cursor: pointer;
    font-weight: bold;
}
</style>