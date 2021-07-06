export const auth = {
    namespaced: true,
    state: {
        login: {email: '', name: '', api_token: ''},
        error: '',
    },
    getters: {
        get_login: state => {
            return state.login
        }
    },
    mutations: {
        set_login: (state, loginData) => {
            state.login = loginData;
        },
        set_error: (state, error) => {
            state.error = error;
        },
    },
    actions: {
        login(context, loginData) {
            context.commit('set_login', loginData)
        },
    }
}
