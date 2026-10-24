<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kneu\Petition\Petition;
use Kneu\Petition\User;
use Tests\TestCase;

class PetitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_petition_list(): void
    {
        $open = Petition::factory()->create();
        $closed = Petition::factory()->closed()->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($open->title)
            ->assertDontSee($closed->title);

        $this->get(route('petitions.index'))
            ->assertOk()
            ->assertSee($open->title)
            ->assertSee($closed->title);
    }

    public function test_guest_can_see_petition(): void
    {
        $petition = Petition::factory()->create();

        $this->get(route('petitions.show', $petition))
            ->assertOk()
            ->assertSee($petition->title)
            ->assertSee($petition->user->getName());
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('petitions.create'))->assertRedirect('/login');
    }

    public function test_user_can_create_petition(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('petitions.create'))->assertOk();

        $response = $this->actingAs($user)->post(route('petitions.store'), [
            'title' => str_repeat('Заголовок ', 4),
            'content' => str_repeat('Зміст петиції. ', 10),
        ]);

        $petition = Petition::sole();
        $response->assertRedirect(route('petitions.show', $petition));
        $this->assertSame($user->id, $petition->user_id);
    }

    public function test_petition_validation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('petitions.create'))
            ->post(route('petitions.store'), ['title' => 'Коротко', 'content' => 'Коротко'])
            ->assertRedirect(route('petitions.create'))
            ->assertSessionHasErrors(['title', 'content']);

        $this->assertSame(0, Petition::count());
    }

    public function test_author_can_update_petition_only_while_editable(): void
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->for($user)->create();
        $data = [
            'title' => trim(str_repeat('Новий заголовок ', 3)),
            'content' => str_repeat('Новий зміст петиції. ', 10),
        ];

        $this->actingAs(User::factory()->create())
            ->put(route('petitions.update', $petition), $data)
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('petitions.update', $petition), $data)
            ->assertRedirect(route('petitions.show', $petition));
        $this->assertSame($data['title'], $petition->fresh()->title);

        $this->travel(config('petition.minutes_for_edit') + 1)->minutes();

        $this->actingAs($user)
            ->put(route('petitions.update', $petition), $data)
            ->assertForbidden();
    }

    public function test_admin_can_delete_any_petition(): void
    {
        $petition = Petition::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->deleteJson(route('petitions.destroy', $petition), [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => true]);

        $this->assertSoftDeleted($petition);
    }

    public function test_user_can_vote_once(): void
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();

        $this->actingAs($user)
            ->postJson(route('petitions.vote', $petition), [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['status' => true]);

        $this->actingAs($user)
            ->postJson(route('petitions.vote', $petition), [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk();

        $this->assertSame(1, $petition->fresh()->votes);
        $this->assertTrue($petition->fresh()->hasUserVoted($user->id));
    }

    public function test_guest_vote_is_unauthorized_for_ajax(): void
    {
        $petition = Petition::factory()->create();

        $this->postJson(route('petitions.vote', $petition), [], ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertUnauthorized();
    }

    public function test_cannot_vote_for_closed_petition(): void
    {
        $petition = Petition::factory()->closed()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('petitions.vote', $petition))
            ->assertStatus(400);
    }
}
