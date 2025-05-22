<?php

namespace Laltu\Modular\Tests\Commands\Database;

use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;

class SeedCommandTest extends TestCase
{
	use WritesToAppFilesystem;
	
	public function test_it_looks_for_seeders_in_module_namespace_when_module_option_is_set(): void
	{
		$app_seeder = $this->createMockSeeder();
		$module_seeder = $this->createMockSeeder();
		
		$this->app->instance('Modules\\TestModule\\Database\\Seeders\\DatabaseSeeder', $module_seeder);
		$this->app->instance('Modules\\TestModule\\DatabaseSeeder', $module_seeder);
		$this->app->instance('Database\\Seeders\\DatabaseSeeder', $app_seeder);
		$this->app->instance('DatabaseSeeder', $app_seeder);
		
		$this->makeModule('test-module');
		
		$this->artisan('db:seed', ['--module' => 'test-module']);
		
		$this->assertEquals(1, $module_seeder->invoked);
		$this->assertEquals(0, $app_seeder->invoked);
	}
	
	public function test_it_looks_for_named_seeders_in_module_namespace_when_module_option_is_set(): void
	{
		$app_seeder = $this->createMockSeeder();
		$module_seeder = $this->createMockSeeder();
		
		$this->app->instance('Modules\\TestModule\\Database\\Seeders\\Custom\\Seeder', $module_seeder);
		$this->app->instance('Database\\Seeders\\Custom\\Seeder', $app_seeder);
		$this->app->instance('Custom\\Seeder', $app_seeder);
		
		$this->makeModule('test-module');
		
		$this->artisan('db:seed', ['--module' => 'test-module', '--class' => 'Custom\\Seeder']);
		
		$this->assertEquals(1, $module_seeder->invoked);
		$this->assertEquals(0, $app_seeder->invoked);
	}
	
	public function test_it_looks_for_seeders_in_app_namespace_when_module_option_is_missing(): void
	{
		$mock = $this->createMockSeeder();
		
		$this->app->instance('Database\\Seeders\\DatabaseSeeder', $mock);
		$this->app->instance('DatabaseSeeder', $mock);
		
		$this->artisan('db:seed');
		
		$this->assertEquals(1, $mock->invoked);
	}
	
	public function test_it_looks_for_named_seeders_in_app_namespace_when_module_option_is_missing(): void
	{
		$mock = $this->createMockSeeder();
		
		$this->app->instance('Database\\Seeders\\CustomSeeder', $mock);
		$this->app->instance('CustomSeeder', $mock);
		
		$this->artisan('db:seed', ['--class' => 'CustomSeeder']);
		
		$this->assertEquals(1, $mock->invoked);
	}
	
	protected function createMockSeeder()
	{
		return new class() {
			public $invoked = 0;
			
			public function __invoke()
			{
				$this->invoked++;
			}
			
			public function __call($method, $args)
			{
				// Just ignore everything else
				return $this;
			}
		};
	}
}
