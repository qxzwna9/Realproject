<template>
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">A brief overview of your luxury store management.</p>

    <v-row>
      <v-col v-for="(item, i) in overviewItems" :key="i" cols="12" md="4">
        <v-card class="overview-card" :to="item.to" hover>
          <v-icon class="card-icon">{{ item.icon }}</v-icon>
          <div class="card-title">{{ item.title }}</div>
          <div class="card-subtitle">{{ item.subtitle }}</div>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  layout: 'admin',
  middleware: 'admin-auth',
  data() {
    return {
      overviewItems: [
        { icon: 'mdi-account-group-outline', title: 'User Management', subtitle: 'Oversee all user accounts', to: '/admin/users' },
        { icon: 'mdi-package-variant-closed', title: 'Product Management', subtitle: 'Curate your product collection', to: '/admin/products' },
        { icon: 'mdi-receipt-text-outline', title: 'Order Management', subtitle: 'Oversee all customer orders', to: '/admin/orders' },
      ]
    }
  }
}
</script>

<style scoped>
.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  color: #333;
  margin-bottom: 0.5rem;
}
.page-subtitle {
  font-size: 1.1rem;
  color: #777;
  font-weight: 300;
  margin-bottom: 2rem;
}
.overview-card {
  padding: 2.5rem;
  border-radius: 4px;
  border: 1px solid #E0E0E0;
  text-align: center;
  transition: all 0.3s ease-in-out;
}
.overview-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.05);
  border-color: #D4AF37;
}
.card-icon {
  font-size: 3rem;
  color: #D4AF37;
  margin-bottom: 1rem;
}
.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #222;
}
.card-subtitle {
  font-size: 0.9rem;
  color: #888;
  font-weight: 300;
}
</style>