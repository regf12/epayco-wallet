<template>
	<div class="w-100 h-100">

		<b-container class="bv-example-row mt-5 pt-5">
			<b-row>
				<b-col></b-col>
				<b-col cols="12" sm="8" md="6">
					<v-row class="m-5 p-5">
						<v-col>
							<card :title="$t('reset_password')">
								<Reset />
							</card>
						</v-col>
					</v-row>
				</b-col>
				<b-col></b-col>
			</b-row>
		</b-container>

  </div>
</template>

<script>
import Form from 'vform'
import Reset from '~/components/auth/password/Reset'

export default {
  middleware: 'guest',

  layout: 'simple',

	components: {
		Reset,
	},

  data: () => ({
    status: '',
    form: new Form({
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),

  head () {
    return { title: this.$t('reset_password') }
  },

  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    reset () {
      this.form.post('/password/reset').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
