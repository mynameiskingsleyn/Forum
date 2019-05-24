<template>
    <div class="alert alert-flash" :class="'alert-'+mylevel" role="alert" v-show="show" v-text="body" >

    </div>
</template>

<script>
    export default {

        props: ['message','level'],
        data() {
            return {
                body: this.message,
                show: false,
                myLevel:'success'
            }
        },
        created(){
          if(this.message){

            this.flash();
          }
          window.events.$on('flash',data => this.flash(data));
        },
        methods: {
          flash(data){
            if(data){
              var pre = data.level=='danger'? 'ERROR':'SUCCESS';
              this.body= pre+': '+data.message;
              this.updateMyLevel(data.level);
            }
            //console.log(data);
            this.show = true;
            this.hide();
          },
          hide(){
            setTimeout(() => {
              this.show = false;
            },10000);
          },
          updateMyLevel(level){
            this.myLevel = level;
          }
        },
        computed:{
          mylevel(){
            return this.myLevel;
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
