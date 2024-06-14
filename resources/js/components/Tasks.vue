<template>
  <!-- Use Bootstrap container for better alignment and responsiveness -->
  <div class="container my-4">
    <div class="row">
      <div class="col">
        <!-- Draggable list -->
        <draggable v-model="tasks" @end="onEnd" item-key="id" class="list-group">
          <template #item="{element}">
            <div
                class="list-group-item d-flex justify-content-between align-items-center"
                :key="element.id"
            >
              {{ element.name }}
              <div>
                <button class="btn btn-primary btn-sm ml-2" @click="editTask(element)">Edit</button>
              </div>
              <div>
                <button class="btn btn-danger btn-sm ml-2" @click="deleteTask(element.id)">Delete</button>
              </div>
            </div>
          </template>
        </draggable>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <!-- Input for new task -->
        <input
            type="text"
            v-model="newTask"
            placeholder="New Task Name"
            class="form-control"
        >
      </div>
      <div class="col-md-6">
        <button class="btn btn-success" @click="createTask">Add Task</button>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import draggable from 'vuedraggable';
import axios from 'axios';

export default defineComponent({
  components: {
    draggable,
  },
  data() {
    return {
      tasks: [],
      newTask: '', // For the new task input
    };
  },
  created() {
    this.fetchTasks();
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get('/api/tasks');
        this.tasks = response.data;
      } catch (error) {
        console.error("Failed to fetch tasks:", error);
      }
    },
    async onEnd() {
      let order = this.tasks.map((task, index) => ({
        id: task.id,
        priority: index + 1
      }));

      try {
        await axios.post('/api/tasks/reorder', { order });
      } catch (error) {
        console.error("Failed to update task order:", error);
      }
    },
    async createTask() {
      if (!this.newTask.trim()) return;
      try {
        const response = await axios.post('/api/tasks/create', { name: this.newTask, priority: this.tasks.length + 1 });
        this.tasks.push(response.data);
        this.newTask = '';
      } catch (error) {
        console.error("Failed to create task:", error);
      }
    },
    async editTask(task) {
      const newName = prompt("Edit Task Name", task.name);
      if (newName !== null && newName.trim() !== '') {
        try {
          const response = await axios.put('/api/tasks/update', { id: task.id, name: newName, priority: task.priority });
          const index = this.tasks.findIndex(t => t.id === task.id);
          this.tasks[index] = response.data;
        } catch (error) {
          console.error("Failed to edit task:", error);
        }
      }
    },
    async deleteTask(id) {
      if (!confirm("Are you sure you want to delete this task?")) return;
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter(t => t.id !== id);
      } catch (error) {
        console.error("Failed to delete task:", error);
      }
    },
  },
});
</script>
