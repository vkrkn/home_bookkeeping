<template>
    <div id="create_operation">
        <div class="row head">
            <h1>Создать категорию</h1>
            <a href='#' @click="$router.go(-1)">
                <font-awesome-icon :icon="['fas', 'times']" size="3x"/>
            </a>
        </div>
        <form @submit.prevent="create">
            <div class="form-group">
                <label for="type">Тип</label>
                <select id="type" v-model.lazy="category_form.type" class="form-control" required>
                    <option disabled value="">Выберите тип</option>
                    <option v-for="type in getTypes" :key="type.id" :value="type.id">
                        {{ type.value }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="category-input">Введите название категории</label>
                <input id="category-input"
                       v-model="category_form.name"
                       class="form-control"
                       placeholder="Название категории"
                       required
                       type="text">
            </div>
            <br>
            <button class="btn btn-warning btn-lg btn-block" type="submit">Создать</button>
        </form>
    </div>
</template>

<script>

export default {
    name: "CreateCategoryPage",
    data() {
        return {
            category_form: {
                type: '',
                name: ''
            },
        }
    },

    computed: {
        getTypes() {
            return this.$store.getters["categories/get_types"];
        },
    },
    methods: {
        routerBack: () => {
            this.$router.go(-1)
        },

        create: function () {
            axios
                .post('api/v1/categories', this.category_form)
                .then(response => {
                    this.$store.dispatch('categories/init_categories')
                    this.$router.go(-1)
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
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


