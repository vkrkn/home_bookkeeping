export const categories = {
    namespaced: true,
    state: {
        all: [],
        necessary: [],
        types: [],
    },
    getters: {
        get_types: state => {
            return state.types;
        },
        get_all: state => {
            return state.all;
        },
        get_necessary: state => {
            return state.necessary;
        }
    },
    mutations: {
        set_categories: (state, categories) => {
            state.all = categories;
        },
        set_necessary: (state, categories) => {
            state.necessary = categories;
        },
        set_types: (state, types) => {
            state.types = types;
        },

    },
    actions: {
        init_categories: ({commit}) => {
            axios
                .get('api/v1/categories')
                .then(response => {
                    commit('set_categories', response.data['category']);
                    commit('set_types', response.data['category_types']);
                })
                .catch(error => {

                })
        },
        init_necessary: (context, type) => {
            let result = context.state.all.filter(category => {
                return category.type === type;
            })
            context.commit('set_necessary', result);
        },

    }
}
