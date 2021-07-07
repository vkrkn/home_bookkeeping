<template>
    <div id="create_operation">
        <div class="row head">
            <h1>{{pagesTitle}}</h1>
            <a href='#' @click="$router.go(-1)">
                <font-awesome-icon :icon="['fas', 'times']" size="3x"/>
            </a>
        </div>
        <form @submit.prevent="availableMethod">
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
                <input v-model.number="operation.sum" id="sum" class="form-control" type="number" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="comment">Комментарий</label>
                <textarea v-model="operation.comment" name="comment" id="comment" rows="3" class="form-control"></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-warning btn-lg btn-block">{{buttonName}}</button>
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
            pagesTitle: '',
            buttonName: '',
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
    mounted() {
        if (this.$route.name === 'operation.create') {
            this.pagesTitle = 'Создать операцию';
            this.buttonName = 'Создать';
        } else if (this.$route.name === 'operation.update') {
            this.pagesTitle = 'Изменить операцию';
            this.buttonName = 'Изменить';
            function checkOperation(id) {
                return function (operation) {
                    return operation.id === id;
                }
            }
            let operation = this.$store.state.operations.all.find(checkOperation(this.$route.params.id));
            this.operation = operation;
            this.operation.type = operation.category.type;
            this.setCategoryByType();
            this.operation.category_id = operation.category.id;
            this.datePickerDate = new Date(operation.date);
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
            this.$store.dispatch('categories/initNecessary', this.operation.type)
        },
        createOperation: function () {
            axios
            .post('../api/v1/operations', this.operation)
            .then(response => {
                this.$router.go(-1)
            })
            .catch(error => {
                console.log(error)
            })
        },
        updateOperation: function (id) {
            axios
                .patch(`../../api/v1/operations/${id}`, this.operation)
                .then(response => {
                    this.$router.go(-1)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        availableMethod: function () {
            if (this.$route.name === 'operation.update') {
                this.updateOperation(this.$route.params.id);
            } else {
                this.createOperation();
            }

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
