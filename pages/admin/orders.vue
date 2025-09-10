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
            {{ translateStatus(item.status) }}
          </v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
           <v-btn v-if="item.status === 'pending'" icon small class="mr-2" @click="updateStatus(item, 'processing')" title="อนุมัติออเดอร์">
              <v-icon small color="success">mdi-check-circle-outline</v-icon>
          </v-btn>
           <v-btn v-if="item.status === 'processing'" icon small class="mr-2" @click="updateStatus(item, 'shipped')" title="จัดส่งแล้ว">
              <v-icon small color="info">mdi-truck-delivery-outline</v-icon>
          </v-btn>
          <v-btn v-if="item.status === 'shipped'" icon small class="mr-2" @click="updateStatus(item, 'completed')" title="จัดส่งสำเร็จ">
               <v-icon small color="success">mdi-package-check</v-icon>
          </v-btn>
          <v-btn icon small class="mr-2" @click="editItem(item)" title="แก้ไขสถานะ"><v-icon small>mdi-pencil-outline</v-icon></v-btn>
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
  async asyncData({ $axios, error }) {
    try {
      const { data } = await $axios.get('/get_orders.php');
      if (data.status === 'success') {
        return { orders: data.orders, loading: false };
      } else {
        error({ statusCode: 500, message: data.message || 'API Error' });
        return { orders: [], loading: false };
      }
    } catch (e) {
      error({ statusCode: 503, message: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้' });
      return { orders: [], loading: false };
    }
  },

  data () {
    return {
      search: '',
      loading: true,
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
    translateStatus(status) {
      const translations = {
        pending: 'รอดำเนินการ',
        processing: 'กำลังจัดส่งสินค้า', // <--- แก้ไขข้อความนี้
        shipped: 'จัดส่งแล้ว',
        completed: 'จัดส่งสำเร็จ',
        cancelled: 'ยกเลิกแล้ว'
      };
      return translations[status] || status;
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
      const itemToUpdate = { ...this.editedItem };
      await this.updateOrderStatus(itemToUpdate);
      this.close();
      this.saving = false;
    },
    async deleteItem (item) {
      if(confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบออเดอร์ #${item.order_id}?`)) {
          try {
              const response = await this.$axios.post('/delete_order.php', { order_id: item.order_id });
              if(response.data.status === 'success') {
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
    async updateStatus(item, newStatus) {
        if (confirm(`คุณต้องการเปลี่ยนสถานะออเดอร์ #${item.order_id} เป็น "${this.translateStatus(newStatus)}" ใช่หรือไม่?`)) {
            const itemClone = { ...item, status: newStatus };
            await this.updateOrderStatus(itemClone);
        }
    },
    async updateOrderStatus(item) {
        this.saving = true;
        try {
            const response = await this.$axios.post('/update_order_status.php', {
                order_id: item.order_id,
                status: item.status
            });
            if (response.data.status === 'success') {
                const index = this.orders.findIndex(o => o.order_id === item.order_id);
                if (index !== -1) {
                    Object.assign(this.orders[index], item);
                }
            } else {
                throw new Error(response.data.message);
            }
        } catch (error) {
            console.error("Could not update order status", error);
            alert("เกิดข้อผิดพลาด: " + (error.response ? error.response.data.message : "An unknown error occurred."));
        } finally {
            this.saving = false;
        }
    }
  },
}
</script>

<style>
/* ... style ของคุณ (เหมือนเดิม) ... */
</style>