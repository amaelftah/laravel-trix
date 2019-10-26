<?php

namespace Te7aHoudini\LaravelTrix\Tests;

use Illuminate\Database\Schema\Blueprint;
use Te7aHoudini\LaravelTrix\LaravelTrixFacade;
use Te7aHoudini\LaravelTrix\Tests\Models\Post;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Te7aHoudini\LaravelTrix\LaravelTrixServiceProvider;

class TestCase extends BaseTestCase
{
    public $post;

    /**
     * Setup the test environment.
     */
    protected function setUp() :void
    {
        parent::setUp();
        
        $this->app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->post = Post::create();

        include_once __DIR__.'/../database/migrations/create_trix_rich_texts_table.php.stub';
        (new \CreateTrixRichTextsTable())->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTrixServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LaravelTrix' => LaravelTrixFacade::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
