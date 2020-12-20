<?php

namespace App\Http\Controllers;

use App\Interfaces\Playoffs\IPlayoffGeneratorService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер по работе с генерацией плей-офф результатов
 *
 * @package App\Http\Controllers
 * @author Serdar Durdyev
 */
class PlayOffGeneratorController extends Controller
{
    public IPlayoffGeneratorService $playoffGeneratorService;

    public function __construct(IPlayoffGeneratorService $playoffGeneratorService)
    {
        $this->playoffGeneratorService = $playoffGeneratorService;
    }

    /**
     * Генерация турнира по запросу POST /playoff/{id}/generate
     * @param int $tournamentId Id турнира
     * @return JsonResponse Вернет json-ответ с данными сгенерированных плей-офф результатов
     */
    public function generatePlayOff(int $tournamentId): JsonResponse
    {
        $result = $this->playoffGeneratorService->generatePlayOfForTournament($tournamentId);
        return response()->json($result, JsonResponse::HTTP_OK);
    }
}
