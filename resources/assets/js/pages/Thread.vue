<script>
  import Replies from '../components/Replies.vue';
  import SubscribeButton from '../components/SubscribeButton';

  export default {
    props:['initialRepliesCount'],
    components:{
      Replies,SubscribeButton
    },
    data (){
      return {
        repliesCount:this.initialRepliesCount,
        repliesCount1:5
      };
    },
    methods:{

    },
    watch:{
      repliesCount(){
        console.log('replies counter has changed'.this.repliesCounts);
      }
    }

  }

</script>
