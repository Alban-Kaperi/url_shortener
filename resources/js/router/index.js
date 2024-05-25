import { createRouter, createWebHistory } from "vue-router";
import HomeRoute from "../pages/Home.vue";

const routes = [
    {
        path: "/",
        component: HomeRoute,
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
