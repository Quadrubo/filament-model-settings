<?php

namespace Quadrubo\FilamentModelSettings\Exceptions;

use Exception;

class HasModelSettingsNotImplementedException extends Exception
{
    public function __construct()
    {
        $message = 'The Quadrubo\\FilamentModelSettings\\Pages\\Contracts\\HasModelSettings interface has to be implemented.';
        parent::__construct($message);
    }
}
