<template lang="html">
    <input type="file" value="" accept="image/*" @change="onChange">
</template>

<script>
export default {

    methods: {
      onChange(e){
        if(! e.target.files.length) return;
        let file= e.target.files[0];
        console.log(e.target.result);
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = e => {
          let src = e.target.result;
          this.$emit('fileloaded', {src,file});
        };
        // Persist to the server.
        //this.persist(avatar);
      },
    }
}
</script>

<style lang="css">
</style>
