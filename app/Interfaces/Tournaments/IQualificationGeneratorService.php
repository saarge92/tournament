<?php


namespace App\Interfaces\Tournaments;

/**
 * Интерфейс для работы генератора квалификационных матчей
 *
 * @package App\Interfaces\Tournaments
 * @author Serdar Durdyev
 */
interface IQualificationGeneratorService
{
    function generateQualificationGames();
}
