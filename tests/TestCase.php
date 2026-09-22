<?php

namespace Pedreiro\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Pedreiro\PedreiroServiceProvider;
use Pedreiro\Test\Models\Category;
use Pedreiro\Test\Models\Post;
use Pedreiro\Test\Models\Tag;
use Illuminate\Support\Facades\Schema;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

    }

    protected function getPackageProviders($app)
    {
        return [
            PedreiroServiceProvider::class,
            \Pedreiro\Test\Providers\RouteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:QUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUE=');
        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        /*
        include_once __DIR__.'/../database/migrations/create_pedreiro_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }

    protected function setUpDatabase(): void
    {
        Schema::create('categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 100);
            $table->timestamps();
        });

        Schema::create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 100);
            $table->text('body');
            $table->date('publish_on')->nullable();
            $table->boolean('published')->default(false);
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('post_tag', function ($table) {
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        $category = Category::create(['name' => 'category 1', 'slug' => 'category-1']);
        $tag = Tag::create(['name' => 'tag 1', 'slug' => 'tag-1']);
        $post = Post::create([
            'title' => 'post 1',
            'slug' => 'post-1',
            'body' => 'post 1 body',
            'publish_on' => now(),
            'published' => true,
            'category_id' => $category->id,
        ]);
        $post->tags()->attach($tag);
    }
}

// <?php

// namespace Pedreiro\Test;

// use Carbon\Carbon;
// use Pedreiro\Test\Models\Tag;
// use Pedreiro\Test\Models\Post;
// use Pedreiro\Test\Models\Category;
// use Illuminate\Database\Capsule\Manager as DB;
// use Pedreiro\CrudControllerServiceProvider;
// use Orchestra\Testbench\TestCase as OrchestraTestCase;
// use Pedreiro\Test\Providers\RouteServiceProvider;

// abstract class TestCase extends OrchestraTestCase
// {
//     /**
//      * Setup the test environment.
//      *
//      * @return void
//      */
//     public function setUp(): void
//     {
//         parent::setUp();

//         $this->setUpDatabase();
//         $this->migrateTables();
//         $this->seedTables();
//     }

//     /**
//      * Get package providers.
//      *
//      * @param  \Illuminate\Foundation\Application  $app
//      * @return array
//      */
//     protected function getPackageProviders($app)
//     {
//         return [
//             CrudControllerServiceProvider::class,
//             RouteServiceProvider::class,
//         ];
//     }

//     /**
//      * Define environment setup.
//      *
//      * @param  \Illuminate\Foundation\Application   $app
//      * @return void
//      */
//     protected function getEnvironmentSetUp($app)
//     {
//         $this->app = $app;
//         $app['config']->set('app.url', 'http://localhost');
//         $app['config']->set('app.key', 'base64:WpZ7D2IUkBA+99f8HABIVujw2HqzR6kLGsTpDdV5nao=');
//     }

//     /**
//      * Setup the test database.
//      *
//      * @return void
//      */
//     protected function setUpDatabase()
//     {
//         $database = new DB();

//         $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
//         $database->bootEloquent();
//         $database->setAsGlobal();
//     }

//     /**
//      * Run migrations on test database.
//      *
//      * @return void
//      */
//     protected function migrateTables()
//     {
//         DB::schema()->create('categories', function ($table) {
//             $table->increments('id');
//             $table->string('name');
//             $table->string('slug', 100);
//             $table->timestamps();
//             $table->softDeletes();
//         });

//         DB::schema()->create('tags', function ($table) {
//             $table->increments('id');
//             $table->string('name');
//             $table->string('slug', 100);
//             $table->timestamps();
//         });

//         DB::schema()->create('posts', function ($table) {
//             $table->increments('id');
//             $table->string('title');
//             $table->string('slug', 100);
//             $table->text('body');
//             $table->date('publish_on')->nullable();
//             $table->boolean('published')->default(false);
//             $table->unsignedInteger('category_id');
//             $table->timestamps();
//             $table->softDeletes();

//             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
//         });

//         DB::schema()->create('post_tag', function ($table) {
//             $table->unsignedInteger('post_id');
//             $table->unsignedInteger('tag_id');

//             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
//             $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

//             $table->primary(['post_id', 'tag_id']);
//         });
//     }

//     /**
//      * Seed test tables with dummy data.
//      *
//      * @return void
//      */
//     protected function seedTables()
//     {
//         for ($i = 1; $i <= 11; ++$i) {
//             Category::create([
//                 'name' => "category $i",
//                 'slug' => "category-$i",
//             ]);

//             Tag::create([
//                 'name' => "tag $i",
//                 'slug' => "tag-$i",
//             ]);
//         }

//         for ($i = 1; $i <= 11; ++$i) {
//             $post = Post::create([
//                 'title'      => "post $i",
//                 'slug'       => "post-$i",
//                 'body'       => "post $i body",
//                 'publish_on' => date_sub(Carbon::now(), date_interval_create_from_date_string("$i days")),
//                 'published'  => rand(0, 1),
//                 'category_id'=> $i,
//             ]);

//             $post->tags()->attach(Tag::where('id', '<=', rand(0, 10))->get());
//         }
//     }
// }
