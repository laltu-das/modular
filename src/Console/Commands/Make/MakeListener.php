<?php

namespace Laltu\Modular\Console\Commands\Make;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ListenerMakeCommand;
use Laltu\Modular\Support\Facades\Modules;

class MakeListener extends ListenerMakeCommand
{
	use Modularize;

    /**
     * @throws FileNotFoundException
     */
    protected function buildClass($name): array|string
    {
		$event = $this->option('event');
		
		if (Modules::moduleForClass($name)) {
			$stub = str_replace(
				['DummyEvent', '{{ event }}'],
				class_basename($event),
				GeneratorCommand::buildClass($name)
			);
			
			return str_replace(
				['DummyFullEvent', '{{ eventNamespace }}'],
				trim($event, '\\'),
				$stub
			);
		}
		
		return parent::buildClass($name);
	}
}
