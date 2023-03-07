<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function test_user_can_view_task_list()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        
        $response = $this->get(route('api.tasks.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_create_a_task_in_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        $todo = $this->pushTodo($user);
        
        $response = $this->post(route('api.tasks.store'), [
            'user_id' => $user->id,
            'todo_id' => $todo->id,
            'name' => 'Test Task in Test Todo',
            'status' => true,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_update_a_task_of_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        $todo = $this->pushTodo($user);

        $task = Task::create([
            'user_id' => $user->id,
            'todo_id' => $todo->id,
            'name' => 'Test Task in Test Todo',
            'status' => true,
        ]);

        $response = $this->patch(route('api.tasks.update', $task->id), [
            'name' => 'Test Task in Test Todo Update',
            'status' => 1,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_delete_a_task_of_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        $todo = $this->pushTodo($user);

        $task = Task::create([
            'user_id' => $user->id,
            'todo_id' => $todo->id,
            'name' => 'Test Task in Test Todo',
            'status' => true,
        ]);

        $response = $this->delete(route('api.tasks.destroy', $task->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_view_todo_of_a_task()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        $todo = $this->pushTodo($user);

        $task = Task::create([
            'user_id' => $user->id,
            'todo_id' => $todo->id,
            'name' => 'Test Task in Test Todo',
            'status' => true,
        ]);

        $todoOfTask = $task->todo()->first();
        $todoOfTaskById = Todo::where('id', $task->todo_id)->first();

        $this->assertEquals($todoOfTask, $todoOfTaskById);
    }

    private function pushUserLogin()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = $this->faker->password(8)),
        ]);

        $responseUser = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);
        
        return $user;
    }

    private function pushTodo($user)
    {
        $todo = Todo::create([
            'user_id' => $user->id,
            'name' => 'Test todo',
            'status' => true,
        ]);
        
        return $todo;
    }
}
