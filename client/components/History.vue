<template>
  <card :title="$t('movement_history')" class="">
		<div>
			<b-table stacked="sm" hover :fields="fields" :items="items" >

				<template #cell(name)="row" v-if="user.type=='ADMIN'">
					{{ row.item.name }}
				</template>
				<template #cell(type)="row" v-else>
					{{ $t(row.item.type.toLowerCase()) }}
				</template>

				<template #cell(updated_at)="row">
					{{ formatDate(row.item.updated_at) }}
				</template>

				<template #cell(status)="row">
					{{ $t(row.item.status.toLowerCase()) }}
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
				{ key: 'updated_at', label: this.$t('date') },
				this.user.type=='ADMIN' ? { key: 'name', label: this.$t('user') }: { key: 'type', label: this.$t('type') },
				{ key: 'mount', label: this.$t('mount') },
				{ key: 'status', label: this.$t('status') }
			]
		}
	},

  methods: {
		formatDate (date){
			let newDate = (date!=null&&date!='')?date.split("T")[0]/* .join("/") */:'';
			return moment(newDate,'YYYY-MM-DD').format('DD/MM/YY');
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
