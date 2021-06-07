<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card v-if="mustVerifyEmail" :title="$t('register')">
        <div class="alert alert-success" role="alert">
          {{ $t('verify_email_address') }}
        </div>
      </card>
      <card v-else :title="$t('register')" class="p-3">
        <Register />
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Register from '~/components/auth/Register'

export default {
  middleware: 'guest',

	components: {
		Register
	},

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  head () {
    return { title: this.$t('register') }
  },

  methods: {
    async register () {
      let data

      // Register the user.
      try {
        const response = await this.form.post('/register')
        data = response.data
      } catch (e) {
        return
      }

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true
      } else {
        // Log in the user.
        const { data: { token } } = await this.form.post('/login')

        // Save the token.
        this.$store.dispatch('auth/saveToken', { token })

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data })

        // Redirect home.
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
