<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function test_user_can_register()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password(8);

        $response = $this->post(route('api.register'), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $user = User::where('email', $email)->first();
        $this->assertEquals($name, $user->name);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /** @test */
    public function test_user_can_not_register_with_existing_email()
    {
        $user = User::factory()->create();

        $response = $this->post(route('api.register'), [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => $this->faker->password(8),
            'password_confirmation' => $this->faker->password(8),
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = $this->faker->password(8)),
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_user_can_not_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($this->faker->password(8)),
        ]);

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->post(route('api.logout'));

        $this->assertGuest();
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $response = $this->get(route('api.user'));
        
        $this->assertAuthenticatedAs($user);
    }
}
