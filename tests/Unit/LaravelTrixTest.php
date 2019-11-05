<?php

namespace Te7aHoudini\LaravelTrix\Tests\Unit;

use Illuminate\Support\Str;
use Te7aHoudini\LaravelTrix\Tests\Models\Post;
use Te7aHoudini\LaravelTrix\Tests\TestCase;

class LaravelTrixTest extends TestCase
{
    /** @test */
    public function it_returns_default_trix_html()
    {
        $expected = <<<EOT
<span id='container-post-description-1'> <style>  </style><input id='post-description-1' value='' name='post-trixFields[description]' type='hidden'><input id='attachment-post-description-1' value='[]' name='attachment-post-trixFields[description]' type='hidden'><trix-editor class='trix-content' input='post-description-1' data-config='{"id":"post-description-1","modelClass":"Te7aHoudini\\\LaravelTrix\\\Tests\\\Models\\\Post","field":"description"}'></trix-editor> </span>
EOT;

        $this->assertEquals(
            $expected,
            $this->post->trix('description')->__toString()
        );
    }

    /** @test */
    public function it_hides_toolbar()
    {
        $expected = <<<'EOT'
<style> #container-post-description-1 trix-toolbar{display:none;} </style>
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['hideToolbar' => true])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_hides_tools()
    {
        $expected = <<<'EOT'
<style> #container-post-description-1 .trix-button-group--foo,#container-post-description-1 .trix-button-group--bar{display:none;} </style>
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['hideTools' => ['foo', 'bar']])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_hides_button_icons()
    {
        $expected = <<<'EOT'
<style> #container-post-description-1 .trix-button--icon-foo,#container-post-description-1 .trix-button--icon-bar{display:none;} </style>
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['hideButtonIcons' => ['foo', 'bar']])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_changes_id()
    {
        $expected = <<<EOT
<span id='container-foo'> <style>  </style><input id='foo' value='' name='post-trixFields[description]' type='hidden'><input id='attachment-foo' value='[]' name='attachment-post-trixFields[description]' type='hidden'><trix-editor class='trix-content' input='foo' data-config='{"id":"foo","modelClass":"Te7aHoudini\\\LaravelTrix\\\Tests\\\Models\\\Post","field":"description"}'></trix-editor> </span>
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['id' => 'foo'])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_changes_disk()
    {
        $expected = <<<EOT
data-config='{"disk":"fooDisk","id":"post-description-1","modelClass":"Te7aHoudini\\\LaravelTrix\\\Tests\\\Models\\\Post","field":"description"}
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['disk' => 'fooDisk'])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_change_container_element()
    {
        $expected = <<<EOT
<fooElement id='container-post-description-1'> <style>  </style><input id='post-description-1' value='' name='post-trixFields[description]' type='hidden'><input id='attachment-post-description-1' value='[]' name='attachment-post-trixFields[description]' type='hidden'><trix-editor class='trix-content' input='post-description-1' data-config='{"containerElement":"fooElement","id":"post-description-1","modelClass":"Te7aHoudini\\\LaravelTrix\\\Tests\\\Models\\\Post","field":"description"}'></trix-editor> </fooElement>
EOT;

        $this->assertTrue(
            Str::contains($this->post->trix('description', ['containerElement' => 'fooElement'])->__toString(), $expected)
        );
    }

    /** @test */
    public function it_renders_correct_id_for_new_model()
    {
        $expected = <<<EOT
<span id='container-post-description-new-model'> <style>  </style><input id='post-description-new-model' value='' name='post-trixFields[description]' type='hidden'><input id='attachment-post-description-new-model' value='[]' name='attachment-post-trixFields[description]' type='hidden'><trix-editor class='trix-content' input='post-description-new-model' data-config='{"id":"post-description-new-model","modelClass":"Te7aHoudini\\\LaravelTrix\\\Tests\\\Models\\\Post","field":"description"}'></trix-editor> </span>
EOT;

        $this->assertTrue(
            Str::contains((new Post)->trix('description')->__toString(), $expected)
        );
    }

    /** @test */
    public function it_returns_new_model_using_app_make()
    {
        $expected = <<<'EOT'
<span id='container-foomodel-BarField-new-model'> <style>  </style><input id='foomodel-BarField-new-model' value='' name='foomodel-trixFields[BarField]' type='hidden'><input id='attachment-foomodel-BarField-new-model' value='[]' name='attachment-foomodel-trixFields[BarField]' type='hidden'><trix-editor class='trix-content' input='foomodel-BarField-new-model' data-config='{"id":"foomodel-BarField-new-model","modelClass":"FooModel","field":"BarField"}'></trix-editor> </span>
EOT;

        $this->assertTrue(
            Str::contains(app('laravel-trix')->make('FooModel', 'BarField')->__toString(), $expected)
        );
    }
}
