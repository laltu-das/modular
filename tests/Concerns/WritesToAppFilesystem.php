<?php

namespace Laltu\Modular\Tests\Concerns;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\Attributes\Before;

trait WritesToAppFilesystem
{
	protected $filesystem;
	
	protected string $last_test_modules_root = 'modules';
	
	/** @before */
	public function cleanUpAppModules(): void
	{
		$this->beforeApplicationDestroyed(function() {
			$modules_path = static::applicationBasePath().'/'.$this->last_test_modules_root;
			$this->filesystem()->deleteDirectory($modules_path);
		});
	}
	
	protected function filesystem(): Filesystem
	{
		if (null === $this->filesystem) {
			$this->filesystem = new Filesystem();
		}
		
		return $this->filesystem;
	}
	
	protected function copyStub(string $stub, string $destination): string
	{
		$destination = trim($destination, '/');
		$destination = static::applicationBasePath().$this->normalizeDirectorySeparators("{$destination}");
		
		$stubs_directory = str_replace('\\', '/', dirname(__DIR__, 1)).'/stubs';
		
		$from = $this->normalizeDirectorySeparators("{$stubs_directory}/{$stub}");
		$to = $this->normalizeDirectorySeparators("{$destination}/{$stub}");
		
		$this->filesystem()->ensureDirectoryExists($destination);
		$this->filesystem()->copy($from, $to);
		
		return $to;
	}
	
	protected function getModulePath(string $module_name, string $path = '/', string $modules_root = 'modules'): string
	{
		$this->last_test_modules_root = $modules_root;
		
		return static::applicationBasePath()
			.'/'
			.$modules_root
			.'/'
			.$module_name
			.$this->normalizeDirectorySeparators($path);
	}
	
	protected function normalizeDirectorySeparators(string $path): string
	{
		if (($path = trim($path, '/')) && (substr($path, 1, 1) !== ':')) {
			$path = '/'.$path;
		}
		
		return $path;
	}
}
