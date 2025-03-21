<?php

namespace Akika\Mojo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Akika\Mojo\Mojo
 */
class Mojo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Akika\Mojo\Mojo::class;
    }
}
