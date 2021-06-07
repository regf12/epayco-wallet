<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card :title="$t('login')" class="p-3">
        <Login />
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Login from '~/components/auth/Login'

export default {
  middleware: 'guest',

	components: {
		Login,
	},

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  head () {
    return { title: this.$t('login') }
  },

  methods: {
    async login () {
      let data

      // Submit the form.
      try {
        const response = await this.form.post('/login')
        data = response.data
      } catch (e) {
        return
      }

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
