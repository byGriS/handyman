<template>
  <div class="container">
    <nav class="row">
      <router-link
        :to="{name: 'works'}"
        class="adminNavItem col-sm-2"
        :class="{'adminNavSelected': this.$route.name == 'works'}"
      >Объекты</router-link>
      <router-link
        :to="{name: 'completedWorks'}"
        class="adminNavItem col-sm-3"
        :class="{'adminNavSelected': this.$route.name == 'completedWorks'}"
      >Завершенные объекты</router-link>
    </nav>
    <hr />
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  beforeMount(){
    this.$store.state.userRole = 2;
    this.$store.state.userApi = $('meta[name="api-token"]').attr("content");
  }
}
</script>


<style scoped>
.adminNavItem {
  background: rgb(246, 245, 245);
  font-size: 14pt;
  text-decoration: none;
  color: black;
  border: 1px solid rgb(219, 219, 219);
  border-radius: 0 0 5px 5px;
  padding: 8px;
  min-width: 150px;
  text-align: center;
}
.adminNavItem:hover {
  background: rgb(204, 240, 209);
  box-shadow: 0 4px 8px rgb(181, 192, 197, 0.8);
}
.adminNavSelected {
  background: rgb(176, 217, 233);
}
.row {
  margin: 0px;
}
</style>