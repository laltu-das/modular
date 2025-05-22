<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeChannel;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeChannelTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:channel', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_channel_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeChannel::class;
		$arguments = ['name' => 'TestChannel'];
		$expected_path = 'src/Broadcasting/TestChannel.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Broadcasting',
			'class TestChannel',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_channel_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeChannel::class;
		$arguments = ['name' => 'TestChannel'];
		$expected_path = 'app/Broadcasting/TestChannel.php';
		$expected_substrings = [
			'namespace App\Broadcasting',
			'class TestChannel',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
