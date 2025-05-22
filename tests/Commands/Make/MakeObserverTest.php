<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeObserver;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeObserverTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:observer', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_observer_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeObserver::class;
		$arguments = ['name' => 'TestObserver'];
		$expected_path = 'src/Observers/TestObserver.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Observers',
			'class TestObserver',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_observer_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeObserver::class;
		$arguments = ['name' => 'TestObserver'];
		$expected_path = 'app/Observers/TestObserver.php';
		$expected_substrings = [
			'namespace App\Observers',
			'class TestObserver',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
