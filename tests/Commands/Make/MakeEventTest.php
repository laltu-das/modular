<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeEvent;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeEventTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:event', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_event_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeEvent::class;
		$arguments = ['name' => 'TestEvent'];
		$expected_path = 'src/Events/TestEvent.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Events',
			'class TestEvent',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_event_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeEvent::class;
		$arguments = ['name' => 'TestEvent'];
		$expected_path = 'app/Events/TestEvent.php';
		$expected_substrings = [
			'namespace App\Events',
			'class TestEvent',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
