<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kneu\Petition\Socialite\KneuProvider;
use Kneu\Petition\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.kneu' => [
            'client_id' => 'test-client',
            'client_secret' => 'test-secret',
            'redirect' => 'http://localhost/login/complete',
        ]]);
    }

    public function test_login_redirects_to_kneu(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://auth.kneu.edu.ua/oauth?', $location);
        $this->assertStringContainsString('client_id=test-client', $location);
        $this->assertStringContainsString('redirect_uri=' . urlencode('http://localhost/login/complete'), $location);
    }

    public function test_login_complete_creates_and_authenticates_user(): void
    {
        $raw = [
            'id' => 123,
            'email' => 'student@kneu.edu.ua',
            'first_name' => 'Тарас',
            'middle_name' => 'Григорович',
            'last_name' => 'Шевченко',
            'type' => 'student',
            'student_id' => 456,
        ];

        $provider = Mockery::mock(KneuProvider::class);
        $provider->shouldReceive('user')->andReturn((new SocialiteUser())->setRaw($raw)->map($raw));
        Socialite::shouldReceive('with')->with('kneu')->andReturn($provider);

        $this->get('/login/complete')
            ->assertRedirect('/')
            ->assertSessionHas('userName', 'Тарас Шевченко');

        $user = User::findOrFail(123);
        $this->assertSame('Шевченко', $user->last_name);
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_redirects_to_kneu(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post('/logout');

        $this->assertGuest();
        $this->assertStringStartsWith(
            'https://auth.kneu.edu.ua/oauth/logout?client_id=test-client',
            $response->headers->get('Location')
        );
    }
}
