<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_users()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
    
        User::factory()->count(3)->create(['role' => 'user']);
    
        $response = $this->actingAs($adminUser)->get('/api/users/'.$adminUser->id.'/admin');
    
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['name', 'email'],
            ]);
    }    

    public function test_can_get_user_by_id()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User created successfully']);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        $userData = [
            'name' => 'new Name',
            'password' => 'new password',
        ];

        $response = $this->put('/api/users/' . $user->id, $userData);

        $response->assertStatus(200);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

}

