<?php


namespace App\Repositories\Interfaces;

/**
 * Интерфейс для репозитория по работе с сущностями result_finals
 *
 * @package App\Repositories\Interfaces
 * @author Serdar Durdyev
 */
interface IResultFinaleRepository
{
    function createFinalResult(array $data);
}
