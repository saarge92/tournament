<?php


namespace App\Services\Tournaments;


use App\Interfaces\Tournaments\IQualificationTournamentService;
use App\Models\Tournament;
use App\Repositories\Interfaces\IMatchRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;

/**
 * Сервис по работе с результатами турниров
 * Содержит методы, который предоставляют данные турнира в удобно-читаемом виде
 * (как например, результаты турнира, сгруппированного по дивизионам и турнирам)
 *
 * @package App\Services\Tournaments
 */
class QualificationTournamentService implements IQualificationTournamentService
{
    public ITournamentResultRepository $tournamentResultRepository;
    public ITournamentRepository $tournamentRepository;
    public IMatchRepository $matchRepository;

    public function __construct(ITournamentResultRepository $tournamentResultRepository, ITournamentRepository $tournamentRepository,
                                IMatchRepository $matchRepository)
    {
        $this->tournamentResultRepository = $tournamentResultRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->matchRepository = $matchRepository;
    }

    /**
     * Получение данных квалификации, сгруппированных по дивизионно
     * @param int $tournamentId Id Турнира
     * @return array Вернем массив с данными турнира
     */
    public function getQualificationTournamentResult(int $tournamentId)
    {
        $tournament = $this->tournamentRepository->getTournamentById($tournamentId);
        if (!$tournament)
            return [];

        $tournamentResults = $this->tournamentResultRepository->getTournamentResultByTournamentId($tournamentId);
        return $this->generateResponseForTournament($tournamentResults, $tournament);
    }

    private function generateResponseForTournament(iterable $tournamentResults, Tournament $tournament)
    {
        $response['tournament_id'] = $tournament->id;
        $response['tournament_name'] = $tournament->name;
        foreach ($tournamentResults as $tournamentResult) {
            $response['data'][] = $this->matchRepository->getMatchesByTeamIdAndTournament($tournamentResult->id_team, $tournament->id, 1);
        }
        return $response;
    }
}
