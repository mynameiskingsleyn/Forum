<template>
  <div>
      <div v-for="(reply, index) in items" :key="reply.id">
         <reply :data="reply" @deleted="remove(index)"></reply>
      </div>
      <paginator :dataSet="dataSet" @updated="fetch"></paginator>
      <div class="" v-if="signedIn">
        <new-reply :thread="thread" @created="add"> </new-reply>
      </div>
  </div>

</template>


<script>
  import Reply from './Reply.vue';
  import newReply from './newReply.vue';
  import collection from '../mixins/collection';
  export default {
    props: ['thread'],
    components: {
      Reply, newReply
    },
    mixins:[collection],
    data() {
      return{
        dataSet:false,
        endpoint: location.pathname+'/replies'
      }
    },
    created(){
      this.fetch();
    },
    methods: {

        fetch(page){
          axios.get(this.url(page))
              .then(this.refresh);
        },
        url(page){

          if(! page){
            let query = location.search.match(/page=(\d+)/);
            page = query ? query[1]:1;
          }
        //  console.log(page);
          return location.pathname+'/replies?page='+page;
        },
        refresh({data}){
          //console.log(response['data']);
          this.dataSet = data;
          this.items = data.data;
          window.scrollTo(0,0);
        }
    },
    computed:{
      signedIn(){
        return window.App.signedIn;
      }
    }
  }


</script>
