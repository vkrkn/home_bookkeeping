<template>
    <div id="statistic-page">
        <div class="buttons">
            <a href='#' @click="$router.go(-1)">
                <font-awesome-icon :icon="['fas', 'times']" size="3x"/>
            </a>
        </div>
        <main>
            <div class="month">
                <h2>Статистика за месяц</h2>
                <p>Операций: {{ Number(statistics.month.quantity).toLocaleString() }}</p>
                <p>На сумму: {{ Number(statistics.month.total).toLocaleString() }}</p>
                <p>Расход: {{ Number(statistics.month.debit).toLocaleString() }}</p>
                <p>Доход: {{ Number(statistics.month.credit).toLocaleString() }}</p>
                <statistic-component :credit="Number(statistics.month.credit)"
                                     :debit="Number(statistics.month.debit)"></statistic-component>
            </div>
            <div class="weeks">
                <h2>Статистика за неделю</h2>
                <p>Операций: {{ Number(statistics.week.quantity).toLocaleString() }}</p>
                <p>На сумму: {{ Number(statistics.week.total).toLocaleString() }}</p>
                <p>Расход: {{ Number(statistics.week.debit).toLocaleString() }}</p>
                <p>Доход: {{ Number(statistics.week.credit).toLocaleString() }}</p>
                <statistic-component :credit="Number(statistics.week.credit)"
                                     :debit="Number(statistics.week.debit)"></statistic-component>
            </div>
        </main>
    </div>
</template>

<script>
import StatisticComponent from "../components/StatisticComponent";

export default {
    name: "StatisticsPage",
    components: {StatisticComponent},
    data() {
        return {
            statistics: {

            }
        }
    },
    created() {
        axios.get('api/v1/operations/statistics')
        .then(response => {
            this.statistics = response.data;
        })
        .catch(error => {

        })
    }
}
</script>

<style scoped>
#statistic-page {

}

#statistic-page > .buttons {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    margin: 10px;
}

#statistic-page > .buttons > a {
    color: #43C691;
}

#statistic-page > main {
    display: flex;

}

#statistic-page > main > * {
    width: 50%;
    min-height: 500px;
    padding: 20px;
}

#statistic-page > main > .month {
    border-right: 1px solid #43C691;
}

h2 {
    margin-bottom: 20px;
}
</style>
