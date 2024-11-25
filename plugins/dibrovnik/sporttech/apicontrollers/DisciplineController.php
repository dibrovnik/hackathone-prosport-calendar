<?php namespace Dibrovnik\SportTech\ApiControllers;

use Octobro\API\Classes\ApiController;
use Dibrovnik\SportTech\Models\Sport;
use Dibrovnik\SportTech\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use October\Rain\Exception\ApplicationException;
use October\Rain\Database\ModelException;
use Response;
use Input;

class DisciplineController extends ApiController
{

   public function getAll()
{
    // Получаем все дисциплины
    $disciplines = Discipline::with('sport')->get(); // Подгружаем связанные данные о спорте

    // Группируем дисциплины по виду спорта
    $groupedDisciplines = $disciplines->groupBy(function ($discipline) {
        // Проверяем, есть ли спорт, если нет - возвращаем 'Без спорта'
        return $discipline->sport ? $discipline->sport->name : 'Без спорта';
    });

    // Возвращаем результат
    return Response::json([
        'disciplines' => $groupedDisciplines
    ], 200);
}



    /**
     * Добавление новой дисциплины
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
{
    $data = $request->validate([
        'discipline_name' => 'required|string|max:255',
        'sport_name' => 'required|string|max:255',
    ]);

    // Приводим название дисциплины и спорта к правильному виду
    $disciplineName = ucwords(strtolower($data['discipline_name']));
    $sportName = ucwords(strtolower($data['sport_name']));

    try {
        // Проверяем, существует ли спорт с таким именем
        $sport = Sport::firstOrCreate(['name' => $sportName]);

        // Проверяем, существует ли дисциплина с таким именем
        $discipline = Discipline::firstOrCreate(['name' => $disciplineName]);

        // Явно связываем дисциплину с видом спорта
        $discipline->sport()->associate($sport);  // Устанавливаем связь с видом спорта
        $discipline->save(); // Сохраняем дисциплину с установленным спортом

        // Связываем дисциплину с событием, если спорт и дисциплина уникальны
        if (!$sport->disciplines->contains($discipline->id)) {
            $sport->disciplines()->attach($discipline->id);  // Теперь attach() работает
        }

        // Ответ с данными созданных объектов
        return Response::json([
            'message' => 'Дисциплина успешно добавлена.',
            'sport' => $sport,
            'discipline' => $discipline,
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
     * Обработчик исключений для возвращения удобочитаемых сообщений
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleException(\Exception $e)
    {
        // Проверяем, если это ошибка валидации уникальности
        if ($e instanceof ValidationException) {
            $errors = $e->errors();

            // Если ошибка связана с уникальностью дисциплины, возвращаем более понятное сообщение
            if (isset($errors['discipline_name'])) {
                return Response::json([
                    'error' => 'Ошибка при добавлении дисциплины.',
                    'message' => 'Дисциплина с таким именем уже существует. Пожалуйста, выберите другое название.'
                ], 422); // 422 Unprocessable Entity
            }
        }

        // Для всех остальных ошибок
        return Response::json([
            'error' => 'Ошибка при обработке запроса.',
            'details' => $e->getMessage()
        ], 500);
    }
}
