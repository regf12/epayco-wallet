<template>
  <card :title="$t('pay')">
    <form @submit.prevent="pay" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t(message)" />

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{
          $t("email")
        }}</label>
        <div class="col-md-7">
          <input
						required
            v-model="form.email"
            :class="{ 'is-invalid': form.errors.has('email') }"
            type="email"
            name="email"
            placeholder="mail@mail.com"
            class="form-control"
          />
          <has-error :form="form" field="email" />
        </div>
      </div>

      <!-- Mount -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{
          $t("mount")
        }}</label>
        <div class="col-md-7">
          <input
						required
            v-model="form.mount"
            :class="{ 'is-invalid': form.errors.has('mount') }"
            type="number"
            min="0"
            name="mount"
            class="form-control"
          />
          <has-error :form="form" field="mount" />
        </div>
      </div>

      <!-- Code -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{
          $t("request_code")
        }}</label>
        <div class="col-md-7">
          <div class="input-group mb-3">
            <div class="input-group-prepend cursor-pointer" @click="sendCode">
              <span class="input-group-text" id="basic-addon1">
                <fa icon="mail-bulk" fixed-width />
              </span>
            </div>
            <input
							required
              v-model="form.code"
              :class="{ 'is-invalid': form.errors.has('code') }"
              type="text"
              name="code"
              placeholder="@#$123"
              class="form-control"
            />
          </div>
          <has-error :form="form" field="code" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t("pay") }}
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  middleware: 'user',

  scrollToTop: false,

	props:{
	},

  computed: mapGetters({
  }),

  data: () => ({
    form: new Form({
			email: '',
      mount: 0,
      code: ''
    }),
		message: null
  }),

  methods: {
		reset () {
      this.form.email = ''
      this.form.mount = 0
      this.form.code = ''
		},
    async pay () {
      try {
        const response = await this.form.post('/operations/pay')
        let data = response.data
				this.message = data.message

				// Clean form
				this.reset()
      
				// Redirect home.
				// this.$router.push({ name: 'home' })

			} catch (e) {
        return
      }
    },
    async sendCode () {
      const response = await this.form.post('/settings/code')
			let data = response.data
			this.message = data.message
    }
  }
}
</script>

<style lang="scss">
.cursor-pointer {
  cursor: pointer;
}
</style>