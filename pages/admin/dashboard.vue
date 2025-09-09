<template>
  <div>
    <h1 class="text-h4 font-weight-bold grey--text text--darken-3 mb-2">Admin Dashboard</h1>
    <p class="subtitle-1 grey--text">Welcome back, {{ $store.state.user ? $store.state.user.name : 'Admin' }}! Here's a quick overview.</p>

    <v-row>
      <v-col cols="12" md="4">
        <v-card class="pa-4 rounded-lg" to="/admin/users" hover>
          <div class="d-flex align-center">
            <v-avatar color="blue lighten-4" size="60">
              <v-icon color="primary">mdi-account-group</v-icon>
            </v-avatar>
            <div class="ml-4">
              <div class="text-h6 font-weight-bold">Manage Users</div>
              <div class="caption">View, Edit, Delete Users</div>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" md="4">
        <v-card class="pa-4 rounded-lg" to="/admin/products" hover>
           <div class="d-flex align-center">
            <v-avatar color="green lighten-4" size="60">
              <v-icon color="success">mdi-tshirt-crew</v-icon>
            </v-avatar>
            <div class="ml-4">
              <div class="text-h6 font-weight-bold">Manage Products</div>
              <div class="caption">View, Edit, Delete Products</div>
            </div>
          </div>
        </v-card>
      </v-col>
       <v-col cols="12" md="4">
        <v-card class="pa-4 rounded-lg" to="/admin/add-product" hover>
           <div class="d-flex align-center">
            <v-avatar color="orange lighten-4" size="60">
              <v-icon color="orange">mdi-plus-box</v-icon>
            </v-avatar>
            <div class="ml-4">
              <div class="text-h6 font-weight-bold">Add a New Product</div>
              <div class="caption">Create a new item for your store</div>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  // ไม่มี layout: 'admin'
  middleware: 'admin-auth'
}
</script>