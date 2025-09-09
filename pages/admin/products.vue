<template>
  <div>
    <h1 class="text-h4 font-weight-bold grey--text text--darken-3 mb-4">Manage Products</h1>
    <v-card class="rounded-lg">
      <v-card-title>
        <span class="subtitle-1 font-weight-bold">All Products</span>
        <v-spacer></v-spacer>
        <v-btn color="primary" to="/admin/add-product">
          <v-icon left>mdi-plus</v-icon>
          Add Product
        </v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="products"
        :loading="loading"
        loading-text="Loading products..."
      >
        <template v-slot:item.image="{ item }">
          <v-avatar tile size="50" class="ma-2 rounded">
            <v-img :src="`http://localhost:8080/ProjectReal/images/${item.image}`" :alt="item.product_name"></v-img>
          </v-avatar>
        </template>
        <template v-slot:item.price="{ item }">
          {{ parseFloat(item.price).toFixed(2) }} ฿
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)" title="Edit Product">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)" color="error" title="Delete Product">mdi-delete</v-icon>
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="700px" persistent>
      <v-card>
        <v-card-title class="text-h5 grey--text text--darken-2">
          Edit Product
        </v-card-title>
        <v-card-text class="pt-4">
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editedItem.product_name" label="Product Name*" required outlined dense></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-textarea v-model="editedItem.description" label="Description" rows="3" outlined dense></v-textarea>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="editedItem.category_id"
                  :items="categories"
                  item-text="category_name"
                  item-value="category_id"
                  label="Category*"
                  required
                  outlined
                  dense
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6">
                  <p class="body-2 grey--text">Current Image:</p>
                  <v-img
                    :src="`http://localhost:8080/ProjectReal/images/${editedItem.image}`"
                    max-height="150"
                    contain
                    class="grey lighten-2 rounded"
                  ></v-img>
              </v-col>
              <v-col cols="12" sm="6">
                  <v-file-input
                    v-model="editedItem.newImageFile"
                    label="Upload New Image (Optional)"
                    accept="image/*"
                    show-size
                    outlined
                    dense
                    prepend-icon="mdi-camera"
                  ></v-file-input>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field v-model="editedItem.price" label="Price" type="number" prefix="฿" outlined dense></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field v-model="editedItem.stock" label="Stock" type="number" outlined dense></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-1" text @click="close">Cancel</v-btn>
          <v-btn color="primary" text @click="save" :loading="saving">Save Changes</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  middleware: 'admin-auth',
  data () {
    return {
      loading: true,
      saving: false,
      dialog: false,
      headers: [
        { text: 'Image', value: 'image', sortable: false },
        { text: 'ID', value: 'product_id', width: '80px' },
        { text: 'Product Name', value: 'product_name' },
        { text: 'Price', value: 'price' },
        { text: 'Stock', value: 'stock' },
        { text: 'Actions', value: 'actions', sortable: false, align: 'end' },
      ],
      products: [],
      categories: [],
      editedIndex: -1,
      editedItem: {
        product_id: 0,
        product_name: '',
        description: '',
        price: 0,
        stock: 0,
        category_id: null,
        image: '',
        newImageFile: null,
      },
      defaultItem: {
        product_id: 0,
        product_name: '',
        description: '',
        price: 0,
        stock: 0,
        category_id: null,
        image: '',
        newImageFile: null,
      },
    }
  },
  async mounted() {
    await this.fetchProducts();
    await this.fetchCategories();
  },
  methods: {
    async fetchProducts() {
      this.loading = true;
      try {
        const { data } = await this.$axios.get('/shirt_select.php');
        this.products = data;
      } catch (error) {
        console.error("Could not fetch products", error);
        alert("Error: Could not fetch products.");
      } finally {
        this.loading = false;
      }
    },
    async fetchCategories() {
        try {
            const  response  = await this.$axios.get('/category_select.php');
            this.categories = response.data
        } catch (error) {
            console.error("Could not fetch categories", error);
        }
    },
    editItem (item) {
      this.editedIndex = this.products.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editedItem.newImageFile = null;
      this.dialog = true
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    async save () {
      this.saving = true;
      const formData = new FormData();
      formData.append('product_id', this.editedItem.product_id);
      formData.append('product_name', this.editedItem.product_name);
      formData.append('description', this.editedItem.description);
      formData.append('price', this.editedItem.price);
      formData.append('stock', this.editedItem.stock);
      formData.append('category_id', this.editedItem.category_id);

      if (this.editedItem.newImageFile) {
        formData.append('image', this.editedItem.newImageFile);
      }

      try {
        const response = await this.$axios.post('/product_update_with_image.php', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        if (response.data.status === 'success') {
          alert('Product updated successfully!');
          this.close();
          await this.fetchProducts();
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        console.error("Could not update product", error);
        alert("Error: " + (error.response ? error.response.data.message : "Undefined error"));
      } finally {
        this.saving = false;
      }
    },
    async deleteItem (item) {
      if(confirm(`Are you sure you want to delete ${item.product_name}?`)) {
          try {
              const response = await this.$axios.post('/shirt_delete.php', { product_id: item.product_id });
              if(response.data.status === 'success') {
                  this.fetchProducts();
              } else {
                  alert("Error: " + response.data.message);
              }
          } catch (error) {
              console.error("Could not delete product", error);
              alert("An error occurred while deleting the product.");
          }
      }
    },
  },
}
</script>