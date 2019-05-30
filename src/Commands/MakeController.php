<?php

namespace SsGroup\MkCrud\Commands;

use Illuminate\Console\Command;
use SsGroup\MkCrud\Controller\ControllerGenerator;

class MakeController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller {name} {--path=} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $controllerGenerator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ControllerGenerator $controllerGenerator)
    {
        $this->controllerGenerator = $controllerGenerator;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $path_option = $this->option('path');

        $this->info("================================================");
        $this->info(" ...... Creating Controller in {$module} .......");
        $this->info("================================================");

        $module_path = base_path() . '/Module/' . $module;
        $controller_path = $module_path . '/Controller';

        if (!folder_exist($module_path)) {
            $this->error("Module " . '(' . $module . ')' . ' Not Found in Module');
            die;
        }

        if (!folder_exist($controller_path)) {
            mkdir($module_path . '/Controller', 0777, true);
        }

        $make_in = $controller_path . '/' . $path_option;
        makeDir($make_in);

        $this->controllerGenerator->create($name, $path_option, $make_in, $module);

        $this->info("Controller Created in {$module} : {$name} ");
    }

}
