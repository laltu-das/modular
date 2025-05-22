<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeTest;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeTestTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:test', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_test_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeTest::class;
		$arguments = ['name' => 'TestTest'];
		$expected_path = 'tests/Feature/TestTest.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Tests',
			'use Tests\TestCase',
			'class TestTest extends TestCase',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_test_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeTest::class;
		$arguments = ['name' => 'TestTest'];
		$expected_path = 'tests/Feature/TestTest.php';
		$expected_substrings = [
			'namespace Tests\Feature',
			'use Tests\TestCase',
			'class TestTest extends TestCase',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
