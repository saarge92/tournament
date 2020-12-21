<?php

namespace App\Http\Controllers;

use App\Interfaces\Playoffs\IPlayOffService;

/**
 * Контроллер, отвечающий за обработку запросов связанных с данными playoff
 * @package App\Http\Controllers
 */
class PlayOffController extends Controller
{
    protected IPlayOffService $playOffService;

    public function __construct(IPlayOffService $playOffService)
    {
        $this->playOffService = $playOffService;
    }

    /**
     * Получение данных плей-офф по турнирам по запросу GET /playoff/tournament/{id}
     * @param int $tournamentId Id турнира
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlayOffInfo(int $tournamentId)
    {
        $resultPlayOffs = $this->playOffService->getPlayOffResultsByTournamentId($tournamentId);
        return response()->json($resultPlayOffs);
    }
}
