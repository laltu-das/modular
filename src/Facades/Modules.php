<?php

namespace Laltu\Modular\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Laltu\Modular\Support\ModuleConfig;
use Laltu\Modular\Support\ModuleRegistry;

/**
 * @method static ModuleConfig|null module(string $name)
 * @method static ModuleConfig|null moduleForPath(string $path)
 * @method static ModuleConfig|null moduleForClass(string $fqcn)
 * @method static Collection modules()
 * @method static Collection reload()
 *
 * @see \Laltu\Modular\Support\ModuleRegistry
 */
class Modules extends Facade
{
	protected static function getFacadeAccessor(): string
	{
		return ModuleRegistry::class;
	}
}
