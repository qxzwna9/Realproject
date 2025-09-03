import vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
vue.config.productionTip = false;
const request = axios.create({
  baseURL: process.env.API_URL || 'http://localhost:8080/shirtshop/shirt_select.php',
})
vue.use(VueAxios, request);