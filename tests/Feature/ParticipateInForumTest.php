<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test*/
    public function an_auth_user_may_participate_in_forum_threads()
    {
        $this->be($user = factory('App\User')->create());

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post('/threads' .$thread->id . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test*/
    public function unauth_users_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthentificationException');

        $this->post('/threads/1/replies', []);
    }
}
