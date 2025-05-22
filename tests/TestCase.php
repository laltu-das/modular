<?php

namespace Laltu\Modular\Tests;

use Illuminate\Encryption\Encrypter;
use Laltu\Modular\Console\Commands\Make\MakeModule;
use Laltu\Modular\Console\ModularServiceProvider;
use Laltu\Modular\Facades\Modules;
use Laltu\Modular\ModularEventServiceProvider;
use Laltu\Modular\ModularizedCommandsServiceProvider;
use Laltu\Modular\Support\DatabaseFactoryHelper;
use Laltu\Modular\Support\ModuleConfig;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function setUp(): void
	{
		parent::setUp();
		
		Modules::reload();
		
		$config = $this->app['config'];
		
		// Add encryption key for HTTP tests
		$config->set('app.key', 'base64:'.base64_encode(Encrypter::generateKey('AES-128-CBC')));
		
		// Add stubs to view
		// $this->app['view']->addLocation(__DIR__.'/Feature/stubs');
	}
	
	protected function tearDown(): void
	{
		$this->app->make(DatabaseFactoryHelper::class)->resetResolvers();
		
		parent::tearDown();
	}
	
	protected function makeModule(string $name = 'test-module'): ModuleConfig
	{
		$this->artisan(MakeModule::class, [
			'name' => $name,
			'--accept-default-namespace' => true,
		]);
		
		return Modules::module($name);
	}
	
	protected function requiresLaravelVersion(string $minimum_version, string $operator = '>=')
	{
		if (! version_compare($this->app->version(), $minimum_version, $operator)) {
			$this->markTestSkipped("Only applies to Laravel {$operator} {$minimum_version}.");
		}
		
		return $this;
	}
	
	protected function getPackageProviders($app)
	{
		return [
			ModularServiceProvider::class,
			ModularizedCommandsServiceProvider::class,
			ModularEventServiceProvider::class,
		];
	}
	
	protected function getPackageAliases($app)
	{
		return [
			'Modules' => Modules::class,
		];
	}
}
