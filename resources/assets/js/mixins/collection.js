export default{
  data() {
    return {
      items:[]
    }
  },
  methods:{
    add(item){
      console.log('item was added');
      this.items.push(item);
      this.$emit('added');
    },
    remove(index){
      this.items.splice(index, 1);
      flash('Reply deleted');
      this.$emit('removed')
    }
  }
}
