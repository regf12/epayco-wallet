<template>
  <form @submit.prevent="register" @keydown="form.onKeydown($event)">
		<!-- Name -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
			<div class="col-md-7">
				<input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" type="text" name="name"  placeholder="Jhon Doe" class="form-control">
				<has-error :form="form" field="name" />
			</div>
		</div>

		<!-- Email -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
			<div class="col-md-7">
				<input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email"  placeholder="mail@mail.com" class="form-control">
				<has-error :form="form" field="email" />
			</div>
		</div>

		<!-- Document -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('document') }}</label>
			<div class="col-md-7">
				<input v-model="form.document" :class="{ 'is-invalid': form.errors.has('document') }" type="text" name="document" placeholder="12345678" class="form-control">
				<has-error :form="form" field="document" />
			</div>
		</div>

		<!-- Phone -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('phone') }}</label>
			<div class="col-md-7">
				<input v-model="form.phone" :class="{ 'is-invalid': form.errors.has('phone') }" type="number" name="phone" placeholder="04120878578" class="form-control">
				<has-error :form="form" field="phone" />
			</div>
		</div>

		<!-- Password -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
			<div class="col-md-7">
				<input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password" name="password" placeholder="******" class="form-control">
				<has-error :form="form" field="password" />
			</div>
		</div>

		<!-- Password Confirmation -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
			<div class="col-md-7">
				<input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" type="password" name="password_confirmation" placeholder="******" class="form-control" >
				<has-error :form="form" field="password_confirmation" />
			</div>
		</div>

		<div class="form-group row">
			<div class="col-md-7 offset-md-3 d-flex">
				<!-- Submit Button -->
				<v-button :loading="form.busy">
					{{ $t('register') }}
				</v-button>

				<!-- GitHub Login Button -->
				<login-with-github />
			</div>
		</div>
	</form>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      document: '',
      phone: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  methods: {
    async register () {
      let data

      // Register the user.
      try {
        const response = await this.form.post('/register')
        data = response.data
				
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

      } catch (e) {
        return
      }

      // Must verify email fist.
    }
  }
}
</script>
