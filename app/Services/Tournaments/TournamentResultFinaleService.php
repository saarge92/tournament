<?php


namespace App\Services\Tournaments;


use App\Interfaces\Tournaments\IResultGameFinaleService;
use App\Models\ResultFinal;
use App\Repositories\Implementations\ResultFinaleRepository;
use App\Repositories\Interfaces\ITeamRepository;
use App\Repositories\Interfaces\ITournamentRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Сервис по работе с результатами финальных стадий турниров (result_finals)
 *
 * @package App\Services\Tournaments
 * @author Serdar Durdyev
 */
class TournamentResultFinaleService implements IResultGameFinaleService
{
    public ITournamentRepository $tournamentRepository;
    public ITeamRepository $teamRepository;
    public ResultFinaleRepository $resultFinaleRepository;

    public function __construct(ITournamentRepository $tournamentRepository, ITeamRepository $teamRepository,
                                ResultFinaleRepository $resultFinaleRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->teamRepository = $teamRepository;
        $this->resultFinaleRepository = $resultFinaleRepository;
    }

    /**
     * Создаем данные для финалов
     * @param int $idTeam Команда для которой записываем результат
     * @param int $idTournament Id турнира, для которого добавляем результат финальных стадий
     * @param int $place Место занятое на турнире
     * @return ResultFinal Вернем запись с результатами команды
     */
    function createResultFinale(int $idTeam, int $idTournament, int $place): ResultFinal
    {
        $existRecordAboutFinale = $this->resultFinaleRepository->getFinalResultByTeamAndTournament($idTeam, $idTournament);
        if (!$existRecordAboutFinale)
            throw new ConflictHttpException("Такая запись результата уже существует");

        $team = $this->teamRepository->getTeam($idTeam);
        if (!$team)
            throw new ConflictHttpException("Такой команды не существует");

        $tournament = $this->tournamentRepository->getTournamentById($idTournament);
        if (!$tournament)
            throw new ConflictHttpException("Такой турнир не найден");

        return $this->resultFinaleRepository->createFinalResult([
            'id_team' => $idTeam,
            'id_tournament' => $idTournament,
            'place' => $place
        ]);
    }
}
