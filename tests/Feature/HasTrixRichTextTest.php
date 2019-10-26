<?php

namespace Te7aHoudini\LaravelTrix\Tests\Feature;

use Illuminate\Support\Str;
use Te7aHoudini\LaravelTrix\Tests\TestCase;
use Te7aHoudini\LaravelTrix\Tests\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Te7aHoudini\LaravelTrix\Models\TrixAttachment;

class HasTrixRichTextTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_rich_text()
    {
        $post = Post::create([
            'post-trixFields' => [
                'content' => 'foo',
                'description' => 'bar',
            ],
        ]);

        $this->assertTrue(
            Str::contains($post->trix('content')->__toString(), "value='foo' name='post-trixFields[content]' type='hidden'")
        );
        $this->assertTrue(
            Str::contains($post->trix('description')->__toString(), "value='bar' name='post-trixFields[description]' type='hidden'")
        );
    }

    /** @test */
    public function it_can_store_attachment()
    {
        TrixAttachment::create([
            'field' => 'content',
            'attachable_type' => Post::class,
            'attachment' => 'randomImage.jpg',
            'disk' => 'randomDisk',
        ]);

        $post = Post::create([
            'post-trixFields' => [
                'content' => 'foo',
            ],

            'attachment-post-trixFields' => [
                'content' => [
                    'randomImage.jpg',
                ],
            ],
        ]);
        
        $this->assertTrue(
            Str::contains($post->trix('content')->__toString(), "value='[\"randomImage.jpg\"]' name='attachment-post-trixFields[content]' type='hidden'")
        );

        $this->assertFalse((boolean) TrixAttachment::first()->is_pending);
    }
}
