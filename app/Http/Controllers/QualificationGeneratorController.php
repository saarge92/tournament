<?php

namespace App\Http\Controllers;


use App\Interfaces\Tournaments\IQualificationGeneratorService;
use App\Interfaces\Tournaments\IQualificationTournamentService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер, отвечающий за обработку запросов
 * касательно генерации квалификационных игр и турнирной таблицы
 *
 * @package App\Http\Controllers
 * @author Serdar Durdyev
 */
class QualificationGeneratorController extends Controller
{
    protected IQualificationGeneratorService $qualificationGeneratorService;

    public function __construct(IQualificationGeneratorService $qualificationGeneratorService)
    {
        $this->qualificationGeneratorService = $qualificationGeneratorService;
    }

    /**
     * Генерация данных квалификационных данных
     * и получение турнирной таблицы по запросу
     * POST /api/qualification/generate
     *
     * @return JsonResponse Json ответ с турнирной таблицей
     */
    public function generateQualificationGames()
    {
        $data = $this->qualificationGeneratorService->generateQualificationGames();
        return response()->json($data, JsonResponse::HTTP_OK);
    }

}
