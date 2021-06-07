<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
        <Verify />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Verify from '~/components/auth/verification/Verify'

const qs = params => Object.keys(params).map(key => `${key}=${params[key]}`).join('&')

export default {
  middleware: 'guest',

	components: {
		Verify,
	},

  metaInfo () {
    return { title: this.$t('verify_email') }
  },

  async asyncData ({ params, query }) {
    try {
      const { data } = await axios.post(`/email/verify/${params.id}?${qs(query)}`)

      return { success: true, status: data.status }
    } catch (e) {
      return { success: false, status: e.response.data.status }
    }
  }
}
</script>
