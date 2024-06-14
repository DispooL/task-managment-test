import './bootstrap';
import { createApp } from 'vue'
import Tasks from "./components/Tasks.vue";

const app = createApp()

app.component('tasks', Tasks)

app.mount('#app')
