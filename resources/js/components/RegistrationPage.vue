<template>
    <main class="form-register">
        <p v-if="success_registration" class="text-center">Здравствуйте, {{ getName }}! <br> Мы настраиваем окружение
            для вас.
        </p>
        <form v-else class="form-enter" @submit.prevent="onRegister">
            <h1 class="text-center h3 mb-3 fw-normal">Регистрация</h1>
            <div class="mb-3">
                <label class="d-none" for="name">Email</label>
                <input id="name" v-model="registration_form.name" class="form-control" placeholder="Введите имя"
                       type="text"
                       @click="error_message = false">
            </div>
            <div class="mb-3">
                <label class="d-none" for="email">Email</label>
                <input id="email" v-model="registration_form.email" class="form-control" placeholder="Введите email"
                       type="email"
                       @click="error_message = false">
            </div>
            <div class="mb-3">
                <label class="d-none" for="password">Пароль</label>
                <input id="password" v-model="registration_form.password" class="form-control"
                       placeholder="Введите пароль"
                       type="password"
                       @click="error_message = false">
            </div>
            <div class="mb-3">
                <label class="d-none" for="password_confirmation">Подтверждение пароля</label>
                <input id="password_confirmation" v-model="registration_form.password_confirmation" class="form-control"
                       placeholder="Введите подтверждение пароля"
                       type="password"
                       @click="error_message = false">
            </div>
            <div v-if="error_message" class="alert alert-danger" role="alert">
                {{ error_message }}
            </div>
            <button class="mt-2 w-100 btn btn-lg btn-outline-light" type="submit">Зарегистрироваться</button>
            <div class="link-to-login">
                <p>Для авторизации пройдите по <router-link :to="{name: 'login'}">ссылке</router-link></p>
            </div>
        </form>
    </main>
</template>

<script>
export default {
    name: "RegistrationPage",
    data() {
        return {
            registration_form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            error_message: '',
            success_registration: false,
        }
    },
    computed: {
        getName() {
            return ''
        }
    },
    methods: {
        onRegister() {
            axios
                .post('api/registration', this.registration_form)
                .then(response => {
                    this.$store.dispatch('auth/login', response.data['user']);
                    this.$cookies.set('X-LOGIN-TOKEN', response.data['user']['api_token'])
                    window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data['user']['api_token']}`
                    this.success_login = true;
                    this.goToHome()
                })
                .catch(error => {
                    this.error_message = "Ошибка регистрации. Проверьте вводимые данные";
                })
        },
        goToHome() {
            setTimeout(() => {
                this.$router.push({name: 'index'})
            }, 0)
        }

    }
}
</script>

<style scoped>

main {
    margin-left: -15px;
    margin-right: -15px;
    color: white;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
}


@keyframes gradient {
    0% {
        background-position: 0 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0 50%;
    }
}

.link-to-login {
    margin-top: 20px;
    text-align: center;
}

.link-to-login a {
    color: white;
    font-weight: bold;
}

.form-register {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-register > form.form-enter {
    width: 300px;
}

.form-register > p {
    font-size: 40px;
}


</style>
