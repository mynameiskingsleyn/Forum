<template>
<div v-if="signedIn">
  <div class="form-group">
      <label for="body">Detail</label>
      <textarea name="body" id="body" class="form-control" v-model="body"></textarea>
  </div>
  <button @click="addReply" class="btn btn-success" value="">Submit Reply</button>
</div>

</template>

<script>
  import 'at.js';
  import 'jquery.caret'
  export default{
    props:['thread'],
    data (){
      return{
        body:'',
        threadId: this.thread.id,
        threadSlug:this.thread.slug,
      };

    },
    methods:{
      addReply(){
        if(this.body.length > 5){
          axios.post(this.endpoint,{body: this.body})
            .catch(error => {
              //console.log(error.response.data);
              var errors = error.response.data.body;
              var message = errors.join(',');
              console.log(message);
              flash(message, 'danger');
            })
            .then(({data})=>{
              this.body ='';
              flash('Your reply has been Posted');
              this.$emit('created',data);
              // response=>{
              //   this.body='';
              //   flash('Your reply has been posted succesfully');
              //   this.$emit('created',response.data);
                });
        }else{
          this.body="Error!! please enter more more than 5 leters!!";
        }

      }
    },
    computed:{
      endpoint:{
        cache:false,
        get: function(){
          console.log(this.threadSlug);
          return '/threads/'+this.threadSlug+'/replies';
        }
      },
      signedIn(){
        return window.App.signedIn;
      }
    },
    mounted(){
      $('#body').atwho({
        at: "@",
        delay:750,
        callbacks:{
          remoteFilter: function(query, callback){
            //axios.get()
            $.getJSON("/api/users",{name:query},function(usernames){
              callback(usernames)
            });

          }
        }
      });
    }

  }

</script>
