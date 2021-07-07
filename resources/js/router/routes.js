import HomePage from "../components/HomePage";
import LoginPage from "../components/LoginPage";
import RegistrationPage from "../components/RegistrationPage";
import CreateOperationsPage from "../components/CreateOperationsPage";
import CreateCategoryPage from "../components/CreateCategoryPage";
import StatisticsPage from "../components/StatisticsPage";


const routes = [
    { path: '/', component: HomePage, name: 'index', meta: {title: 'Главная'} },
    { path: '/statistics', component: StatisticsPage, name: 'statistics', meta: {title: 'Статистика'} },
    { path: '/operation/add', component: CreateOperationsPage, name: 'operation.create', meta: {title: 'Создание операции'} },
    { path: '/operation/:id/update', component: CreateOperationsPage, name: 'operation.update', meta: {title: 'Изменение операции'} },
    { path: '/category/add', component: CreateCategoryPage, name: 'category.create', meta: {title: 'Создание категории'} },
    { path: '/login', component: LoginPage, name: 'login', meta: {title: 'Авторизация'} },
    { path: '/registration', component: RegistrationPage, name: 'registration', meta: {title: 'Регистрация'} }
]

export default routes
