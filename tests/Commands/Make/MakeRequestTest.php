<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeRequest;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeRequestTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:request', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_request_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeRequest::class;
		$arguments = ['name' => 'TestRequest'];
		$expected_path = 'src/Http/Requests/TestRequest.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Http\Requests',
			'class TestRequest',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_request_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeRequest::class;
		$arguments = ['name' => 'TestRequest'];
		$expected_path = 'app/Http/Requests/TestRequest.php';
		$expected_substrings = [
			'namespace App\Http\Requests',
			'class TestRequest',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
