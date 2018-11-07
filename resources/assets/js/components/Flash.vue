<template>
    <div class="alert alert-flash" :class="'alert-'+level" role="alert" v-show="show" v-text="body">

    </div>
</template>

<script>
    export default {

        props: ['message'],
        data() {
            return {
                body: this.message,
                show: false,
                level:'success'
            }
        },
        created(){
          if(this.message){

            this.flash(this);
          }
          window.events.$on('flash',data => this.flash(data));
        },
        methods: {
          flash(data){
            var pre = data.level=='danger'? 'ERROR:: ':'SUCCESS::';
            this.show = true;
            this.body= pre+':'+data.message;
            this.level=data.level;
            this.hide();
          },
          hide(){
            setTimeout(() => {
              this.show = false;
            },5000);
          }
        }
    }
</script>

<style>
  .alert-flash{
    position: fixed;
    right: 25px;
    bottom:20px;
  }

</style>
