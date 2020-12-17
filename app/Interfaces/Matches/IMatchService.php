<?php


namespace App\Interfaces\Matches;

use App\Models\Match;

/**
 * Интерфейс по работе с бизнес-логикой
 * по управлению матчами в турнирах
 *
 * @package App\Interfaces\Matches
 * @author Serdar Durdyev
 */
interface IMatchService
{
    function addMatchInfo(array $data): Match;

    function sayHello(): array;
}
