<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Illuminate\Database\Migrations\MigrationCreator;
use Laltu\Modular\Console\Commands\Make\MakeMigration;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class MakeMigrationTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->app->singleton('migration.creator', function($app) {
			return new class($app['files'], $app->basePath('stubs')) extends MigrationCreator {
				protected function getDatePrefix()
				{
					return 'test';
				}
			};
		});
	}
	
	public function test_it_overrides_the_default_command(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:migration', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}
	
	public function test_it_scaffolds_a_migration_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeMigration::class;
		$arguments = ['name' => 'test_migration'];
		$expected_path = 'database/migrations/test_test_migration.php';
		$expected_substrings = [
			'Illuminate\Database\Migrations\Migration',
			'extends Migration',
			'function up',
		];
		
		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
	
	public function test_it_scaffolds_a_migration_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeMigration::class;
		$arguments = ['name' => 'test_migration'];
		$expected_path = 'database/migrations/test_test_migration.php';
		$expected_substrings = [
			'Illuminate\Database\Migrations\Migration',
			'extends Migration',
			'function up',
		];
		
		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);
	}
}
