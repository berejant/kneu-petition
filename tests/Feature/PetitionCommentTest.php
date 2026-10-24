<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;
use Kneu\Petition\User;
use Tests\TestCase;

class PetitionCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment(): void
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();

        $this->actingAs($user)
            ->post(route('petitions.comments.store', $petition), ['content' => 'Підтримую петицію'])
            ->assertOk();

        $comment = PetitionComment::sole();
        $this->assertSame($petition->id, $comment->petition_id);
        $this->assertSame($user->id, $comment->user_id);

        $this->get(route('petitions.show', $petition))->assertSee('Підтримую петицію');
    }

    public function test_cannot_comment_closed_petition(): void
    {
        $petition = Petition::factory()->closed()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('petitions.comments.store', $petition), ['content' => 'Підтримую петицію'])
            ->assertForbidden();
    }

    public function test_only_author_can_update_comment(): void
    {
        $comment = PetitionComment::factory()->create();
        $url = route('petitions.comments.update', [$comment->petition, $comment]);
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        $this->actingAs(User::factory()->create())
            ->put($url, ['content' => 'Чужий коментар'], $headers)
            ->assertForbidden();

        $this->actingAs($comment->user)
            ->put($url, ['content' => 'Виправлений коментар'], $headers)
            ->assertOk()
            ->assertJson(['status' => true]);

        $this->assertSame('Виправлений коментар', $comment->fresh()->content);

        $this->travel(config('petition.comment_minutes_for_edit') + 1)->minutes();

        $this->actingAs($comment->user)
            ->put($url, ['content' => 'Запізно виправлений'], $headers)
            ->assertForbidden();
    }

    public function test_author_can_delete_comment(): void
    {
        $comment = PetitionComment::factory()->create();

        $this->actingAs($comment->user)
            ->delete(route('petitions.comments.destroy', [$comment->petition, $comment]))
            ->assertRedirect(route('petitions.show', $comment->petition));

        $this->assertSoftDeleted($comment);
    }
}
