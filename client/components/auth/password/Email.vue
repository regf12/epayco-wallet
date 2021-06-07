<template>
  <form @submit.prevent="send" @keydown="form.onKeydown($event)">
		<alert-success :form="form" :message="status" />

		<!-- Email -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
			<div class="col-md-7">
				<input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email" class="form-control">
				<has-error :form="form" field="email" />
			</div>
		</div>

		<!-- Remember Me -->
		<div class="form-group row">
			<div class="col-md-3" />
			<div class="col-md-7 d-flex">
				<router-link :to="{ name: 'welcome' }" class="small ml-auto my-auto">
					{{ $t('login') }}
				</router-link>
			</div>
		</div>

		<!-- Submit Button -->
		<div class="form-group row">
			<div class="col-md-9 ml-md-auto">
				<v-button :loading="form.busy">
					{{ $t('send_password_reset_link') }}
				</v-button>
			</div>
		</div>
	</form>
</template>

<script>
import Form from 'vform'

export default {
  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

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
