<template>
    <div id="create_operation">
        <div class="row head">
            <h1>Создать операцию</h1>
            <a href='#' @click="$router.go(-1)">
                <font-awesome-icon :icon="['fas', 'times']" size="3x"/>
            </a>
        </div>
        <form @submit.prevent="createOperation">
            <div class="form-group">
                <label for="type">Тип</label>
                <select id="type" v-model.lazy="operation.type" @change="setCategoryByType" class="form-control" required>
                    <option disabled value="">Выберите тип операции</option>
                    <option v-for="type in getTypes" :value="type.id" :key="type.id">
                        {{type.value}}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Категория</label>
                <span class="text-info">
                    (<router-link :to="{name: 'category.create'}">Вы можете создать свою <b>категорию</b></router-link>)
                </span>
                <select v-model="operation.category_id" id="category" class="form-control" required>
                    <option disabled value="">Выберите категорию операции</option>
                    <option v-for="category in getCategories" :value="category.id" :key="category.id">
                        {{category.name}}
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="date">Дата</label><br>
                <DatePicker v-model="datePickerDate" @input="dateFormat" id="date" required></DatePicker>
            </div>

            <div class="form-group">
                <label for="sum">Сумма</label>
                <input v-model.number="operation.sum" id="sum" class="form-control" type="number" required>
            </div>

            <div class="form-group">
                <label for="comment">Комментарий</label>
                <textarea v-model="operation.comment" name="comment" id="comment" rows="3" class="form-control"></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-warning btn-lg btn-block">Создать</button>
        </form>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment';

export default {
    name: "CreateOperationsPage",
    components: {DatePicker},
    data() {
        return {
            datePickerDate: new Date(),
            operation: {
                type: '',
                category_id: '',
                date: moment(new Date()).format('YYYY-MM-DD'),
                sum: '',
                comment: ''
            },
        }
    },

    computed: {
        getTypes() {
            return this.$store.getters["categories/get_types"];
        },
        getCategories() {
            return this.$store.getters["categories/get_necessary"];
        }
    },
    methods: {
        dateFormat() {
            this.operation.date = moment(this.datePickerDate).format('YYYY-MM-DD');
        },
        routerBack: () => {
            this.$router.go(-1)
        },
        setCategoryByType: function () {
            this.$store.dispatch('categories/init_necessary', this.operation.type)
        },
        createOperation: function () {
            axios
            .post('api/v1/operations', this.operation)
            .then(response => {
                this.$router.go(-1)
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    watch: {

    }
}
</script>

<style scoped>
#create_operation {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

#create_operation > form {
    max-width: 500px;
    min-width: 500px;
}

.head {
    padding: 15px 0;
    width: 500px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

.head svg {
    color: #fde300;
}
</style>
