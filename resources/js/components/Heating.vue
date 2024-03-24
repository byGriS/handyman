<template>
    <div>
        <div class="row">
            <div class="col-md-3 text-center vAlign">Список шкафов нагрева</div>
            <div class="col-md-3">
                <select class="form-control" v-model="selectedHeater">
                    <option
                        v-for="heater in heaters"
                        :key="heater.id"
                        :value="heater"
                        >{{ heater.title }}</option
                    >
                </select>
            </div>
            <div class="col-md-6">
            </div>
        </div>
        <div class="marginT15" v-if="selectedHeater != null">
          <div class="row">
            <div class="col-2"><b>Состояние</b></div>
            <div class="col-2"><b>Фаза A</b></div>
            <div class="col-2"><b>Фаза B</b></div>
            <div class="col-2"><b>Фаза C</b></div>
            <div class="col-4"><b>Дата обновления</b></div>
          </div>
          
          <div class="row" v-for="elem in selectedHeater.lastParams" :key="elem.id">
            <div class="col-2">{{elem.onoff ? "Вкл" : "Выкл"}}</div>
            <div class="col-2">{{elem.phaseA}}</div>
            <div class="col-2">{{elem.phaseB}}</div>
            <div class="col-2">{{elem.phaseC}}</div>
            <div class="col-4">{{elem.last}}</div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            heaters: [],
            selectedHeater: null
        };
    },
    computed: {},
    watch: {},
    methods: {
        getListHeaters() {
            this.$http
                .get(this.$store.state.host + "api/getHeaters", {
                    params: {
                        api_token: this.$store.state.userApi
                    }
                })
                .then(function(response) {
                    this.heaters = response.data;
                });
        }
    },
    created() {
        this.getListHeaters();
    }
};
</script>

<style scoped></style>>

