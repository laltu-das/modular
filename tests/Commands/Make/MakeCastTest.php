<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeCast;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeCastTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:cast', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_cast_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeCast::class;
		$arguments = ['name' => 'JsonCast'];
		$expected_path = '/src/Casts/JsonCast.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Casts',
			'class JsonCast',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_cast_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeCast::class;
		$arguments = ['name' => 'JsonCast'];
		$expected_path = 'app/Casts/JsonCast.php';
		$expected_substrings = [
			'namespace App\Casts',
			'class JsonCast',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
