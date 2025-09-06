import colors from 'vuetify/es5/util/colors'

export default {
  mode: 'spa',
  head: {
    // ... head config ...
  },
  loading: { color: '#fff' },
  css: [],
  plugins: [],
  buildModules: [ '@nuxtjs/vuetify' ],
  modules: [ '@nuxtjs/axios', '@nuxtjs/pwa' ],
  axios: {
    baseURL: 'http://localhost:8080/projectreal/db/', // <-- URL หลักของ API
    credentials: true
  },
  vuetify: {
    // ... vuetify config ...
  },
  build: {
    extend (config, ctx) {}
  }
}