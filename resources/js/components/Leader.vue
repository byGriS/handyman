<template>
  <div>Руководитель</div>
</template>

<script>
export default {
  mounted(){
    this.$store.state.userRole = 2;
    this.$store.state.userApi = $('meta[name="api-token"]').attr("content");
  }
}
</script>