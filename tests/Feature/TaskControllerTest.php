<?php

declare(strict_types=1);

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        // Create tasks out of order to verify they are returned in order
        $task1 = Task::create(['name' => 'Task 3', 'priority' => 3]);
        $task2 = Task::create(['name' => 'Task 1', 'priority' => 1]);
        $task3 = Task::create(['name' => 'Task 2', 'priority' => 2]);

        $response = $this->json('GET', '/api/tasks');

        $response->assertOk();

        // Decode the JSON response to an array to check order
        $tasks = $response->json();

        // Assert tasks are returned in the correct order
        $this->assertEquals($task2->name, $tasks[0]['name']);
        $this->assertEquals($task3->name, $tasks[1]['name']);
        $this->assertEquals($task1->name, $tasks[2]['name']);

        $response->assertJsonStructure([
            '*' => ['id', 'name', 'priority', 'created_at', 'updated_at'], // Adjust based on your actual structure
        ]);
    }

    public function testStoreMethod()
    {
        $response = $this->json('POST', '/api/tasks/create', [
            'name' => 'Sample Task',
            'priority' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => 'Sample Task',
                'priority' => 1,
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Sample Task',
            'priority' => 1,
        ]);
    }

    public function testUpdateMethod()
    {
        // Create a task to update
        $task = Task::create([
            'name' => 'Old Task',
            'priority' => 2,
        ]);

        $response = $this->json('PUT', '/api/tasks/update', [
            'id' => $task->id, // Include task ID in the payload, as the route doesn't specify {id}
            'name' => 'Updated Task',
            'priority' => 2,
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'name' => 'Updated Task',
                'priority' => 2,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task',
        ]);
    }

    public function testDestroyMethod()
    {
        $task = Task::create([
            'name' => 'Task to Delete',
            'priority' => 3,
        ]);

        $response = $this->json('DELETE', "/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testReorderMethod()
    {
        $taskOne = Task::create(['name' => 'Task One', 'priority' => 1]);
        $taskTwo = Task::create(['name' => 'Task Two', 'priority' => 2]);

        $response = $this->json('POST', '/api/tasks/reorder', [
            'order' => [
                ['id' => $taskOne->id, 'priority' => 2],
                ['id' => $taskTwo->id, 'priority' => 1],
            ]
        ]);

        // Assuming your endpoint returns a success message on successful reorder
        $response->assertOk()->assertJson(['message' => 'Tasks reordered successfully']);

        // Validate the database has the expected values after the reorder operation
        $this->assertDatabaseHas('tasks', ['id' => $taskOne->id, 'priority' => 2]);
        $this->assertDatabaseHas('tasks', ['id' => $taskTwo->id, 'priority' => 1]);
    }
}
