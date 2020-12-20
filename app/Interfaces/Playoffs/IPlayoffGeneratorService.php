<?php


namespace App\Interfaces\Playoffs;


interface IPlayoffGeneratorService
{
    function generatePlayOfForTournament(int $idTournament);
}
