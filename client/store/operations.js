import axios from 'axios'
import { cookieFromRequest } from '~/utils'

// state
export const state = () => ({
})

// getters
export const getters = {
}

export const actions = {
  async checkTransaction ({ commit }, {transaction}) {
    try {
      let resonse = await axios.post(`/operations/${transaction.method}`, transaction )
			commit('SET_PROFILE', resonse.data.data, {root:true})
		} catch (e) { }
  },
}

// mutations
export const mutations = {
  
}
