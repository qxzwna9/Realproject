<template>
    <div class="contact-bg">
        <h1 class="contact-title">ติดต่อเรา</h1>
        <form @submit.prevent="submitForm" class="contact-form">
            <div class="form-group">
                <label for="name">ชื่อ</label>
                <input type="text" id="name" v-model="form.name" required />
            </div>
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" v-model="form.email" required />
            </div>
            <div class="form-group">
                <label for="message">ข้อความ</label>
                <textarea id="message" v-model="form.message" required></textarea>
            </div>
            <button type="submit">ส่งข้อความ</button>
        </form>
        <div v-if="submitted" class="success-message">
            ขอบคุณสำหรับการติดต่อ!
        </div>
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
            // สามารถเพิ่ม logic ส่งข้อมูลไป backend ได้ที่นี่
            this.submitted = true
            this.form = { name: '', email: '', message: '' }
        }
    }
}
</script>

<style scoped>
.contact-bg {
    min-height: 100vh;
    width: 100vw;
    background: linear-gradient(135deg, #111827 0%, #111827 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 64px;
}
.contact-title {
    font-size: 2.4rem;
    color: #fff;
    margin-bottom: 32px;
    text-align: center;
    font-weight: bold;
    text-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.contact-form {
    width: 100%;
    max-width: 420px;
    padding: 0 16px;
}
.form-group {
    margin-bottom: 16px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #ffffff;
}
input, textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #fff;
    border-radius: 4px;
    background: rgba(158, 158, 158, 0.8);
    color: #222;
}
button {
    background: #ffffff;
    color: #000000;
    border: none;
    padding: 10px 24px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.08);
}
button:hover {
    background: #696969;
    color: #fff;
}
.success-message {
    margin-top: 20px;
    color: #fff;
    font-weight: bold;
    font-size: 1.2rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
</style>