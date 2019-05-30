<?php

namespace SsGroup\MkCrud\Providers;

use Illuminate\Support\ServiceProvider;
use SsGroup\MkCrud\Commands\MakeMigration;
use SsGroup\MkCrud\Commands\MakeController;

class CommandServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->cmd());
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

    /**
     * @return array
     */
    public function provides()
    {
        $provides = $this->cmd();
        return $provides;
    }
}
