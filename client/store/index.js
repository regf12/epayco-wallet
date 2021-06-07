import Cookies from 'js-cookie'
import axios from 'axios'
import { cookieFromRequest } from '~/utils'

// state
export const state = () => ({
  balance: '0',
  history: [],
  transactions: [],
})

// getters
export const getters = {
  balance: state => state.balance,
  history: state => state.history,
  transactions: state => state.transactions
}

export const actions = {
  nuxtServerInit ({ commit }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = cookieFromRequest(req, 'locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }
  },

  nuxtClientInit ({ commit, getters }) {
    const token = Cookies.get('token')
    if (token && !getters['auth/token']) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = Cookies.get('locale')
    if (locale && !getters['lang/locale']) {
      commit('lang/SET_LOCALE', { locale })
    }
  },
	
  async getProfile ({ commit, getters }) {
		try {
      let resonse = await axios.get(`/profile`)
			commit('SET_PROFILE', resonse.data.data)
		} catch (e) { }
  }
}

// mutations
export const mutations = {
  SET_PROFILE (state, data) {
    state.balance = data && data.balance ? data.balance : '0'
    state.history = data && data.history ? data.history : []
    state.transactions = data && data.transactions ? data.transactions : []
  }
}
