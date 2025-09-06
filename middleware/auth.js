// middleware/auth.js
export default function ({ store, redirect }) {
  if (!store.state.isAuthenticated) {
    return redirect('/Login');
  }
}