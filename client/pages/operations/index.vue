<template>
  <div class="row" v-if="user">
    <div class="col-md-3">
      <card :title="$t('operations')" class="settings-card">
        <ul class="nav flex-column nav-pills">
					<template v-for="tab in tabs" >
						<li v-if="user.type ? user.type==tab.user : tab.user!='ADMIN'" :key="tab.route" class="nav-item">
							<router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
								<fa :icon="tab.icon" fixed-width />
								{{ tab.name }}
							</router-link>
						</li>
					</template>
        </ul>
      </card>
    </div>

    <div class="col-md-9 py-3 py-md-0">
      <transition name="fade" mode="out-in">
        <router-view :user="user" />
      </transition>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  head () {
    return { title: this.$t('operations') }
  },

  computed: {
		...mapGetters({
			user: 'auth/user'
  	}),
    tabs () {
      return [
        {
          icon: 'money-bill-wave',
          name: this.$t('pay'),
          route: 'operations.pay',
					user: 'USER'
        },
        {
          icon: 'cash-register',
          name: this.$t('recharge'),
          route: 'operations.recharge',
					user: 'USER'
        },
        {
          icon: 'search',
          name: this.$t('search'),
          route: 'operations.search',
					user: 'ADMIN'
        }
      ]
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
