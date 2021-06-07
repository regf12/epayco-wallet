<template>
  <card :title="$t('pending_transactions')" class="">
		<div>
			<b-table stacked="sm" hover :fields="fields" :items="items" >

				<template #cell(created_at)="row">
					{{ formatDate(row.item.created_at) }}
				</template>

				<template #cell(actions)="row">
					<b-button size="sm" @click="checkTransaction({...row.item, method: 'acept'})" class="mr-2" :title="$t('acept')">
						<fa icon="check" class="d-flex m-auto" fixed-width />
					</b-button>
					<b-button size="sm" @click="checkTransaction({...row.item, method: 'reject'})" class="mr-2" :title="$t('reject')">
						<fa icon="times" class="d-flex m-auto" fixed-width />
					</b-button>
				</template>

			</b-table>
		</div>
	</card>
</template>

<script>
import { mapGetters } from 'vuex'
import moment from 'moment';

export default {
  components: {
  },

	props:{
		items: { required: true, type: Array, default: [] },
	},

  data: () => ({
    /* items: [
			{ date: '06/04/21', user: 'Dickerson', mount: 10.22 },
			{ date: '12/03/21', user: 'Larsen', mount: -54.22 },
			{ date: '08/02/21', user: 'Geneva', mount: 20.22 },
			{ date: '03/02/21', user: 'Jami', mount: -60.22 }
		] */
  }),

  computed: {
		...mapGetters({
			user: 'auth/user'
		}),
		fields (){
			return [
				{ key: 'created_at', label: this.$t('date') },
				{ key: 'name', label: this.$t('user') },
				{ key: 'mount', label: this.$t('mount') },
				{ key: 'actions', label: this.$t('actions') }
			]
		}
	},

  methods: {
		formatDate (date){
			let newDate = (date!=null&&date!='')?date.split("T")[0]/* .join("/") */:'';
			return moment(newDate,'YYYY-MM-DD').format('DD/MM/YY');
		},

    async checkTransaction (transaction) {
			await this.$store.dispatch('operations/checkTransaction', {transaction})
		},

    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
