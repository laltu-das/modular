<?php

namespace Laltu\Modular\Tests\Commands\Make;

use Laltu\Modular\Console\Commands\Make\MakeLivewire;
use Laltu\Modular\Tests\Concerns\TestsMakeCommands;
use Laltu\Modular\Tests\Concerns\WritesToAppFilesystem;
use Laltu\Modular\Tests\TestCase;
use Livewire\Livewire;
use Livewire\LivewireManager;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\Mechanism;

class MakeLivewireTest extends TestCase
{
	use WritesToAppFilesystem;
	use TestsMakeCommands;

	protected function setUp(): void
	{
		parent::setUp();

		if (! class_exists(Livewire::class)) {
			$this->markTestSkipped('Livewire is not installed.');
		}
		
		if (class_exists(Mechanism::class)) {
			$this->markTestSkipped('Livewire 3 is not yet supported.');
		}
	}
	
	public function test_it_overrides_the_default_commands(): void
	{
		$this->requiresLaravelVersion('9.2.0');
		
		$this->artisan('make:livewire', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
		
		$this->artisan('livewire:make', ['--help' => true])
			->expectsOutputToContain('--module')
			->assertExitCode(0);
	}

	public function test_it_scaffolds_a_component_in_the_module_when_module_option_is_set(): void
	{
		$command = MakeLivewire::class;
		$arguments = ['name' => 'TestLivewireComponent'];
		$expected_path = 'src/Http/Livewire/TestLivewireComponent.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Http\Livewire',
			'class TestLivewireComponent',
		];

		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);

		$expected_view_path = 'resources/views/livewire/test-livewire-component.blade.php';
		$this->assertModuleFile($expected_view_path);
	}

	public function test_it_scaffolds_a_component_with_nested_folders(): void
	{
		$command = MakeLivewire::class;
		$arguments = ['name' => 'test.my-component/TestLivewireComponent'];
		$expected_path = 'src/Http/Livewire/Test/MyComponent/TestLivewireComponent.php';
		$expected_substrings = [
			'namespace Modules\TestModule\Http\Livewire\Test\MyComponent',
			'class TestLivewireComponent',
		];

		$this->assertModuleCommandResults($command, $arguments, $expected_path, $expected_substrings);

		$expected_view_path = 'resources/views/livewire/test/my-component/test-livewire-component.blade.php';
		$this->assertModuleFile($expected_view_path);
	}

	public function test_it_scaffolds_a_component_in_the_app_when_module_option_is_missing(): void
	{
		$command = MakeLivewire::class;
		$arguments = ['name' => 'TestLivewireComponent'];
		$expected_path = 'app/Http/Livewire/TestLivewireComponent.php';
		$expected_substrings = [
			'namespace App\Http\Livewire',
			'class TestLivewireComponent',
		];

		$this->assertBaseCommandResults($command, $arguments, $expected_path, $expected_substrings);

		$expected_view_path = 'resources/views/livewire/test-livewire-component.blade.php';
		$this->assertBaseFile($expected_view_path);
	}

	protected function getPackageProviders($app)
	{
		return array_merge(parent::getPackageProviders($app), [LivewireServiceProvider::class]);
	}

	protected function getPackageAliases($app)
	{
		return array_merge(parent::getPackageAliases($app), ['Livewire' => LivewireManager::class]);
	}
}
