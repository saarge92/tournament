<?php


namespace App\Repositories\Implementations;


use App\Models\ResultFinal;
use App\Repositories\Interfaces\IResultFinaleRepository;

class ResultFinaleRepository implements IResultFinaleRepository
{

    function createFinalResult(array $data)
    {
        return ResultFinal::create($data);
    }
}
