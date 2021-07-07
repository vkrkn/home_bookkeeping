import moment from "moment";

export const operations = {
    namespaced: true,

    state: {
        all: []
    },
    getters: {
        getOperationsData: state => {
            if (Array.isArray(state.all)) {
                return state.all.reverse();
            } else {
                return [];
            }
        },
        getOperationsCount: state => {
            if (Array.isArray(state.all)) {
                return state.all.length;
            }
            return 0;
        }
    },
    mutations: {
        setOperations: (state, operations) => {
            state.all = operations;
        }
    },
    actions: {
        getOperations: (context) => {
            axios
                .get(`api/v1/operations`)
                .then(response => {
                    context.commit('setOperations', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getOperationsByData: (context, dateObj) => {
            if (!dateObj.toDate) {
                dateObj.toDate = moment(new Date()).format('YYYY-MM-DD')
            }
            axios
                .get(`api/v1/operations?fromDate=${dateObj.fromDate}&toDate=${dateObj.toDate}`)
                .then(response => {
                    context.commit('setOperations', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        delOperation: (context, operation_id) => {
            axios
                .delete(`api/v1/operations/${operation_id}`)
                .then(response => {
                    context.dispatch('getOperations');
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }
}
