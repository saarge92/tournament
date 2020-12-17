<?php

namespace App\Http\Controllers;

use App\Interfaces\IHelloService;


class HomeController extends Controller
{
    protected IHelloService $helloService;

    public function __construct(IHelloService $helloService)
    {
        $this->helloService = $helloService;
    }

    public function hello()
    {
        return response()->json($this->helloService->hello());
    }
}
