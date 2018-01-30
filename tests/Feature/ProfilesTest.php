<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->title);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_associated_user()
    {
        $user = create('App\User');

        $user = create('App\Thread', ['user_id' => $user->id]);

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->title)
            ->assertSee($user->body);
    }
}