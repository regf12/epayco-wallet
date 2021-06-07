<template>
  <card :title="$t('search')">
    <form @submit.prevent="search" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t(message)" />

      <!-- Document -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('document') }}</label>
        <div class="col-md-7">
          <input v-model="form.document" :class="{ 'is-invalid': form.errors.has('document') }" type="text" name="document" required placeholder="12345678" class="form-control">
          <has-error :form="form" field="document" />
        </div>
      </div>

			<!-- Phone -->
			<div class="form-group row">
				<label class="col-md-3 col-form-label text-md-right">{{ $t('phone') }}</label>
				<div class="col-md-7">
					<input v-model="form.phone" :class="{ 'is-invalid': form.errors.has('phone') }" type="number" name="phone" required placeholder="04120878578" class="form-control">
					<has-error :form="form" field="phone" />
				</div>
			</div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t('search') }}
          </v-button>
        </div>
      </div>

    </form>

		<hr/>

		<div v-if="user">
			<div>
				<b-card :title="user.name" :sub-title="user.email">
					<b-card-text>
						{{ user.wallet && user.wallet.mount ? user.wallet.mount : 0 }}
					</b-card-text>
				</b-card>
			</div>
		</div>

  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  middleware: 'admin',
	
  scrollToTop: false,

	props:{
		
	},

  data: () => ({
    form: new Form({
      document: '',
      phone: ''
    }),
		user: null,
		message: null
  }),

  computed: mapGetters({
  }),

  created () {
    // Fill the form with user data.
    /* this.form.keys().forEach((key) => {
      this.form[key] = this.user[key]
    }) */
  },

  methods: {
    reset () {
      this.form.document = ''
      this.form.phone = ''
		},
    async search () {
			this.user = null
			try {
        const response = await this.form.patch('/operations/search')
        let data = response.data
				this.message = data.message
				
				if (data.data) {
					this.user = data.data
				}

				// Clean form
				this.reset()
      
				// Redirect home.
				// this.$router.push({ name: 'home' })

			} catch (e) {
        return
      }
    }
  }
}
</script>
