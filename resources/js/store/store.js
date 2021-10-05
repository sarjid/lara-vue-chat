import Axios from "axios"

export default {
        state:{
            userList:[],
            userMessage:[]
         },
        mutations: {
            userList(state,payload){
               return state.userList = payload
            },

            userMessage(state,payload){
                return state.userMessage = payload;
            }
        },
        actions: {
            //get user
            userList(context){
                Axios.get('/userlist')
                .then(response=>{
                    context.commit("userList",response.data);
                })

                .catch(err=>{

                })

            },

            //userMessage .. payload pass korar karon hocche userId data pass kora
            userMessage(context,payload){
                Axios.get('/usermessage/'+payload)
                .then(response=>{
                    context.commit('userMessage',response.data);
                })
            }
         },
        getters: {
            userList(state){
               return state.userList
            },
            userMessage(state){
                return state.userMessage
            }
         }
}
