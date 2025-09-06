// plugins/auth-init.js
export default async ({ store }) => {
  if (!store.state.isAuthenticated) {
    await store.dispatch('checkAuth');
  }
}