<?php

namespace Laltu\Modular\Console\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Laltu\Modular\Support\ModuleConfig;
use Laltu\Modular\Support\ModuleRegistry;
use function Laravel\Prompts\select;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputOption;

trait Modularize
{
	protected ?string $module = null;

    /**
     * @throws BindingResolutionException
     */
    private function moduleRegistry(): ModuleRegistry
	{
		return $this->getLaravel()->make(ModuleRegistry::class);
	}

    /**
     * @throws FileNotFoundException
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
		if ($this->input->hasParameterOption('--module')) {
			$modules = $this->moduleRegistry()->modules()->keys();

			$this->module = $this->option('module') ?: (string) select('Which module?', $modules);
		}

		parent::handle();
	}

    /**
     * @throws BindingResolutionException
     */
    protected function module(): ?ModuleConfig
	{
		if ($this->module === null) {
			return null;
		}

		$config = $this->moduleRegistry()->module($this->module);

		if ($config === null) {
			throw new InvalidOptionException(sprintf('The "%s" module does not exist.', $this->module));
		}
		
		return $config;
	}
	
	protected function configure(): void
    {
		parent::configure();
		
		$this->getDefinition()->addOption(
			new InputOption(
				'--module',
				null,
				InputOption::VALUE_OPTIONAL,
				'Run inside an application module',
				false
			)
		);
	}
}
