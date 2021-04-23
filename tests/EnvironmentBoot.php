<?php

namespace Kompo\Tests;

use Illuminate\Database\Eloquent\Factory;
use Kompo\KompoServiceProvider;
use Kompo\Routing\Mixins\ExtendsRoutingTrait;
use Kompo\Tests\Models\User;
use Orchestra\Testbench\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EnvironmentBoot extends TestCase
{
    use ExtendsRoutingTrait;
    use KompoTestRequestsTrait;
    use KompoUtilitiesTrait;

    /**
     * Setup the test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (file_exists($file = __DIR__.'/Utilities/TestHelpers.php')) {
            require_once $file;
        }

        KompoServiceProvider::registerHelpers();

        KompoServiceProvider::initializeBootFlag();

        $this->extendRouting();
        $this->loadRoutes();

        //Migrations... (only dependency on Orchestra Package)
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->loadViews();

        $this->app->make(Factory::class)->load(__DIR__.'/Factories');
        $this->user = factory(User::class)->create();
    }

    /**
     * Load the views needed to perform the tests.
     */
    protected function loadViews()
    {
        \View::addNamespace('kompo', __DIR__.'/../resources/views'); //package views
        \View::addNamespace('test', __DIR__.'/resources/views'); //for testing
    }

    /**
     * Load the routes needed to perform the tests.
     */
    protected function loadRoutes()
    {
        require __DIR__.'/../routes/web.php';

        $this->kompoRoute = route($this->kompoUri);
    }

    /**
     * Setting up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        //App & DB config
        $app['config']->set('app.key', 'base64:AY8IdjRIyYUguAV21ZLOsYclc0aWKwuDgM/lJ95lZRk=');

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        //Kompo Config
        $app['config']->set('kompo', require(__DIR__.'/../config/kompo.php'));

        //in Tests, we want to compare response json with the model and have 201 responses.
        $app['config']->set('kompo.eloquent_form.return_model_as_response', true);

        //Spatie Config
        $app['config']->set('permission.table_names', [
            'roles'                 => 'roles',
            'permissions'           => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles'       => 'model_has_roles',
            'role_has_permissions'  => 'role_has_permissions',
        ]);
        $app['config']->set('permission.column_names', [
            'model_morph_key' => 'model_id',
        ]);
        $app['config']->set('permission.models', [
            'permission' => Permission::class,
            'role'       => Role::class,
        ]);
    }
}
