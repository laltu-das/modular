<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeMail;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeMailTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:mail', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_mail_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeMail::class;
		$arguments = ['name' => 'TestMail'];
		$expected_path = 'src/Mail/TestMail.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Mail',
			'class TestMail',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_mail_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeMail::class;
		$arguments = ['name' => 'TestMail'];
		$expected_path = 'app/Mail/TestMail.php';
		$expected_substrings = [
			'namespace App\Mail',
			'class TestMail',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
