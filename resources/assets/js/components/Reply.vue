<template>

    <div :id="'reply_' +id "class="panel" :class="isBest ? 'panel-success':'panel-default'">
        <div class="panel-heading" style="padding-bottom:20px;">
          <div class="level">
          <div class="flex">
            Created : <span v-text="ago"></span>
            By: <a href="/profiles/this.data.owner.name" v-text="data.owner.name"></a>
            <span class="likes"> </span>
          </div>
            <span class="level">

                <div class="" v-if="authorize('updateReply',reply)" >
                  <span style="float:right; margin-bottom:20px;">
                    <button type="button" name="button" class="btn btn-xs btn-danger" @click="destroy">Delete Reply</button>
                    <button type="button" name="button" class="btn btn-xs" @click="editing=true">Edit Reply</button>
                  </span>

                </div>



            </span>

          </div>


        </div>
        <div class="panel-body">

        <div class="level">

          <div class="flex" v-if="editing">
            <form @submit="update">
              <div class="form-group">
                <textarea name="reply" rows="8" class="form-control" required
                v-model="body" id="body">

                </textarea>
              </div>
              <button  class="btn btn-xs btn-link"> Update </button>
              <button  class="btn btn-xs btn-link" @click="editing=false" type="button"> Cancel </button>
            </form>

          </div>

          <div class="flex" v-else v-html="body">
          </div>

          <div class="" v-if="signedIn">
              <favorite :reply="data"> </favorite>
          </div>
          <div v-if="authorize('canMarkBest',reply)"class="bestResponse">
            <button class="btn btn-xs btn-success ml-a"  @click="markBestReply" v-if="! isBest">Best Reply</button>
          </div>

        </div>

            <br>

        </div>
    </div>


</template>
<script>
  import Favorite from './Favorite.vue';
  import moment from 'moment';

  export default {

    props: ['data'],
    data(){
      return{
        editing:false,
        body:this.data.body,
        id: this.data.id,
        reply:this.data,
        best:this.data.isBest//this.data.thread.best_reply_id == this.data.id
        //isBest: this.isBest? this.isBest :false//this.data.isBest
      };
    },
    components :{
      Favorite
    },
    methods:{
      update(){
        console.log('update on stuff');
        if(this.body.length > 8){
          axios.patch('/replies/'+this.data.id,{
            body:this.body
          })
          .catch(error =>{
            console.log(error);
             flash(error.response.data,'danger');
             console.log('there was an error on post');
          }).then(({data})=>{
            this.body ='';
            flash('your reply has been updated seccessfully','success');
            this.$emit('updated',data);
            // response=>{
            //   this.body='';
            //   flash('Your reply has been posted succesfully');
            //   this.$emit('created',response.data);
            });
        }else{
          flash('body length must be greater than 8','danger');
        }


        this.editing = this.toggle();
      },

      toggle(){
        this.editing = !this.editing;
      },
      destroy(){
          axios.delete('/replies/'+this.data.id);
        //  $(this.$el).fadeOut(300,()=>{
        //    flash('Reply deleted');
        //  });
          this.$emit('deleted',this.data.id);

      },
      markBestReply(){
        //this.isBest=true;
        axios.post('/replies/'+this.data.id+'/best');
        window.events.$emit('best-reply-selected',this.data.id);
      }

    },
    computed: {
      canUpdate(){
        return this.authorize(user=> this.data.id == user.id);
        //return window.App.user.id == this.data.owner.id;
      },
      ago(){
        return moment(this.data.created_at).fromNow();
      },
      isBest(){
        return this.best;
      }
    },
    created() {
      window.events.$on('best-reply-selected',id=>{
        this.best = (id === this.data.id);
      });
    }


  }

</script>
