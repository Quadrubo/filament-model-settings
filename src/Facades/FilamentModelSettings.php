<?php

namespace Quadrubo\FilamentModelSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Quadrubo\FilamentModelSettings\FilamentModelSettings
 */
class FilamentModelSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Quadrubo\FilamentModelSettings\FilamentModelSettings::class;
    }
}
