<template>
<div class="container-chat clearfix">
    <div class="people-list" id="people-list">
      <div class="search">
        <input type="text" placeholder="search" />
        <i class="fa fa-search"></i>
      </div>
      <ul class="list">
        <li class="clearfix" @click.prevent="selectUser(user.id)" v-for="user in userList" :key="user.id">
          <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01.jpg" alt="avatar" />
          <div class="about">
            <div class="name">{{ user.name }}</div>
            <div class="status">
              <i class="fa fa-circle online"></i> online
            </div>
          </div>
        </li>

      </ul>
    </div>

    <div class="chat" v-if="userMessage.user">
      <div class="chat-header clearfix">
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />

        <div class="chat-about">
          <div class="chat-with" v-if="userMessage.user">{{ userMessage.user.name }} <i class="fa fa-circle online"></i></div>
          <div class="chat-num-messages">Active Now </div>
        </div>
              <i class="fa fa-cog" >
                    <ul class="nav nav-tabs">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"  aria-haspopup="true" aria-expanded="false">..</a>
                            <div class="dropdown-menu">
                                <a @click.prevent="deleteAllMessage" class="dropdown-item" href="#">Delete All</a>
                            </div>
                        </li>
                    </ul>
            </i>
      </div> <!-- end chat-header -->

      <div class="chat-history" v-chat-scroll>
        <ul>
          <li class="clearfix" v-for="message in userMessage.messages" :key="message.id">
            <div class="message-data" v-if="message.user.id == userMessage.user.id">
              <span class="message-data-name"> {{message.user.name}}</span>

              <span class="message-data-time">{{ message.created_at | timeformat }}, {{ message.created_at | dayformat }}</span>
              <!-- delete message  -->

              <ul class="nav nav-tabs">
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">...</a>
                     <div class="dropdown-menu">
                         <a @click.prevent="deleteSingleMessage(message.id)" class="dropdown-item" href="#">Delete</a>
                     </div>
                 </li>
             </ul>

            </div>

            <div class="message-data align-right mt-2" v-else>
            <ul class="nav nav-tabs">
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">...</a>
                     <div class="dropdown-menu">
                         <a class="dropdown-item" @click.prevent="deleteSingleMessage(message.id)" href="#">Delete</a>
                     </div>
                 </li>
             </ul>

              <span class="message-data-time" >{{ message.created_at | timeformat }}, {{ message.created_at | dayformat }}</span> &nbsp; &nbsp;
              <span class="message-data-name" v-if="message.user.from == userMessage.user.id">{{message.user.name}}</span>
              <span class="message-data-name">me</span>
               <!-- delete message  -->

            </div>

            <div :class="`message ${message.user.id == userMessage.user.id ? 'my-message':'other-message float-right'} `">
                {{ message.message }}
            </div>
          </li>


          <!-- <li>
            <div class="message-data">
              <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
              <span class="message-data-time">10:12 AM, Today</span>
            </div>
            <div class="message my-message">
              Are we meeting today? Project has been already finished and I have results to show you.
            </div>
          </li> -->
        </ul>

      </div> <!-- end chat-history -->

      <div class="chat-message clearfix">
        <textarea @keydown.enter="sendMessage" v-model="message" name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="3"></textarea>

        <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
        <i class="fa fa-file-image-o"></i>

        <button>Send</button>

      </div> <!-- end chat-message -->

    </div> <!-- end chat -->

</div> <!-- end container -->
</template>

<script>
export default {
    mounted(){
          this.$store.dispatch('userList');
    },

    data() {
        return {
            message:'',
        }
    },

    computed:{
        userList(){
            return this.$store.getters.userList;
        },
        userMessage(){
            return this.$store.getters.userMessage
        }
    },

    created(){

    },
    methods: {
        //select user and request to get user messages
        selectUser(userId){
           this.$store.dispatch('userMessage',userId);
        },
        //send message
        sendMessage(e){
            e.preventDefault();
            if (this.message !='') {
                axios.post('/sendmessage',{message:this.message,user_id:this.userMessage.user.id})
                .then((response) => {
                    this.selectUser(this.userMessage.user.id);
                }).catch((err) => {

                });
                this.message = '';
            }
        },
        //deletesingle message

        deleteSingleMessage(msgID){
            axios.get('/deletesingle-message/'+msgID)
            .then((result) => {
                this.selectUser(this.userMessage.user.id)
            }).catch((err) => {

            });
        },
        // deleteall message
        deleteAllMessage(){
            axios.get('deleteall-message/'+this.userMessage.user.id)
            .then((result) => {
                this.selectUser(this.userMessage.user.id)
            }).catch((err) => {

            });
        }

    },
}
</script>

<style>

</style>
