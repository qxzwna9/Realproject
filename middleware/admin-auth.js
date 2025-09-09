export default function ({ store, redirect }) {
  if (!store.state.isAuthenticated || store.state.user.user_type !== 'admin') {
    return redirect('/admin');
  }
}