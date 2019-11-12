<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_upload_avatar()
    {
        $this->withExceptionHandling();

        $this->json('POST', 'users/1/avatar')
          ->assertstatus(401);
    }


    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
      $this->signIn();

      $this->expectException('Illuminate\Validation\ValidationException');

      $this->json('POST', 'users/' . auth()->id() . '/avatar', [
          'avatar' =>  'not-valid-image'
      ]);
    }


    /** @test */
    public function another_method_to_test_that_a_valid_avatar_must_be_provided()
    {
      $this->withExceptionHandling()->signIn();

      $this->json('POST', 'users/' . auth()->id() . '/avatar', [
          'avatar' =>  'not-valid-image'
      ])->assertstatus(422);  // 422 => un processable entity
    }


    /** @test */
    public function a_user_may_add_avatar_to_his_profile()
    {
      $this->signIn();

      // make a fake disk in [ storage > framework > testing > disks ]
      Storage::fake('public');

      $this->json('POST', 'users/' . auth()->id() . '/avatar', [
          'avatar' =>  $file = UploadedFile::fake()->image('avatar.jpg')
      ]);

      Storage::disk('public')->assertExists('avatars/' . $file->hashName());

      $this->assertEquals(auth()->user()->avatar_path, 'avatars/' . $file->hashName());
    }



}
