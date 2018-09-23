<template>

    <div :id="'reply_' +id "class="panel panel-default">
        <div class="panel-heading" style="padding-bottom:20px;">
          <div class="level">
            <div class="flex">
              Created edit: {{ data.created_at }}
              By: <a href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a>
              <span class="likes"> </span>
            </div>
            <span class="level">


                <div class="">
                  <span style="float:right; margin-bottom:20px;">
                    <button type="button" name="button" class="btn btn-xs btn-danger" @click="destroy">Delete Reply</button>
                    <button type="button" name="button" class="btn btn-xs" @click="editing=true">Edit Reply</button>
                    <!-- <a href="/reply/{{$reply->id}}/edit/" class="btn btn-xs">Edit Reply</a> -->
                  </span>

                </div>



            </span>

          </div>


        </div>
        <div class="panel-body">

          <div class="level">
            <div class="flex" v-if="editing">
              <div class="form-group">
                <textarea name="name" rows="8" class="form-control" v-model="body">

                </textarea>
              </div>
              <button type="button" name="button" class="btn btn-xs btn-link" @click="update"> Update </button>
              <button type="button" name="button" class="btn btn-xs btn-link" @click="editing=false"> Cancel </button>
            </div>
            <div class="flex" v-else v-text="body">
            </div>

              <div class="" v-if="signedIn">
                <favorite :reply="data"> </favorite>
              </div>


          </div>

            <br>

        </div>
    </div>


</template>
<script>
  import Favorite from './Favorite.vue';
  export default {

    props: ['data'],
    data(){
      return{
        editing:false,
        body:this.data.body,
        id: this.data.id
      };
    },
    components :{
      Favorite
    },
    mounted:{

    },
    methods:{
      update(){
        axios.patch('/replies/'+this.data.id,{
          body:this.body
        });

        this.editing = this.toggle();
        flash('Updated');
      },

      toggle(){
        this.editing = !this.editing;
      },
      computed: {
        signedIn(){
          return window.App.signedIn;
        }
      },

      destroy(){
          axios.delete('/replies/'+this.data.id);
        //  $(this.$el).fadeOut(300,()=>{
        //    flash('Reply deleted');
        //  });
          this.$emit('deleted',this.data.id);

      }

    }

  }

</script>
