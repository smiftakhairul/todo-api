<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function test_user_can_view_todo_list()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        
        $response = $this->get(route('api.todos.index'));

        $response->assertStatus(200);
    }
    
    /** @test */
    public function test_user_can_create_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);
        
        $response = $this->post(route('api.todos.store'), [
            'user_id' => $user->id,
            'name' => 'Test todo',
            'status' => true,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_update_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);

        $todo = Todo::create([
            'user_id' => $user->id,
            'name' => 'Test todo',
            'status' => true,
        ]);

        $response = $this->patch(route('api.todos.update', $todo->id), [
            'name' => 'Test todo Update',
            'status' => 1,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_delete_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);

        $todo = Todo::create([
            'user_id' => $user->id,
            'name' => 'Test todo',
            'status' => true,
        ]);

        $response = $this->delete(route('api.todos.destroy', $todo->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_view_all_tasks_of_a_todo()
    {
        $user = $this->pushUserLogin();
        $this->assertAuthenticatedAs($user);

        $todo = Todo::create([
            'user_id' => $user->id,
            'name' => 'Test todo',
            'status' => true,
        ]);

        Task::create([
            'user_id' => $user->id,
            'todo_id' => $todo->id,
            'name' => 'Test Task in Test Todo',
            'status' => 1
        ]);

        $tasks = $todo->tasks()->get();
        $tasksOfTodo = Task::where('todo_id', $todo->id)->get();

        $this->assertEquals($tasks, $tasksOfTodo);
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
}
