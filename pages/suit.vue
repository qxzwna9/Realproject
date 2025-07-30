<template>
  <v-container>
    <v-card>
      <v-card-title>
        รายการชุดสูท
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="suits"
          :loading="loading"
          class="elevation-1"
          loading-text="กำลังโหลดข้อมูล..."
        >
          <template v-slot:item.price="{ item }">
            {{ Number(item.price).toLocaleString() }} บาท
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      suits: [],
      loading: true,
      headers: [
        { text: 'ชื่อสินค้า', value: 'name' },
        { text: 'ขนาด', value: 'size' },
        { text: 'สี', value: 'color' },
        { text: 'ราคา', value: 'price' },
        { text: 'จำนวนคงเหลือ', value: 'stock' },
      ],
    }
  },
  async fetch() {
    try {
      const res = await fetch('http://localhost:8080/suit_select.php?category=สูท')
      const data = await res.json()
      if (data.status === 'success') {
        this.suits = data.data
      } else {
        console.error('Error from API:', data.message)
      }
    } catch (err) {
      console.error('Fetch error:', err)
    } finally {
      this.loading = false
    }
  }
}
</script>
