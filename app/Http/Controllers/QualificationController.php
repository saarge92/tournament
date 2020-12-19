<?php

namespace App\Http\Controllers;

use App\Interfaces\Tournaments\IQualificationTournamentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    private IQualificationTournamentService $qualificationTournamentService;

    public function __construct(IQualificationTournamentService $qualificationTournamentService)
    {
        $this->qualificationTournamentService = $qualificationTournamentService;
    }

    /**
     * Получение данных квалифакационного турнира
     * @param int $id Id турнира
     * @return JsonResponse
     */
    public function getQualificationByTournamentId(int $id)
    {
        $qualificationData = $this->qualificationTournamentService->getQualificationTournamentResult($id);
        return response()->json($qualificationData, JsonResponse::HTTP_OK);
    }
}
