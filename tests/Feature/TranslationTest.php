<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and get a token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('TestToken')->plainTextToken;
    }

    /** @test */
    public function it_creates_a_translation()
    {
        $data = [
            'locale' => 'en',
            'key' => 'greeting',
            'content' => 'Hello World',
            'context' => 'mobile',
            'tags' => ['web', 'mobile']
        ];

        $response = $this->postJson('/api/translations', $data, [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Translation created successfully',
                     'data' => $data,
                     'code' => 201
                 ]);
    }

    /** @test */
    public function it_fetches_translations()
    {
        Translation::factory()->count(5)->create();

        $response = $this->getJson('/api/translations', [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_updates_a_translation()
    {
        $translation = Translation::factory()->create();

        $updateData = [
            'content' => 'Updated Translation',
            'tags' => ['updated', 'web']
        ];

        $response = $this->putJson("/api/translations/{$translation->id}", $updateData, [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Translation updated successfully',
                     'data' => [
                         'id' => $translation->id,
                         'content' => 'Updated Translation'
                     ]
                 ]);

        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'content' => 'Updated Translation'
        ]);
    }

    /** @test */
    public function it_deletes_a_translation()
    {
        $translation = Translation::factory()->create();

        $response = $this->deleteJson("/api/translations/{$translation->id}", [], [
            'Authorization' => 'Bearer ' . $this->token
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Translation deleted successfully'
                 ]);

        $this->assertDatabaseMissing('translations', [
            'id' => $translation->id
        ]);
    }
}
