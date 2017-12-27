<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test*/
    public function an_auth_user_can_create_new_forum_threads()
    {
//        $this->actingAs(create('App\User'));
        $this->signIn();

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /** @test*/
    public function guests_may_not_create_new_forum_threads()
    {
        $this->expectException('Illuminate\Auth\AuthentificationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }
}
