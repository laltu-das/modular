<?php

namespace Laltu\Modular\Console\Commands\Make;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Filesystem\Filesystem;

class MakeMigration extends MigrateMakeCommand
{
	use Modularize;

    /**
     * @throws BindingResolutionException
     */
    protected function getMigrationPath(): array|string
    {
		$path = parent::getMigrationPath();
		
		if ($module = $this->module()) {
			$app_directory = $this->laravel->databasePath('migrations');
			$module_directory = $module->path('database/migrations');
			
			$path = str_replace($app_directory, $module_directory, $path);
			
			$filesystem = $this->getLaravel()->make(Filesystem::class);
			if (! $filesystem->isDirectory($module_directory)) {
				$filesystem->makeDirectory($module_directory, 0755, true);
			}
		}
		
		return $path;
	}
}
