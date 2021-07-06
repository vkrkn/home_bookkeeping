<script>
import { Pie } from 'vue-chartjs';
export default {
    name: "StatisticComponent",
    extends: Pie,
    props: ['debit', 'credit'],
    mounted() {
        this.renderChart({
            labels: ['Доход', 'Расход'],
            datasets: [
                {
                    label: 'Статистика за месяц',
                    backgroundColor: [
                        '#43C691',
                        '#146252',
                    ],
                    data: [this.credit, this.debit],
                    borderWidth: 0,
                    color: '#fff'
                }
            ]
        }, {
            legend: {
                labels: {
                    fontColor: "white",
                    fontSize: 14
                }
            },
            responsive: true,
            maintainAspectRatio: false
        })
    }
}

</script>

<style>
</style>
