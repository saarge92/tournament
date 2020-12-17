<?php


namespace App\Services\Tournaments;


use App\Interfaces\Tournaments\ITournamentResultService;
use App\Models\TournamentResult;
use App\Repositories\Interfaces\ITournamentRepository;
use App\Repositories\Interfaces\ITournamentResultRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Сервис по работе с данными квалификационных турниров tournament_results
 *
 * @package App\Services\Tournaments
 * @author Serdar Durdyev
 */
class TournamentResultService implements ITournamentResultService
{
    public ITournamentResultRepository $tournamentResultRepository;
    public ITournamentRepository $tournamentRepository;

    public function __construct(ITournamentResultRepository $tournamentResultRepository, ITournamentRepository $tournamentRepository)
    {
        $this->tournamentResultRepository = $tournamentResultRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * Создание результата турнира с нуля, когда запись полностью отсутствует
     * @param int $idTeam Id команды, для которой создаем данные
     * @param int $idTournament Id турнира
     * @param int $points Количество очков, присваиваемых команде
     * @return TournamentResult Вернем созданную запись
     */
    public function createTeamResult(int $idTeam, int $idTournament, int $points): TournamentResult
    {
        $tournament = $this->tournamentRepository->getTournamentById($idTournament);
        if (!$tournament)
            throw new ConflictHttpException("Такой турнир отсутсвует в базе");

        $existedTeamTournamentResult = $this->tournamentResultRepository->findTournament($idTeam, $idTournament);
        if ($existedTeamTournamentResult)
            throw new ConflictHttpException("Данные турнира для этой команды уже существуют! Попробуйте обновить запись");

        if ($points < 0)
            throw new ConflictHttpException("Очки не могут быть отрицательными");

        return $this->tournamentResultRepository->createTournamentResult([
            'id_team' => $idTeam,
            'id_tournament' => $idTournament,
            'points' => $points
        ]);
    }

    /**
     * Обновление данных результатов команды и присвоение им количество очков
     * @param int $idTeam Id команды
     * @param int $idTournament Id турнира
     * @param int $newPoint Прибавляемое количество очков
     * @return TournamentResult Новый результат турнира
     */
    public function updateTeamResult(int $idTeam, int $idTournament, int $newPoint): TournamentResult
    {
        if ($newPoint < 0)
            throw new ConflictHttpException("Присваиваемые очки не могут быть отрицательными");

        $tournament = $this->tournamentRepository->getTournamentById($idTournament);
        if (!$tournament)
            throw new ConflictHttpException("Такой турнир отсутсвует в базе");

        $existedRecordTeamResult = $this->tournamentResultRepository->findTournament($idTeam, $idTournament);
        if (!$existedRecordTeamResult)
            return $this->tournamentResultRepository->createTournamentResult([
                'id_team' => $idTeam,
                'id_tournament' => $idTournament,
                'points' => $newPoint
            ]);

        $existedRecordTeamResult->points = $existedRecordTeamResult->points + $newPoint;
        $existedRecordTeamResult->update();
        return $existedRecordTeamResult;
    }
}
