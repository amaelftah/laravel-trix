<?php

namespace Te7aHoudini\LaravelTrix\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Te7aHoudini\LaravelTrix\Models\TrixAttachment;
use Te7aHoudini\LaravelTrix\Tests\Models\Post;
use Te7aHoudini\LaravelTrix\Tests\TestCase;

class TrixAttachmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_attachment_request()
    {
        Storage::fake('fooDisk');

        $response = $this->json('POST', route('laravel-trix.store'), [
            'file' => UploadedFile::fake()->image('foo.jpg'),
            'modelClass' => Post::class,
            'field' => 'fooField',
            'disk' => 'fooDisk',
        ]);

        $this->assertTrue(
            TrixAttachment::where('attachment', basename($response->decodeResponseJson('url')))
                ->where('is_pending', 1)
                ->exists()
        );
    }

    /** @test */
    public function it_destroy_attachment()
    {
        Storage::fake('fooDisk');

        TrixAttachment::create([
            'field' => 'content',
            'attachable_type' => Post::class,
            'attachment' => 'randomImage.jpg',
            'disk' => 'fooDisk',
        ]);

        $this->delete(route('laravel-trix.destroy', ['attachment' => 'randomImage.jpg']));

        $this->assertTrue(
            TrixAttachment::where('attachment', 'randomImage.jpg')
                ->doesntExist()
        );
    }
}
