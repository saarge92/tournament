<?php

namespace App\Http\Controllers;


use App\Interfaces\Tournaments\IQualificationGeneratorService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер, отвечающий за обработку запросов
 * касательно генерации квалификационных игр и турнирной таблицы
 *
 * @package App\Http\Controllers
 * @author Serdar Durdyev
 */
class QualificationController extends Controller
{
    protected IQualificationGeneratorService $qualificationService;

    public function __construct(IQualificationGeneratorService $qualificationGeneratorService)
    {
        $this->qualificationService = $qualificationGeneratorService;
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
        $data = $this->qualificationService->generateQualificationGames();
        return response()->json($data, JsonResponse::HTTP_OK);
    }

}
