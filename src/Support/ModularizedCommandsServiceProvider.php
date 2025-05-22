<?php

namespace Laltu\Modular\Support;

use Illuminate\Console\Application;
use Illuminate\Console\Application as Artisan;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as OriginalMakeMigrationCommand;
use Illuminate\Support\ServiceProvider;
use Laltu\Modular\Console\Commands\Database\SeedCommand;
use Laltu\Modular\Console\Commands\Make\MakeCast;
use Laltu\Modular\Console\Commands\Make\MakeChannel;
use Laltu\Modular\Console\Commands\Make\MakeCommand;
use Laltu\Modular\Console\Commands\Make\MakeComponent;
use Laltu\Modular\Console\Commands\Make\MakeController;
use Laltu\Modular\Console\Commands\Make\MakeEvent;
use Laltu\Modular\Console\Commands\Make\MakeException;
use Laltu\Modular\Console\Commands\Make\MakeFactory;
use Laltu\Modular\Console\Commands\Make\MakeJob;
use Laltu\Modular\Console\Commands\Make\MakeListener;
use Laltu\Modular\Console\Commands\Make\MakeLivewire;
use Laltu\Modular\Console\Commands\Make\MakeMail;
use Laltu\Modular\Console\Commands\Make\MakeMiddleware;
use Laltu\Modular\Console\Commands\Make\MakeMigration;
use Laltu\Modular\Console\Commands\Make\MakeModel;
use Laltu\Modular\Console\Commands\Make\MakeNotification;
use Laltu\Modular\Console\Commands\Make\MakeObserver;
use Laltu\Modular\Console\Commands\Make\MakePolicy;
use Laltu\Modular\Console\Commands\Make\MakeProvider;
use Laltu\Modular\Console\Commands\Make\MakeRequest;
use Laltu\Modular\Console\Commands\Make\MakeResource;
use Laltu\Modular\Console\Commands\Make\MakeRule;
use Laltu\Modular\Console\Commands\Make\MakeSeeder;
use Laltu\Modular\Console\Commands\Make\MakeTest;
use Livewire\Commands as Livewire;

class ModularizedCommandsServiceProvider extends ServiceProvider
{
	protected array $overrides = [
		'command.cast.make' => MakeCast::class,
		'command.controller.make' => MakeController::class,
		'command.console.make' => MakeCommand::class,
		'command.channel.make' => MakeChannel::class,
		'command.event.make' => MakeEvent::class,
		'command.exception.make' => MakeException::class,
		'command.factory.make' => MakeFactory::class,
		'command.job.make' => MakeJob::class,
		'command.listener.make' => MakeListener::class,
		'command.mail.make' => MakeMail::class,
		'command.middleware.make' => MakeMiddleware::class,
		'command.model.make' => MakeModel::class,
		'command.notification.make' => MakeNotification::class,
		'command.observer.make' => MakeObserver::class,
		'command.policy.make' => MakePolicy::class,
		'command.provider.make' => MakeProvider::class,
		'command.request.make' => MakeRequest::class,
		'command.resource.make' => MakeResource::class,
		'command.rule.make' => MakeRule::class,
		'command.seeder.make' => MakeSeeder::class,
		'command.test.make' => MakeTest::class,
		'command.component.make' => MakeComponent::class,
		'command.seed' => SeedCommand::class,
	];
	
	public function register(): void
	{
		// Register our overrides via the "booted" event to ensure that we override
		// the default behavior regardless of which service provider happens to be
		// bootstrapped first (this mostly matters for Livewire).
		$this->app->booted(function() {
			Artisan::starting(function(Application $artisan) {
				$this->registerMakeCommandOverrides();
				$this->registerMigrationCommandOverrides();
				$this->registerLivewireOverrides($artisan);
			});
		});
	}
	
	protected function registerMakeCommandOverrides(): void
    {
		foreach ($this->overrides as $alias => $class_name) {
			$this->app->singleton($alias, $class_name);
			$this->app->singleton(get_parent_class($class_name), $class_name);
		}
	}
	
	protected function registerMigrationCommandOverrides(): void
    {
		// Laravel 8
		$this->app->singleton('command.migrate.make', function($app) {
			return new MakeMigration($app['migration.creator'], $app['composer']);
		});
		
		// Laravel 9
		$this->app->singleton(OriginalMakeMigrationCommand::class, function($app) {
			return new MakeMigration($app['migration.creator'], $app['composer']);
		});
	}
	
	protected function registerLivewireOverrides(Artisan $artisan): void
    {
		// Don't register commands if Livewire isn't installed
		if (! class_exists(Livewire\MakeCommand::class)) {
			return;
		}
		
		// Replace the resolved command with our subclass
		$artisan->resolveCommands([MakeLivewire::class]);
		
		// Ensure that if 'make:livewire' or 'livewire:make' is resolved from the container
		// in the future, our subclass is used instead
		$this->app->extend(Livewire\MakeCommand::class, function() {
			return new MakeLivewire();
		});
		$this->app->extend(Livewire\MakeLivewireCommand::class, function() {
			return new MakeLivewire();
		});
	}
}
