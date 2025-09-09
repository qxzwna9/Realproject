<template>
  <div>
    <h1 class="text-h4 font-weight-bold grey--text text--darken-3 mb-4">Manage Users</h1>
    <v-card class="rounded-lg">
      <v-card-title>
        <span class="subtitle-1 font-weight-bold">All Users</span>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search Users"
          single-line
          hide-details
          dense
          outlined
          class="search-field"
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="users"
        :search="search"
        :loading="loading"
        loading-text="Loading users..."
      >
        <template v-slot:item.user_type="{ item }">
          <v-chip :color="item.user_type === 'admin' ? 'primary' : 'secondary'" dark small>
            {{ item.user_type }}
          </v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)" title="Edit User">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)" color="error" title="Delete User">mdi-delete</v-icon>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
export default {
  layout: 'admin',
  middleware: 'admin-auth',
  data () {
    return {
      search: '',
      loading: true,
      headers: [
        { text: 'ID', value: 'user_id', width: '80px' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'User Type', value: 'user_type' },
        { text: 'Actions', value: 'actions', sortable: false, align: 'end' },
      ],
      users: [],
    }
  },
  async mounted() {
    await this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      // ... โค้ดส่วนนี้เหมือนเดิม ...
    },
    editItem (item) {
      // ... โค้ดส่วนนี้เหมือนเดิม ...
    },
    async deleteItem (item) {
      // ... โค้ดส่วนนี้เหมือนเดิม ...
    },
  },
}
</script>

<style scoped>
.search-field {
  max-width: 300px;
}
</style>