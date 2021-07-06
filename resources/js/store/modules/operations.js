export const operations = {
    namespaced: true,

    state: {
        all: []
    },
    getters: {
        getOperationsData: state => {
            return state.all.data;
        },
        getOperationsCount: state => {
            if (state.all.hasOwnProperty('data') && Array.isArray(state.all.data)) {
                return state.all.data.length;
            } else {
                return 0;
            }
        },
        getCurrentPage: state => {
            return state.all.current_page;
        },
        getLastPage: state => {
            return state.all.last_page;
        },
    },
    mutations: {
        setOperationsPaginate: (state, operations_paginate) => {
            state.all = operations_paginate;
        }
    },
    actions: {
        getOperations: (context, page = 1) => {
            axios
                .get(`api/v1/operations?page=${page}`)
                .then(response => {
                    context.commit('setOperationsPaginate', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        NextOperations: ({dispatch, state}) => {
            dispatch('getOperations', Number(state.all.current_page) + 1);
        },
        PreviousOperations: ({dispatch, state}) => {
            dispatch('getOperations', Number(state.all.current_page) - 1);
        },

        delOperation: (context, operation_id) => {
            axios
                .delete(`api/v1/operations/${operation_id}`)
                .then(response => {
                    context.dispatch('getOperations', context.state.all.current_page);
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
