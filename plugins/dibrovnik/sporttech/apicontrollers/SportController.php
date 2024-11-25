<?php namespace Dibrovnik\SportTech\ApiControllers;

use Octobro\API\Classes\ApiController;
use Dibrovnik\SportTech\Models\Sport;
use Illuminate\Http\Request;
use October\Rain\Database\ModelException;
use October\Rain\Exception\ApplicationException;
use Response;
use Input;

class SportController extends ApiController
{
    /**
     * Создание нового вида спорта
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'sport_name' => 'required|string|max:255|unique:dibrovnik_sporttech_sports,name',
        ]);

        // Приводим название спорта к правильному виду
        $sportName = ucwords(strtolower($data['sport_name']));

        try {
            // Проверяем, существует ли спорт с таким именем
            $sport = Sport::firstOrCreate(['name' => $sportName]);

            // Ответ с данными созданного объекта
            return Response::json([
                'message' => 'Вид спорта успешно добавлен.',
                'sport' => $sport,
            ], 201);

        } catch (ModelException $e) {
            // Обработка ошибок при сохранении данных
            return Response::json([
                'error' => 'Ошибка при добавлении данных.',
                'details' => $e->getMessage()
            ], 500);
        } catch (ApplicationException $e) {
            // Обработка ошибок, связанных с приложением
            return Response::json([
                'error' => 'Неизвестная ошибка.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получение всех видов спорта
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        try {
            $sports = Sport::all(); // Получаем все виды спорта

            // Ответ с данными всех видов спорта
            return Response::json([
                'sports' => $sports,
            ], 200);

        } catch (ModelException $e) {
            // Обработка ошибок при получении данных
            return Response::json([
                'error' => 'Ошибка при получении данных.',
                'details' => $e->getMessage()
            ], 500);
        } catch (ApplicationException $e) {
            // Обработка ошибок, связанных с приложением
            return Response::json([
                'error' => 'Неизвестная ошибка.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
