<?php

namespace SsGroup\MkCrud;

use Illuminate\Support\ServiceProvider;
use SsGroup\MkCrud\Commands\MakeMigration;
use SsGroup\MkCrud\Commands\MakeController;
use SsGroup\MkCrud\Providers\CommandServiceProvider;

class MakeCrudServiceProvider extends ServiceProvider
{
    private $namespace = 'Module\\';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
//        $this->commands($this->cmd());
    }

    public function cmd()
    {
        $commands = glob(__DIR__.'/Commands/*');
        $c = [];
        foreach ($commands as $command) {
            $command = explode('.',$command);
            if (count($command)>1){
                array_push($c,'SsGroup\MkCrud\Commands\\'.$this->getCommand($command[0]));
            }
        }
        return $c;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/' . 'Main/route/web.php';
        $modules = glob(base_path() . '/Module/*');

        foreach ($modules as $key => $module) {
            $m = $this->getModule($module);

            if (file_exists(base_path('Module/' . $m . '/route/public.php'))) {
                $this->mapPublicRoutes($m);
                $this->mapAuthRoutes($m);
                $this->mapApiRoutes($m);
            }

            if (is_dir($module)) {
                $this->loadViewsFrom($module . '/view', $m);
//                $this->mergeConfigFrom($module . '/config/app.php', strtolower($m));
            }
        }
    }

    /**
     * register public route which can access non auth people
     *
     * @param $module
     */
    protected function mapPublicRoutes($module)
    {
        \Route::middleware('web')
            ->namespace($this->namespace . $module . '\\Controller')
            ->group(base_path('Module/' . $module . '/route/public.php'));
    }

    /**
     * register auth route which can access only auth people
     *
     * @param $module
     */
    protected function mapAuthRoutes($module)
    {
        \Route::middleware(['web','auth:'.strtolower($module)])
            ->namespace($this->namespace . $module . '\\Controller')
            ->group(base_path('Module/' . $module . '/route/auth.php'));
    }

    /**
     * register api route
     *
     * @param $module
     */
    protected function mapApiRoutes($module)
    {
        \Route::prefix('api/')
            ->namespace($this->namespace . $module . '\\ApiController')
            ->group(base_path('Module/' . $module . '/route/api.php'));
    }

    /**
     * get module name
     *
     * @param $module
     * @return mixed
     */
    private function getModule($module)
    {
        $marr = explode('/', $module);
        return end($marr);
    }

    public function registerProviders()
    {
        $this->app->register(CommandServiceProvider::class);
    }

    /**
     * get command name
     *
     * @param $module
     * @return mixed
     */
    private function getCommand($module)
    {
        $marr = explode('/', $module);
        return end($marr);
    }

}
