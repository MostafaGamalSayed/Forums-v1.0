<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\Spam;

class SpamsTest extends TestCase
{
    /** @test */
    public function it_detects_invalid_keywords()
    {
      // Given we have a body of a reply without spam and another with spam
      $bodyWithoutSpam = 'A reply with no spam';
      $bodyWithSpam = 'yahoo customer support';

      $spam = new Spam();

      $this->assertFalse($spam->detect($bodyWithoutSpam));

      $this->expectException('\Exception');

      $spam->detect($bodyWithSpam);

    }


    /** @test */
    public function it_detects_key_held_down_spams()
    {
      $body = 'aaaaaa';

      $spam = new spam();

      $this->expectException('Exception');

      $spam->detect($body);
    }
}
