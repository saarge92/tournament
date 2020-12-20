<?php


namespace App\Services\Playoffs;


use App\Interfaces\Playoffs\IPlayoffGeneratorService;
use App\Repositories\Interfaces\IResultFinaleRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;

/**
 * Генератор плей-оф матчей для турниров
 *
 * @package App\Services\Playoffs
 * @author Serdar Durdyev
 */
class PlayoffGeneratorService implements IPlayoffGeneratorService
{
    public ITournamentResultRepository $tournamentResultRepository;
    public IResultFinaleRepository $finaleRepository;

    public function __construct(ITournamentResultRepository $tournamentResultRepository, IResultFinaleRepository $resultFinaleRepository)
    {
        $this->tournamentResultRepository = $tournamentResultRepository;
        $this->finaleRepository = $resultFinaleRepository;
    }

    /**
     * Генерирование плей-оф матчей
     * @param int $idTournament
     * @return array
     */
    public function generatePlayOfForTournament(int $idTournament)
    {
        $tournamentResults = $this->tournamentResultRepository->getTournamentResultByTournamentId($idTournament);
        if (!$tournamentResults)
            return [];

        $resultFinale = $this->finaleRepository->getFinaleResultByTournamentId($idTournament);
        if ($resultFinale)
            return $resultFinale;

    }

    private function startGeneratePlayOffData(int $idTournament)
    {
        $tournamentResults = $this->tournamentResultRepository->getTournamentResultByTournamentIdGroupedByDivision($idTournament)
            ->groupBy('id_division');
        foreach ($tournamentResults as $divisionIndex => $tournamentResult) {
            $orderedGroupResult = $tournamentResult->orderBy('points')->take(4);
        }
    }
}
