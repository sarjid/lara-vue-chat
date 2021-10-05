import Vue from 'vue'
import moment from 'moment'
Vue.filter('timeformat',function(arg){
   return moment(arg).format('LTS')
})

Vue.filter('dayformat',function(arg){
    return moment(arg).format('dddd')
 })
