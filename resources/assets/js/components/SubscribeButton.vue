<template>
  <button type="button" :class="classes" name="button" @click ="subscribe" v-text="subscribeButton">
    Subscribe
  </button>
</template>

<script>
  export default {
    props:['active'],
    data(){
      return{
        isactive: this.active

      }
    },

    computed:{
      classes() {
        return ['btn', this.isactive ? 'btn-primary' : 'btn-default'];
      },
      subscribeButton(){
        return this.isactive ? 'UnSubscribe' : 'Subsribe';
      }
    },
    methods:{
      subscribe(){
            let requestType = this.isactive ? 'delete' : 'post';
            axios[requestType](location.pathname+'/subscribe');
            this.isactive = !this.isactive;
      }

    }
  }
</script>
