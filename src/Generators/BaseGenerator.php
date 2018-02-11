<?php

namespace Luthfi\CrudGenerator\Generators;

use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Luthfi\CrudGenerator\CrudMake;

/**
 * Base Generator Class
 */
abstract class BaseGenerator
{
    use DetectsApplicationNamespace;

    /**
     * The injected Filesystem class
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Array of defined model names
     *
     * @var array
     */
    protected $modelNames;

    /**
     * Array of stub's model names
     *
     * @var array
     */
    protected $stubModelNames;

    /**
     * The CrudMake class
     *
     * @var CrudMake
     */
    protected $command;

    public function __construct(Filesystem $files, CrudMake $command)
    {
        $this->files = $files;

        $this->command = $command;

        $this->modelNames = $this->command->modelNames;
        $this->getStubModelNames();
    }

    /**
     * Get stub's model names
     *
     * @return array
     */
    protected function getStubModelNames()
    {
        return $this->stubModelNames = [
            'model_namespace'           => 'mstrNmspc',
            'full_model_name'           => 'fullMstr',
            'plural_model_name'         => 'Masters',
            'model_name'                => 'Master',
            'table_name'                => 'masters',
            'lang_name'                 => 'master',
            'collection_model_var_name' => 'mstrCollections',
            'single_model_var_name'     => 'singleMstr',
        ];
    }

    /**
     * Generate class file content
     *
     * @return void
     */
    abstract public function generate();

    /**
     * Get class file content
     *
     * @param  string $stubName Name of stub file
     * @return void
     */
    abstract protected function getContent(string $stubName);

    /**
     * Make directory if the path is not exists
     *
     * @param  string $path Absolute path of targetted directory
     * @return string       Absolute path
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Generate file on filesystem
     * @param  string $path    Absoute path of file
     * @param  string $content Generated file content
     * @return string          Absolute path of file
     */
    protected function generateFile($path, $content)
    {
        $this->files->put($path, $content);

        return $path;
    }

    /**
     * Replace all string of model names
     *
     * @param  string $stub String of file or class stub with default content
     * @return string       Replaced content
     */
    protected function replaceStubString($stub)
    {
        return str_replace($this->stubModelNames, $this->command->modelNames, $stub);
    }

    /**
     * Get correct stub file content
     *
     * @param  string $stubName The stub file name
     * @return string           The stub file content
     */
    protected function getStubFileContent(string $stubName)
    {
        return $this->files->get(__DIR__.'/../stubs/'.$stubName.'.stub');
    }
}
