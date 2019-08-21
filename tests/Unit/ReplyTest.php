<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    protected $reply;


    public function setUp(){
        parent::setUp();

        $this->reply = create_factory('App\Reply');
    }

    /** @test */
    public function a_reply_has_owner()
    {
        $this->assertInstanceOf(User::class, $this->reply->owner);
    }


    /** @test */
    public function a_reply_belongs_to_a_thread()
    {
        $this->assertInstanceOf(Thread::class, $this->reply->thread);
    }


    /** @test */
    public function a_reply_has_many_favorites()
    {
        $this->signIn();

        $this->reply->favorites()->create([
            'user_id' => auth()->id(),
            'favoritable_type' => get_class($this->reply)
        ]);
        //$this->assertInstanceOf(Collection::class, $this->reply->favorites);
        $this->assertCount(1, $this->reply->favorites);
    }


    /** @test  */
    public function it_knows_if_it_was_just_published()
    {
      // Given we have a created reply
      // I expect to return true if i check whether the reply was just published
      $this->assertTrue($this->reply->wasJustPublished());

      // If i update the created time of the reply to be a month ago
      $this->reply->created_at = Carbon::now()->subMonth();

      // Then i expect return true false i check whether the reply was just published
      $this->assertFalse($this->reply->wasJustPublished());
    }


}
