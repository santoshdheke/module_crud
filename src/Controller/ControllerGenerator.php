<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/29/18
 * Time: 10:47 PM
 */

namespace SsGroup\MkCrud\Controller;


use Illuminate\Support\Str;

class ControllerGenerator
{

    /**
     * @return string
     */
    public function getStubPath()
    {
        return __dir__.'/stubs';
    }

    /**
     * Create a new model at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     * @throws \Exception
     */
    public function create($name,$path_option,$path,$module)
    {
        $stub = $this->getStub();
        file_put_contents($this->getPath($name,$path),$this->populateStub($name,$path_option,$stub,$module));
        return $path;
    }


    /**
     * Get the full path to the model.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path.'/'.$this->getClassName($name).'.php';
    }

    /**
     * Get the model stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    public function getStub()
    {
        return file_get_contents($this->getStubPath().'/controller.stub');
    }


    /**
     * Populate the place-holders in the model stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @return string
     */
    protected function populateStub($name,$path_option,$stub,$module)
    {
        $old = ['DummyClass','DummyNamespace','DummyModule'];
        $last_name_sapce = str_replace('/','\\', $path_option);
        $path = "Module\\{$module}\\Controller";
        if (isset($path_option)){
            $path .= "\\{$last_name_sapce}";
        }
        $new = [$this->getClassName($name),$path,$this->getClassName($module)];
        $stub = str_replace($old, $new, $stub);
        return $stub;
    }

    /**
     * Get the class name of the model.
     *
     * @param  string  $name
     * @return string
     */
    public function getClassName($name)
    {
        return Str::studly($name);
    }
}