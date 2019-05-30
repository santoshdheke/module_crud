<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/26/18
 * Time: 12:05 PM
 */

namespace SsGroup\MkCrud\Commands;

use Illuminate\Console\Command;
use SsGroup\MkCrud\Migrations\MigrationMaker;

class MakeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration {name} {--create=} {--table=} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It creates a new migration file to be included inside Modules';

    /**
     * @var MigrationMaker
     */
    protected $migrationMaker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MigrationMaker $migrationMaker)
    {
        parent::__construct();
        $this->migrationMaker = $migrationMaker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("=============================================");
        $this->info(" ........ Creating Migration .........");
        $this->info("=============================================");

        $filename = $this->argument('name');
        $module = $this->argument('module');
        $table_to_create = $this->option('create');
        $table_to_migrate = $this->option('table');

        $module_path = base_path() . '/Module/'.$module;

        if(!(folder_exist($module_path))){
            $this->error("Module ".'('.$module.')'.' Not Found in Module');
            die;
        }

        $database_path = $module_path.'/Database';

        makeDir($database_path);
        makeDir($database_path.'/factories');
        makeDir($database_path.'/migrations');
        makeDir($database_path.'/seeds');

        $migration_path = $database_path.'/migrations/';

        if(!is_null($table_to_create))
        {
            $this->migrationMaker->createMigration($filename,$migration_path,$table_to_create,true);
        }

        if(!is_null($table_to_migrate))
        {
            $this->migrationMaker->createMigration($filename,$migration_path,$table_to_migrate,false);
        }

        $this->info('Migration Created : '.$this->migrationMaker->getCreatedMigration($filename));
    }

}