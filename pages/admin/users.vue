<template>
  <div>
    <h1 class="page-title">User Management</h1>
    <p class="page-subtitle">Oversee all registered user accounts.</p>
    <v-card class="table-card">
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search..."
          single-line
          hide-details
          dense
          flat
          solo-inverted
          class="search-field"
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="users"
        :search="search"
        :loading="loading"
        loading-text="Loading users..."
        class="luxury-table"
      >
        <template v-slot:item.user_type="{ item }">
          <v-chip :color="item.user_type === 'admin' ? '#D4AF37' : 'grey darken-1'" dark small>
            {{ item.user_type }}
          </v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn icon small class="mr-2" @click="editItem(item)" title="Edit User"><v-icon small>mdi-pencil-outline</v-icon></v-btn>
          <v-btn icon small @click="deleteItem(item)" title="Delete User"><v-icon small>mdi-delete-outline</v-icon></v-btn>
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="600px" persistent>
      <v-card class="dialog-card">
        <v-card-title class="dialog-title">
          Edit User
        </v-card-title>
        <v-card-text class="pt-4">
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editedItem.name" label="Full Name*" required outlined dense></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field :value="editedItem.email" label="Email Address" readonly disabled outlined dense></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="editedItem.user_type"
                  :items="userTypes"
                  label="User Role*"
                  required
                  outlined
                  dense
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions class="pb-4">
          <v-spacer></v-spacer>
          <v-btn text @click="close">Cancel</v-btn>
          <v-btn dark color="#1A1A1A" @click="save" :loading="saving">Save Changes</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      saving: false,
      dialog: false,
      headers: [
        { text: 'ID', value: 'user_id', width: '80px' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'User Type', value: 'user_type' },
        { text: 'Actions', value: 'actions', sortable: false, align: 'end' },
      ],
      users: [],
      userTypes: ['customer', 'admin'],
      editedIndex: -1,
      editedItem: {
        user_id: null,
        name: '',
        email: '',
        user_type: 'customer',
      },
      defaultItem: {
        user_id: null,
        name: '',
        email: '',
        user_type: 'customer',
      },
    }
  },
  async mounted() {
    await this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      this.loading = true;
      try {
        const { data } = await this.$axios.get('/get_all_users.php');
        if(data.status === 'success') {
            this.users = data.users;
        }
      } catch (error) {
        console.error("Could not fetch users", error);
        alert("Error fetching users: " + (error.response ? error.response.data.message : error.message));
      } finally {
        this.loading = false;
      }
    },
    editItem (item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    async save() {
      this.saving = true;
      try {
        const response = await this.$axios.post('/user_update_admin.php', this.editedItem);
        if (response.data.status === 'success') {
          Object.assign(this.users[this.editedIndex], this.editedItem)
          alert('User updated successfully!');
          this.close();
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        console.error("Could not update user", error);
        alert("Error: " + (error.response ? error.response.data.message : "An unknown error occurred."));
      } finally {
        this.saving = false;
      }
    },
    async deleteItem (item) {
      if(confirm(`Are you sure you want to delete ${item.name}?`)) {
          try {
              const response = await this.$axios.post('/delete_user.php', { user_id: item.user_id });
              if(response.data.status === 'success') {
                  this.fetchUsers();
              } else {
                  alert("Error: " + response.data.message);
              }
          } catch (error) {
              console.error("Could not delete user", error);
              alert("An error occurred while deleting the user.");
          }
      }
    },
  },
}
</script>

<style>
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
.table-card {
  border-radius: 4px;
  border: 1px solid #E0E0E0;
}
.search-field {
  max-width: 300px;
}
.luxury-table .v-data-table-header {
  background-color: #F5F5F5;
}
.luxury-table .v-data-table-header th {
  color: #333 !important;
  font-size: 0.8rem !important;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.luxury-table .v-data-footer {
  background-color: #F5F5F5;
}
.dialog-card {
  border-radius: 4px;
}
.dialog-title {
  font-family: 'Playfair Display', serif;
  color: #222;
  font-size: 1.8rem;
  padding-bottom: 1.5rem;
}
</style>