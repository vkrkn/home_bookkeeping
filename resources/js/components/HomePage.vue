<template>
    <div id="home-page">
        <div class="buttons">
            <div class="select-operations-by-data">
                <form @submit.prevent="getOperationsByData(dateObj)">
                    <span>от</span>
                    <input type="date" v-model="dateObj.fromDate" required>
                    &nbsp;
                    <span>до</span>
                    <input type="date" v-model="dateObj.toDate">
                    &nbsp;
                    <button class="btn btn-warning">Показать</button>
                </form>
            </div>

            <div>
                <router-link tag="button" :to="{name: 'statistics'}" class="btn btn-outline-warning">Статистика</router-link>
                <router-link tag="button" :to="{name: 'operation.create'}" class="btn btn-warning">Добавить</router-link>
            </div>
        </div>

        <table v-if="getOperationsCount !== 0" class="table table-dark">
            <thead>
            <tr>
                <th scope="col">Тип</th>
                <th scope="col">Категория</th>
                <th scope="col">Дата</th>
                <th scope="col">Сумма</th>
                <th scope="col">Итого</th>
                <th scope="col">Комментарий</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="operation in operations" :key="operation.id">
                <td>{{operation.category.type_name}}</td>
                <td>{{operation.category.name}}</td>
                <td>{{operation.date}}</td>
                <td>{{Number(operation.sum).toLocaleString()}}</td>
                <td>{{Number(operation.balance).toLocaleString()}}</td>
                <td>{{operation.comment}}</td>
                <td>
                <router-link :to="{name: 'operation.update', params: {id: operation.id}}" title="Изменить">
                    <font-awesome-icon :icon="['fas', 'pen']" size="1x"/>
                </router-link>
                    &nbsp;
                <a href='#' @click="delOperation(operation.id)" style="color: red" title="Удалить">
                    <font-awesome-icon :icon="['fas', 'times']" size="1x"/>
                </a>
                </td>
            </tr>
            </tbody>
        </table>
        <p v-else style="margin-top: 30px">Упс, ваших данных пока нет! Но вы можете добавить их</p>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
    name: "HomePage",
    data() {
        return {
            dateObj: {
                fromDate: '',
                toDate: ''
            }
        }
    },
    computed: {
        ...mapGetters({
            operations: 'operations/getOperationsData',
            getOperationsCount: 'operations/getOperationsCount'
        })
    },
    created() {
        console.log();
        this.$store.dispatch('categories/initCategories');
        this.$store.dispatch('operations/getOperations');
    },
    methods: {
        ...mapActions({
            delOperation: 'operations/delOperation',
            getOperationsByData: 'operations/getOperationsByData'
        }),
    }
}
</script>

<style scoped>
#home-page {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
#home-page > table, #home-page > div.buttons {
    margin: 10px 0;
}
#home-page > div.buttons {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
#home-page > div.buttons > div.paginate {
    display: flex;
    align-items: flex-end;
}
#home-page > div.buttons > div.paginate > * {
    margin-right: 5px;
}
a {
    color: #ffed4a;
}
</style>
