<?php namespace Dibrovnik\SportTech\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Sportsman extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_sporttech_sportsmans';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    
    protected $fillable = [
        'user_id',
        'sub_events',
    ];

    // Связь с пользователем
    public $belongsTo = [
        'user' => \RainLab\User\Models\User::class
    ];

    /**
     * Получает список мероприятий, связанных с sub_events
     */
    public function getSubEventsDetails()
    {
        try {
            // Декодируем строку JSON в массив
            $subEventsArray = json_decode($this->sub_events, true);

            if (!is_array($subEventsArray)) {
                return ['error' => 'Invalid sub_events format.'];
            }

            // Извлекаем мероприятия из базы данных
            $events = SportEvent::whereIn('code', $subEventsArray)->get();

            // Форматируем данные для ответа
            $response = $events->map(function ($event) {
                $discipline = $event->disciplines->first(); // Предполагаем, что каждое мероприятие связано с одной дисциплиной

                // Преобразуем даты в Carbon, если они существуют
                $startDate = $event->start_date ? Carbon::parse($event->start_date)->format('d.m.y') : 'N/A';
                $endDate = $event->end_date ? Carbon::parse($event->end_date)->format('d.m.y') : 'N/A';

                return [
                    'id' => $event->id,
                    'code' => $event->code,
                    'name' => $event->name,
                    'sport' => $discipline?->sport->name ?? 'N/A',
                    'discipline' => $discipline?->name ?? 'N/A',
                    'genderAge' => ($event->gender ?? 'N/A'),
                    'startTime' => $startDate,
                    'endTime' => $endDate,
                    'country' => $event->country ?? 'N/A',
                    'location' => $event->location ?? 'N/A',
                    'participants' => $event->participants_count ?? 'N/A',
                ];
            });

            return $response->toArray();
        } catch (\Exception $e) {
            // Логируем и возвращаем ошибку
            \Log::error('Error fetching sub events details: ' . $e->getMessage());
            return ['error' => 'Error fetching sub events details.'];
        }
    }

    /**
     * Добавить мероприятие в подписки спортсмена
     *
     * @param string $eventCode
     * @return array
     */
    public function addSubscription($eventCode)
    {
        try {
            // Декодируем JSON
            $subEvents = json_decode($this->sub_events, true) ?? [];

            // Проверяем, есть ли уже код
            if (in_array($eventCode, $subEvents)) {
                return ['error' => 'Event is already subscribed.'];
            }

            // Добавляем новый код
            $subEvents[] = $eventCode;

            // Обновляем поле
            $this->sub_events = json_encode($subEvents);
            $this->save();

            return ['success' => 'Event added successfully.', 'sub_events' => $subEvents];
        } catch (\Exception $e) {
            \Log::error('Error adding subscription: ' . $e->getMessage());
            return ['error' => 'Error adding subscription.'];
        }
    }

    /**
     * Удалить мероприятие из подписок спортсмена
     *
     * @param string $eventCode
     * @return array
     */
    public function removeSubscription($eventCode)
    {
        try {
            // Декодируем JSON
            $subEvents = json_decode($this->sub_events, true) ?? [];

            // Проверяем, есть ли код в подписках
            if (!in_array($eventCode, $subEvents)) {
                return ['error' => 'Event is not subscribed.'];
            }

            // Удаляем код
            $subEvents = array_filter($subEvents, function ($code) use ($eventCode) {
                return $code !== $eventCode;
            });

            // Обновляем поле
            $this->sub_events = json_encode(array_values($subEvents));
            $this->save();

            return ['success' => 'Event removed successfully.', 'sub_events' => $subEvents];
        } catch (\Exception $e) {
            \Log::error('Error removing subscription: ' . $e->getMessage());
            return ['error' => 'Error removing subscription.'];
        }
    }

}
