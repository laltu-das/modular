<?php

namespace Laltu\Modular\Tests\Commands;

use Laltu\Modular\Console\Commands\ModulesCache;
use Laltu\Modular\Console\Commands\ModulesClear;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class ModulesClearTest extends TestCase
{
	use WritesToAppFilesystem;
	
	public function test_it_writes_to_cache_file(): void
	{
		$this->artisan(ModulesCache::class);
		
		$expected_path = $this->getApplicationBasePath().$this->normalizeDirectorySeparators('bootstrap/cache/modules.php');
		
		$this->assertFileExists($expected_path);
		
		$this->artisan(ModulesClear::class);
		
		$this->assertFileDoesNotExist($expected_path);
	}
}
