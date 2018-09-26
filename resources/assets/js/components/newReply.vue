<template>
<div>
  <div class="form-group">
      <label for="body">Details</label>
      <textarea name="body" id="body" class="form-control" v-model="body"></textarea>
  </div>
  <input @click="addReply" class="btn btn-success" value="Submit Reply">
</div>


</template>

<script>
  export default{
    data (){
      return{
        body:'';
      }

    },
    methods:{
      addReply(){
        axios.post(this.endpoint,{this.body})
          .then(
            response =>{
              this.body => '';
              flash('Your reply has been posted');
              this.$emit('created',response.data);
             });
      }
    },
    computed:{
      endpoint(){
        return '';
      }
    }

  }

</script>
