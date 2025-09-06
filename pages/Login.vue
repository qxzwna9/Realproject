<script>
export default {
  layout: 'guest', // <-- บอกให้หน้านี้ใช้ guest layout
  data() {
    return {
      form: { email: '', password: '' },
      message: '',
      messageType: '',
      loading: false
    }
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.message = '';
      try {
        // --- ส่วนที่แก้ไข ---
        const user = await this.$store.dispatch('login', this.form);
        this.message = 'เข้าสู่ระบบสำเร็จ!';
        this.messageType = 'success';
        const destination = user.user_type === 'admin' ? '/AdminIndex' : '/MemberIndex';
        this.$router.push(destination);
      } catch (error) {
        this.message = error.response ? error.response.data.message : error.message;
        this.messageType = 'error';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>