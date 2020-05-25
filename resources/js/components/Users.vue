<template>
  <div>
    <button class="btn btn-primary" @click="addUserForm = true;">Добавить пользователя</button>
    <div v-if="addUserForm">
      <div class="row addUserForm">
        <div class="col-md-3">
          ФИО
          <br />
          <input class="form-control" v-model="newUser.name" />
        </div>
        <div class="col-md-2">
          Логин (телефон)
          <br />
          <input class="form-control" v-model="newUser.phone" />
        </div>
        <div class="col-md-2">
          Пароль
          <br />
          <input class="form-control" v-model="newUser.password" />
        </div>
        <div class="col-md-2">
          Права доступа
          <br />
          <select class="form-control" v-model="newUser.role">
            <option value="1">Администратор</option>
            <option value="2">Руководитель</option>
            <option value="3">Исполнитель</option>
          </select>
        </div>
        <div class="col-md-2 text-center">
          <br />
          <button class="btn btn-primary userBtn" @click="addUser()">Добавить</button>
        </div>
      </div>
    </div>
    <h3>Список пользователей</h3>
    <div class="userRow" v-for="user in users" :key="user.id">
      <div class="row">
        <div class="col-md-4">{{user.name}}</div>
        <div class="col-md-2 text-center">{{user.phone}}</div>
        <div class="col-md-2 text-center">{{user.access}}</div>
        <div class="col-md-2 text-center">
          <button class="btn btn-info userBtn" @click="resetPassForm(user)">Сменить пароль</button>
        </div>
        <div class="col-md-2 text-center">
          <button class="btn btn-danger userBtn" @click="deleteUser(user)">Удалить</button>
        </div>
      </div>
      <div class="resetPassForm form-inline" v-if="user.resetPassForm">
        <label>Новый пароль</label>
        <input class="form-control" v-model="user.newPass" />
        <button class="btn btn-info userBtn" @click="resetPass(user)">Сменить</button>
        <button class="btn btn-secondary userBtn" @click="cancelResetPassForm(user)">Отмена</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      addUserForm: false,
      newUser: {
        name: "",
        phone: "",
        password: "",
        role: 3
      }
    };
  },
  methods: {
    getListUsers() {
      this.$http
        .get(this.$store.state.host + "api/getlistusers", {
          params: {
            api_token: this.$store.state.userApi
          }
        })
        .then(function(response) {
          response.data.forEach(elem => {
            elem.resetPassForm = false;
            elem.newPass = "";
          });
          this.users = response.data;
        });
    },
    resetPassForm(user) {
      user.resetPassForm = true;
    },
    cancelResetPassForm(user) {
      user.resetPassForm = false;
      user.newPass = "";
    },
    resetPass(user) {
      this.$http
        .post(this.$store.state.host + "api/resetPass", {
          api_token: this.$store.state.userApi,
          id: user.id,
          newPass: user.newPass
        })
        .then(function(response) {
          user.resetPassForm = false;
          user.newPass = "";
        });
    },
    deleteUser(user) {
      if (confirm("Удалить пользователя " + user.name + "?")) {
        this.$http
          .post(this.$store.state.host + "api/deleteUser", {
            api_token: this.$store.state.userApi,
            id: user.id
          })
          .then(function(response) {
            this.getListUsers();
          });
      }
    },
    addUser() {
      this.$http
        .post(this.$store.state.host + "api/addUser", {
          api_token: this.$store.state.userApi,
          newUser: this.newUser
        })
        .then(function(response) {
          console.log(response);
          if (response.data == "2")
            alert("Пользователь с логином " + this.newUser.phone + " уже существует");
          else {
            this.getListUsers();
            this.addUserForm = false;
            this.newUser.name = "";
            this.newUser.phone = "";
            this.newUser.password = "";
            this.newUser.role = 3;
          }
        });
    }
  },
  created() {
    this.getListUsers();
  }
};
</script>

<style>
.userRow {
  margin: 5px 0;
  padding: 5px 0;
  background: rgb(246, 245, 245);
  border: 1px solid rgb(233, 233, 233);
  border-radius: 3px;
}
.userRow div {
  margin: auto;
}
.userBtn {
  width: 125px !important;
  padding: 3px 0;
}
.resetPassForm {
  margin: 0px 15px !important;
}
.resetPassForm * {
  margin-right: 15px;
}
.addUserForm {
  margin: 10px;
}
.addUserForm div {
  margin: auto;
  padding: 0 !important;
}
</style>