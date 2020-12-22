<?php

namespace App\Http\Controllers;

use App\Interfaces\Teams\ITeamService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер по работе с командами
 * @package App\Http\Controllers
 */
class TeamController extends Controller
{
    protected ITeamService $teamService;

    public function __construct(ITeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Получение команд по id дивизиона
     * по запросу GET /api/team/division/{id}
     *
     * @param int $idDivision Id дивизиона
     * @return JsonResponse Json ответ с созданны
     */
    public function getTeamsByDivision(int $idDivision): JsonResponse
    {
        $teams = $this->teamService->getTeamByDivisionId($idDivision);
        return response()->json($teams, JsonResponse::HTTP_OK);
    }
}
