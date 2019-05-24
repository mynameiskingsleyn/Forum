<template>
  <div class="">
    <div class="level">
      <img :src="avatar" alt="No Image for user" width="100" height="100" class="mr-2">
      <h1 v-text="user.name"></h1>
    </div>

    <small>member since  <span v-text="user.created_at"></span>" </small><br>
    <form v-if="canUpdate" class="" action="" method="post" enctype="multipart/form-data">
      <image-upload name="avatar" @fileloaded="onLoad"></image-upload>
      <!-- <input type="file" name="avatar" value="" accept="image/*" @change="onChange"> -->
    </form>




  </div>
</template>

<script>
  import ImageUpload from './ImageUpload.vue';
  export default {
      props:['user'],
      components: { ImageUpload },
      data(){
        return {
          avatar: this.user.avatar_path
        };
      },
      computed:{
        canUpdate() {
          return this.authorize(user => user.id === this.user.id)
        }
      },
      methods:{
        onLoad(data){
          let avatar = data.file;
          this.persist(avatar);
          this.avatar=data.src;

        },
        persist(avatar) {
          let data  = new FormData();
          data.append('avatar',avatar);
          axios.post(`/api/users/${this.user.name}/avatar`,data)
              .then(()=> flash('Avatar uploaded!'));
        }
      }
  }
</script>

<style lang="css">
</style>
