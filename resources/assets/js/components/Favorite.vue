<template>
  <button type="submit" :class="classes" @click="toggle">
    <span class="glyphicon glyphicon-heart"></span>
    <span v-text="favoritesCount"></span>
    Favorite  shit balls
  </button>

</template>

<script>
  export default {
      props:['reply'],
      data(){
        return {
          favoritesCount : this.reply.FavoritesCount,
          isFavorited : this.reply.isFavorited
        }
      },
      methods:{
        toggle(){
          this.isFavorited?this.destroy():this.create();
        },
        create(){
            axios.post(this.endpoint);
            this.isFavorited = true;
            this.favoritesCount++;
        },
        destroy(){
            axios.delete(this.endpoint);
            this.favoritesCount--;
            this.isFavorited = false;
        }
      },
      computed: {
        classes() {
          return['btn',this.isFavorited ? 'btn-primary':'btn-default'];
        },
        endpoint(){
          return '/replies/'+this.reply.id+'/favorites';
        }
      }
  }


</script>
