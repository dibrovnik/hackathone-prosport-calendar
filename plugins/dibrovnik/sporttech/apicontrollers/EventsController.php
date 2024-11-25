<?php namespace dibrovnik\sporttech\ApiControllers;

use Octobro\API\Classes\ApiController;
use dibrovnik\sporttech\models\Test;
use dibrovnik\sporttech\models\SportEvent;
use dibrovnik\sporttech\models\Discipline;
use dibrovnik\sporttech\models\Sportsman;
use Carbon\Carbon;
use DB;

class EventsController extends ApiController
{
    public function events()
    {
        try {
            $query = SportEvent::query();

            if ($country = request()->query('country')) {
                $query->where('country', $country);
            }
            if ($location = request()->query('location')) {
                $query->where('location', $location);
            }
            if ($sport = request()->query('sport')) {
                $query->whereHas('disciplines.sport', function ($q) use ($sport) {
                    $q->where('name', $sport);
                });
            }
            if ($discipline = request()->query('discipline')) {
                $query->whereHas('disciplines', function ($q) use ($discipline) {
                    $q->where('name', $discipline);
                });
            }
            if ($startDate = request()->query('start_date')) {
                $query->where('start_date', '>=', Carbon::parse($startDate)->toDateString());
            }
            if ($endDate = request()->query('end_date')) {
                $query->where('end_date', '<=', Carbon::parse($endDate)->toDateString());
            }

            $events = $query->with(['disciplines.sport'])->get();

            if ($events->isEmpty()) {
                return response()->json(['message' => null], 404);
            }

            $formattedEvents = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'gender' => $event->gender,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'country' => $event->country,
                    'location' => $event->location,
                    'participants' => $event->participants_count,
                    'sport' => $event->disciplines->first()?->sport->name ?? 'N/A',
                    'disciplines' => $event->disciplines->map(function ($discipline) {
                        return $discipline->name;
                    })->toArray(),
                ];
            });

            return response()->json($formattedEvents, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function eventByCode($code)
    {
        try {

            $event = DB::table('dibrovnik_sporttech_sports_events')->where('code', $code)->get();

            if ($event->isEmpty()) {
                return response()->json(['message' => NULL], 404);
            }

            return response()->json(['event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function eventById($id)
    {
        try {
            
            $event = DB::table('dibrovnik_sporttech_sports_events')->find($id);

            if (empty($event)) {
                return response()->json(['message' => NULL], 404);
            }

            return response()->json(['event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function eventsByDay($date)
    // {
    //     try {
    //         // Преобразуем дату в формат для работы с SQL
    //         $date = Carbon::parse($date)->toDateString();

    //         // Запрашиваем мероприятия, которые начинаются, заканчиваются или продолжаются в эту дату
    //         $events = SportEvent::where(function ($query) use ($date) {
    //                 $query->where('start_date', '<=', $date)
    //                     ->where('end_date', '>=', $date);
    //             })
    //             ->with(['disciplines.sport']) // Загружаем связанные дисциплины и виды спорта
    //             ->get();

    //         // Проверяем, есть ли события
    //         if ($events->isEmpty()) {
    //             return response()->json(['message' => null], 404);
    //         }

    //         // Форматируем данные для ответа
    //         $formattedEvents = $events->map(function ($event) {
    //             return [
    //                 'id' => $event->id,
    //                 'name' => $event->name,
    //                 'gender' => $event->gender,
    //                 'start_date' => $event->start_date,
    //                 'end_date' => $event->end_date,
    //                 'country' => $event->country,
    //                 'location' => $event->location,
    //                 'participants' => $event->participants_count,
    //                 'sport' => $event->disciplines->first()?->sport->name ?? 'N/A',
    //                 'disciplines' => $event->disciplines->map(function ($discipline) {
    //                     return $discipline->name;
    //                 })->toArray(),
    //             ];
    //         });

    //         // Возвращаем найденные события
    //         return response()->json($formattedEvents, 200);
    //     } catch (\Exception $e) {
    //         // Обрабатываем ошибки
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function eventsByDay($date)
    {
        try {
            $date = Carbon::parse($date)->toDateString();

            $query = SportEvent::where(function ($query) use ($date) {
                $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            });

            if ($country = request()->query('country')) {
                $query->where('country', $country);
            }
            if ($location = request()->query('location')) {
                $query->where('location', $location);
            }
            if ($sport = request()->query('sport')) {
                $query->whereHas('disciplines.sport', function ($q) use ($sport) {
                    $q->where('name', $sport);
                });
            }
            if ($discipline = request()->query('discipline')) {
                $query->whereHas('disciplines', function ($q) use ($discipline) {
                    $q->where('name', $discipline);
                });
            }

            $events = $query->with(['disciplines.sport'])->get();

            if ($events->isEmpty()) {
                return response()->json(['message' => null], 404);
            }

            $formattedEvents = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'gender' => $event->gender,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'country' => $event->country,
                    'location' => $event->location,
                    'participants' => $event->participants_count,
                    'sport' => $event->disciplines->first()?->sport->name ?? 'N/A',
                    'disciplines' => $event->disciplines->map(function ($discipline) {
                        return $discipline->name;
                    })->toArray(),
                ];
            });

            return response()->json($formattedEvents, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * Метод для получения мероприятий спортсмена по номеру телефона
     */
    public function getEventsByPhoneNumber($phone)
    {
        try {
            $sportsman = Sportsman::where('phone', $phone)->first();

            if (!$sportsman) {
                return response()->json(['error' => 'Sportsman not found'], 404);
            }

            $events = $sportsman->getSubEventsDetails();

            return response()->json($events, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching events for phone ' . $phone . ': ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching events.'], 500);
        }
    }

    // public function eventsByMonth($year, $month)
    // {
    //     try {
    //         // Определяем первый и последний день месяца
    //         $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
    //         $endOfMonth = $startOfMonth->copy()->endOfMonth();

    //         // Получаем мероприятия за указанный месяц
    //         $events = DB::table('dibrovnik_sporttech_sports_events') // Замените 'events' на имя вашей таблицы
    //             ->where(function ($query) use ($startOfMonth, $endOfMonth) {
    //                 $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
    //                     ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
    //                     ->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
    //                         $subQuery->where('start_date', '<=', $startOfMonth)
    //                                 ->where('end_date', '>=', $endOfMonth);
    //                     });
    //             })
    //             ->get();

    //         // Подготовка структуры для событий по дням
    //         $daysInMonth = $startOfMonth->daysInMonth; // Количество дней в месяце
    //         $eventsByDay = [];

    //         // Инициализируем массив с пустыми днями
    //         for ($day = 1; $day <= $daysInMonth; $day++) {
    //             $eventsByDay[$day] = [];
    //         }

    //         // Обрабатываем каждое событие
    //         foreach ($events as $event) {
    //             $eventStart = Carbon::parse($event->start_date);
    //             $eventEnd = Carbon::parse($event->end_date);

    //             // Убедимся, что даты корректно входят в диапазон месяца
    //             $actualStart = $eventStart->greaterThanOrEqualTo($startOfMonth) ? $eventStart : $startOfMonth;
    //             $actualEnd = $eventEnd->lessThanOrEqualTo($endOfMonth) ? $eventEnd : $endOfMonth;

    //             // Распределяем событие по дням
    //             for ($day = $actualStart->day; $day <= $actualEnd->day; $day++) {
    //                 $eventsByDay[$day][] = $event;
    //             }
    //         }

    //         return response()->json(['events_by_day' => $eventsByDay], 200);
    //     } catch (\Exception $e) {
    //         // Обрабатываем ошибки
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function eventsByMonth($year, $month)
    {
        try {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $query = SportEvent::where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($subQuery) use ($startOfMonth, $endOfMonth) {
                        $subQuery->where('start_date', '<=', $startOfMonth)
                                ->where('end_date', '>=', $endOfMonth);
                    });
            });

            if ($country = request()->query('country')) {
                $query->where('country', $country);
            }
            if ($location = request()->query('location')) {
                $query->where('location', $location);
            }
            if ($sport = request()->query('sport')) {
                $query->whereHas('disciplines.sport', function ($q) use ($sport) {
                    $q->where('name', $sport);
                });
            }
            if ($discipline = request()->query('discipline')) {
                $query->whereHas('disciplines', function ($q) use ($discipline) {
                    $q->where('name', $discipline);
                });
            }

            $events = $query->with(['disciplines.sport'])->get();

            $daysInMonth = $startOfMonth->daysInMonth;
            $eventsByDay = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $eventsByDay[$day] = [];
            }

            foreach ($events as $event) {
                $eventStart = Carbon::parse($event->start_date);
                $eventEnd = Carbon::parse($event->end_date);

                $actualStart = $eventStart->greaterThanOrEqualTo($startOfMonth) ? $eventStart : $startOfMonth;
                $actualEnd = $eventEnd->lessThanOrEqualTo($endOfMonth) ? $eventEnd : $endOfMonth;

                for ($day = $actualStart->day; $day <= $actualEnd->day; $day++) {
                    $eventsByDay[$day][] = $event;
                }
            }

            return response()->json(['events_by_day' => $eventsByDay], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}