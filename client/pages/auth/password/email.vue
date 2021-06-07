<template>
	<div class="w-100 h-100">

		<b-container class="bv-example-row mt-5 pt-5">
			<b-row>
				<b-col></b-col>
				<b-col cols="12" sm="8" md="6">
					<v-row class="m-5 p-5">
						<v-col>
							<card :title="$t('reset_password')">
								<Email />
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
import Email from '~/components/auth/password/Email'

export default {
  layout: 'simple',

	components: {
		Email,
	},

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  head () {
    return { title: this.$t('reset_password') }
  },

  methods: {
    send () {
      this.form.post('/password/email').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
