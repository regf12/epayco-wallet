export default ({ store, redirect }) => {
  if (!store.getters['auth/check']) {
    return redirect('/')
  }
	if (store.getters['auth/user'].type=='ADMIN') {
    return redirect('/operations')
  }
}
