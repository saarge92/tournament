<?php

namespace App\Services;

use App\Interfaces\IHelloService;

class HelloServices implements IHelloService
{

    function hello(): array
    {
        return ['message' => 'hello'];
    }
}
