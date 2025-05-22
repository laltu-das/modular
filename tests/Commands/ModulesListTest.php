<?php

namespace Laltu\Modular\Tests\Commands;

use Laltu\Modular\Console\Commands\ModulesList;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class ModulesListTest extends TestCase
{
	use WritesToAppFilesystem;
	
	public function test_it_writes_to_cache_file(): void
	{
		$this->makeModule('test-module');
		
		$this->artisan(ModulesList::class)
			->expectsOutput('You have 1 module installed.')
			->assertExitCode(0);
		
		$this->makeModule('test-module-two');
		
		$this->artisan(ModulesList::class)
			->expectsOutput('You have 2 modules installed.')
			->assertExitCode(0);
	}
}
