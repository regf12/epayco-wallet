<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
			<Resend />
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Resend from '~/components/auth/verification/Resend'

export default {
  middleware: 'guest',

	components: {
		Resend,
	},

  metaInfo () {
    return { title: this.$t('verify_email') }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  created () {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email
    }
  },

  methods: {
    send () {
      this.form.post('/email/resend').then(({ data }) => {
        this.status = data.status
        this.form.reset()
      })
    }
  }
}
</script>
