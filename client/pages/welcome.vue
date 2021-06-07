<template>
  <div class="w-100 h-100">

		<b-container class="bv-example-row mt-5 pt-5">
			<b-row>
				<b-col></b-col>
				<b-col cols="12" sm="8" md="6">
					<v-row class="m-5 p-5">
						<v-col>
							<b-tabs content-class="mt-3" fill>
								<template v-for="(tab, key) in tabs">

									<b-tab :title="tab.text" :key="key" class="p-3">
										<component :is="tab.module" />
									</b-tab>
								
								</template>
							</b-tabs>
						</v-col>
					</v-row>
				</b-col>
				<b-col></b-col>
			</b-row>
		</b-container>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Login from '~/components/auth/Login'
import Register from '~/components/auth/Register'

export default {
  layout: 'simple',
  
	middleware: 'isLog',
  
	head () {
    return { 
			title: this.$t('')
		}
  },

	components: {
		Login,
		Register
	},

  data: () => ({
    title: process.env.appName,
		tabs: [
			{text: 'Login', module: 'Login'},
			{text: 'Register', module: 'Register'},
		]
  }),

  computed: mapGetters({
    authenticated: 'auth/check',
		user: 'auth/user'
  }),

	created (){
	}

}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}

.laravel {
  color: #2e495e;
}

.nuxt {
  color: #00c48d;
}
</style>
