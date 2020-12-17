<?php


namespace App\Repositories\Interfaces;


use App\Models\Match;

/**
 * Интерфейс, определяющий функционал репозитория
 * для сущности матчей комманд
 * @package App\Repositories\Interfaces
 */
interface IMatchRepository
{
    function createMatch(array $data): Match;

    function getMatch(int $id): ?Match;
}
