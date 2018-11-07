<template>
<div v-if="signedIn">
  <div class="form-group">
      <label for="body">Detailskk</label>
      <textarea name="body" id="body" class="form-control" v-model="body"></textarea>
  </div>
  <button @click="addReply" class="btn btn-success" value="">Submit Reply</button>
</div>

</template>

<script>
  export default{
    props:['thread_id'],
    data (){
      return{
        body:'',
        threadId: this.thread_id,
      };

    },
    methods:{
      addReply(){
        if(this.body.length > 5){
          axios.post(this.endpoint,{body: this.body})
            .catch(error => {
              //console.log(error.response.data);
              if(typeof error.response.data === 'Array'){
                console.log('this is an object yal');
              }

              flash(error.response.data, 'danger');
            })
            .then(({data})=>{
              this.body ='';
              flash('Your reply has been Posted');
              this.emit('created',data);
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
          return '/threads/'+this.thread_id+'/replies';
        }
      },
      signedIn(){
        return window.App.signedIn;
      }
    }

  }

</script>
