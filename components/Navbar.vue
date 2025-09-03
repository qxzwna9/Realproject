<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const user = ref(null);
const router = useRouter();

onMounted(async () => {
    // Check session on component mount
    const response = await fetch('http://localhost:8080/ProjectReal/db/check_session.php');
    const result = await response.json();
    if (result.loggedin) {
        user.value = result.user;
    }
});

const handleLogout = async () => {
    await fetch('http://localhost:8080/ProjectReal/db/logout.php');
    user.value = null;
    router.push('/login');
};
</script>
<template>
    <nav class="navbar">
        <div class="brand">ShirtShop</div>
        <div class="user-info" v-if="user">
            <span>Welcome, <strong>{{ user.name }}</strong></span>
            <button @click="handleLogout" class="logout-btn">Logout</button>
        </div>
        <div v-else>
            <router-link to="/login">Login</router-link>
        </div>
    </nav>
</template>
<style scoped>
.navbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background-color: #f8f9fa; }
.user-info { display: flex; align-items: center; gap: 1rem; }
.logout-btn { /* ... styles for logout button ... */ }
</style>