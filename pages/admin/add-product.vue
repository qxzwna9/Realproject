<template>
  <div>
    <h1 class="page-title">Add a New Masterpiece</h1>
    <p class="page-subtitle">Introduce a new item to your exclusive collection.</p>
    <v-card class="table-card">
        <v-card-text class="pa-8">
            <v-form @submit.prevent="submitForm" ref="form">
                <v-alert v-if="message" :type="messageType" text class="mb-6">
                    {{ message }}
                </v-alert>

                <v-text-field
                    v-model="product.name"
                    label="Product Name*"
                    outlined
                    dense
                    required
                ></v-text-field>

                <v-textarea
                    v-model="product.description"
                    label="Description"
                    outlined
                    dense
                    rows="3"
                ></v-textarea>

                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model.number="product.price"
                            label="Price*"
                            type="number"
                            min="0"
                            prefix="฿"
                            outlined
                            dense
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model.number="product.stock"
                            label="Stock*"
                            type="number"
                            min="0"
                            outlined
                            dense
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-select
                    v-model="product.categoryId"
                    :items="categories"
                    item-text="category_name"
                    item-value="category_id"
                    label="Category*"
                    outlined
                    dense
                    required
                ></v-select>

                <v-file-input
                    v-model="product.imageFile"
                    label="Product Image*"
                    accept="image/*"
                    show-size
                    outlined
                    dense
                    required
                    prepend-icon="mdi-camera"
                ></v-file-input>

                <v-btn
                    dark
                    color="#1A1A1A"
                    type="submit"
                    :loading="loading"
                    x-large
                    block
                    class="mt-4"
                >
                    Add Product to Collection
                </v-btn>
            </v-form>
        </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
    layout: 'admin',
    middleware: 'admin-auth',
    data() {
    return {
      product: {
        name: '',
        description: '',
        price: '',
        stock: '',
        categoryId: null,
        imageFile: null
      },
      categories: [],
      loading: false,
      message: '',
      messageType: 'error'
    }
  },
  async mounted() {
    await this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const { data } = await this.$axios.get('/category_select.php');
        this.categories = data;
      } catch (error) {
        this.message = 'Could not fetch categories.';
        this.messageType = 'error';
        console.error("Could not fetch categories", error);
      }
    },
    async submitForm() {
      if (!this.product.name || !this.product.categoryId || !this.product.imageFile) {
        this.message = 'Please fill in all required fields: Name, Category, and Image.';
        this.messageType = 'error';
        return;
      }
      this.loading = true;
      this.message = '';
      const formData = new FormData();
      formData.append('product_name', this.product.name);
      formData.append('description', this.product.description);
      formData.append('price', this.product.price);
      formData.append('stock', this.product.stock);
      formData.append('category_id', this.product.categoryId);
      formData.append('image', this.product.imageFile);
      try {
        const response = await this.$axios.post('/product_add.php', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        if (response.data.status === 'success') {
          this.message = 'Product added successfully! Redirecting...';
          this.messageType = 'success';
          this.$refs.form.reset();
          this.product.imageFile = null;
          setTimeout(() => {
            this.$router.push('/admin/products');
          }, 2000);
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        this.message = error.response ? error.response.data.message : 'An unknown error occurred.';
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>