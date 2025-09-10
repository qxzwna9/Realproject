<template>
  <div>
    <h1 class="page-title">จัดการออเดอร์</h1>
    <p class="page-subtitle">ตรวจสอบและจัดการออเดอร์ทั้งหมดของลูกค้า</p>
    <v-card class="table-card">
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="ค้นหา..."
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
        :items="orders"
        :search="search"
        :loading="loading"
        loading-text="กำลังโหลดข้อมูลออเดอร์..."
        class="luxury-table"
      >
        <template v-slot:item.created_at="{ item }">
          {{ new Date(item.created_at).toLocaleString() }}
        </template>
        <template v-slot:item.total="{ item }">
          {{ parseFloat(item.total).toFixed(2) }} ฿
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip :color="getStatusColor(item.status)" dark small>
            {{ item.status }}
          </v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn icon small class="mr-2" @click="editItem(item)" title="แก้ไขออเดอร์"><v-icon small>mdi-pencil-outline</v-icon></v-btn>
          <v-btn icon small @click="deleteItem(item)" title="ลบออเดอร์"><v-icon small>mdi-delete-outline</v-icon></v-btn>
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="600px" persistent>
      <v-card class="dialog-card">
        <v-card-title class="dialog-title">
          แก้ไขสถานะออเดอร์
        </v-card-title>
        <v-card-text class="pt-4">
          <v-container>
            <v-row>
              <v-col cols="12">
                <p><strong>ออเดอร์ไอดี:</strong> #{{ editedItem.order_id }}</p>
                <p><strong>ลูกค้า:</strong> {{ editedItem.customer_name }}</p>
                 <p><strong>ยอดรวม:</strong> {{ parseFloat(editedItem.total).toFixed(2) }} ฿</p>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="editedItem.status"
                  :items="statuses"
                  label="สถานะออเดอร์*"
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
          <v-btn text @click="close">ยกเลิก</v-btn>
          <v-btn dark color="#1A1A1A" @click="save" :loading="saving">บันทึก</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  layout: 'admin',
  middleware: 'admin-auth',

  // --- 1. เปลี่ยนมาใช้ asyncData ในการดึงข้อมูล ---
  async asyncData({ $axios, error }) {
    try {
      const { data } = await $axios.get('/get_orders.php');
      if (data.status === 'success') {
        return { orders: data.orders, loading: false };
      } else {
        // ถ้า API ส่ง status 'error' กลับมา
        error({ statusCode: 500, message: data.message || 'API Error' });
        return { orders: [], loading: false };
      }
    } catch (e) {
      // ถ้าเกิดข้อผิดพลาดในการเชื่อมต่อ
      error({ statusCode: 503, message: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้' });
      return { orders: [], loading: false };
    }
  },

  data () {
    return {
      search: '',
      loading: true, // ตั้งค่าเริ่มต้นเป็น true
      saving: false,
      dialog: false,
      headers: [
        { text: 'ไอดีออเดอร์', value: 'order_id', width: '120px' },
        { text: 'ลูกค้า', value: 'customer_name' },
        { text: 'ยอดรวม', value: 'total' },
        { text: 'สถานะ', value: 'status' },
        { text: 'วันที่', value: 'created_at' },
        { text: 'เครื่องมือ', value: 'actions', sortable: false, align: 'end' },
      ],
      // orders จะถูกเติมค่าจาก asyncData
      statuses: ['pending', 'processing', 'shipped', 'completed', 'cancelled'],
      editedIndex: -1,
      editedItem: {
        order_id: null,
        status: 'pending',
      },
      defaultItem: {
         order_id: null,
        status: 'pending',
      },
    }
  },

  // --- 2. ลบ mounted() ออก และย้าย methods ที่เหลือมาไว้ตรงนี้ ---
  methods: {
    getStatusColor(status) {
        switch(status) {
            case 'completed': return 'success';
            case 'shipped': return 'info';
            case 'processing': return 'primary';
            case 'cancelled': return 'error';
            default: return 'grey';
        }
    },
    editItem (item) {
      this.editedIndex = this.orders.indexOf(item)
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
        const response = await this.$axios.post('/update_order_status.php', {
            order_id: this.editedItem.order_id,
            status: this.editedItem.status
        });
        if (response.data.status === 'success') {
          Object.assign(this.orders[this.editedIndex], this.editedItem)
          alert('อัปเดตสถานะออเดอร์เรียบร้อยแล้ว!');
          this.close();
        } else {
          throw new Error(response.data.message);
        }
      } catch (error) {
        console.error("Could not update order status", error);
        alert("เกิดข้อผิดพลาด: " + (error.response ? error.response.data.message : "An unknown error occurred."));
      } finally {
        this.saving = false;
      }
    },
    async deleteItem (item) {
      if(confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบออเดอร์ #${item.order_id}?`)) {
          try {
              const response = await this.$axios.post('/delete_order.php', { order_id: item.order_id });
              if(response.data.status === 'success') {
                  // ลบ item ออกจาก array โดยตรง ไม่ต้องโหลดใหม่
                  const index = this.orders.indexOf(item);
                  this.orders.splice(index, 1);
              } else {
                  alert("เกิดข้อผิดพลาด: " + response.data.message);
              }
          } catch (error) {
              console.error("Could not delete order", error);
              alert("เกิดข้อผิดพลาดขณะลบออเดอร์");
          }
      }
    },
  },
}
</script>

<style>
/* ... style ของคุณ (เหมือนเดิม) ... */
</style>