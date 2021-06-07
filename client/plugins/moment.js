
// eslint-disable-next-line import/no-extraneous-dependencies
import Vue from 'vue';
import VueMoment from 'vue-moment';
import moment from 'moment';

require('moment/locale/es');

Vue.use(VueMoment, {
  moment, 
});
