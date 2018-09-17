<template>
  <div>
    This is reply template
    <div v-for="(reply, index) in items">
       <reply :data="reply" @deleted="remove(index)"></reply>
    </div>

  </div>

</template>


<script>
  import Reply from './Reply.vue';

  export default {

    props: ['data'],
    components: {
      Reply
    },
    data() {
      return{
        items:this.data
      }
    },
    mothods: {
        remove(index){
          this.items.splice(index, 1);
        }
    }
  }


</script>
