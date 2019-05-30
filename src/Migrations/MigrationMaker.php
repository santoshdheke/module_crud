<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/27/18
 * Time: 10:46 AM
 */

namespace SsGroup\MkCrud\Migrations;

use Illuminate\Database\Migrations\MigrationCreator;


class MigrationMaker extends MigrationCreator
{
   public function createMigration($name,$path,$table = null,$create = false)
    {
        parent::create($name,$path,$table,$create);
    }

    public function getCreatedMigration($name){
        return parent::getDatePrefix().'_'.$name;
    }
    
}