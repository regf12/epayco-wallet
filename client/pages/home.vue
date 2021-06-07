<template>
	<b-container class="bv-example-row mb-4" v-if="user">
		<b-row>

			<b-col cols="12" md="7" class="mt-3 mb-md-3">
				<p class="py-3 text-capitalize">
					{{ $t('wellcome') }} {{ user ? user.name : '' }}
				</p>
				
				<Transactions v-if="user.type=='ADMIN'" :items="transactions"/>
				<card v-else :title="$t('balance')">
					<b-row class="p-3">
						<h1 class="font-weight-bold">
							${{ parseInt(balance ? balance : '0').toFixed(2) }}
						</h1>
					</b-row>
				</card>

				<hr class="d-flex d-lg-none mt-3 mb-0"/>
			</b-col>


			<b-col cols="12" md="5" class="py-3 mt-lg-3">

				<b-row class="p-3">

					<template v-for="(item,key) in options">
						<b-col v-if="user.type ? user.type==item.user : item.user!='ADMIN'" :key="key">
							<router-link :to="{ name: item.path }" class="nav-link d-flex align-items-center flex-nowrap" active-class="active">
								<b-button pill :variant="item.color" class="button-circle mr-2">
									<fa :icon="item.icon" class="d-flex m-auto" fixed-width />
								</b-button>
								{{$t(item.text)}}
							</router-link>
						</b-col>
					</template>

				</b-row>

				<hr class=""/>

				<History :items="history" />
			</b-col>
		
		</b-row>
	</b-container>
</template>

<script>
import { mapGetters } from 'vuex'
import History from '../components/History.vue'
import Transactions from '../components/Transactions.vue'
export default {
  components: { History, Transactions },
  
	middleware: 'auth',
  
	head () {
    return { 
			title: this.$t('home')
		}
  },

	async asyncData({ store, app }) {
		await store.dispatch('getProfile');
	},

  data () {
    return {
			options: [
				{ path: 'operations.pay', color: 'primary', icon: 'money-bill-wave', text: 'Pay', user: 'USER'},
				{ path: 'operations.recharge', color: 'success', icon: 'cash-register', text: 'Recharge', user: 'USER'},
				{ path: 'operations.search', color: 'success', icon: 'search', text: 'Search', user: 'ADMIN'}
			]
		}
  },

  computed: mapGetters({
    user: 'auth/user',
    balance: 'balance',
    history: 'history',
    transactions: 'transactions'
  }),

	created (){
		
	}
}
</script>

<style lang="scss">

table{
	// display: block;
	max-width: 100%;
	thead{
		// display: block;
		max-width: 100%;
		th{
			border-top: none !important;
		}
	}
	tbody{
		// display: block;
		max-width: 100%;
		tr{
			// display: block;
			max-width: 100%;
		}
	}
}

.button-circle{
	padding: 12px 10px !important;
}

</style>