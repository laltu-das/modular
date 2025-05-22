<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeCommand;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeCommandTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:command', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_command_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeCommand::class;
		$arguments = ['name' => 'TestCommand'];
		$expected_path = 'src/Console/Commands/TestCommand.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Console\Commands',
			'use Illuminate\Console\Command',
			'class TestCommand extends Command',
			'test-module:test',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_uses_the_command_option_for_name_when_set(): void
	{
		$command = MakeCommand::class;
		$arguments = ['name' => 'TestCommand', '--command' => 'foo:bar-baz'];
		$expected_path = 'src/Console/Commands/TestCommand.php';
		$expected_substrings = [
			"signature = 'foo:bar-baz'",
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_command_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeCommand::class;
		$arguments = ['name' => 'TestCommand'];
		$expected_path = 'app/Console/Commands/TestCommand.php';
		$expected_substrings = [
			'namespace App\Console\Commands',
			'use Illuminate\Console\Command',
			'class TestCommand extends Command',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
