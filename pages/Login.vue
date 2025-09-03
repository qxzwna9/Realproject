<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const message = ref('');
const router = useRouter();

const handleLogin = async () => {
  try {
    const apiUrl = 'http://localhost:8080/ProjectReal/db/login.php';
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    });
    const result = await response.json();
    if (result.status === 'success') {
      // Redirect based on user type
      if (result.user.user_type === 'admin') {
        router.push('/admin');
      } else {
        router.push('/member');
      }
    } else {
      message.value = result.message;
    }
  } catch (error) {
    message.value = 'Login failed: ' + error.message;
  }
};
</script>
<template>
  <div class="auth-container">
    <form @submit.prevent="handleLogin" class="auth-form">
      <h1>Login</h1>
      <div v-if="message" class="message error">{{ message }}</div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" v-model="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" v-model="password" required>
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>