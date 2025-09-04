<template>
    <nav class="navbar">
        <div class="brand">ShirtShop</div>
        <div class="user-info" v-if="user">
            <span>Welcome, <strong>{{ user.name }}</strong></span>
            <button @click="handleLogout" class="logout-btn">Logout</button>
        </div>
        <div v-else>
            <router-link to="/register">Login / Register</router-link>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            user: null
        }
    },
    async mounted() {
        // Check session on component mount
        try {
            const response = await this.$axios.get('/check_session.php');
            const result = response.data;
            if (result.loggedin) {
                this.user = result.user;
            }
        } catch (error) {
            console.error('Session check failed:', error);
        }
    },
    methods: {
        async handleLogout() {
            try {
                await this.$axios.post('/logout.php');
                this.user = null;
                this.$router.push('/register'); // Redirect to login/register page
            } catch (error) {
                console.error('Logout failed:', error);
            }
        }
    }
}
</script>

<style scoped>
.navbar { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background-color: #f8f9fa; }
.user-info { display: flex; align-items: center; gap: 1rem; }
.logout-btn { /* ... styles for logout button ... */ }
</style>