<?php namespace Dibrovnik\SportTech\Components;

use Cms\Classes\ComponentBase;
use Dibrovnik\SportTech\Models\SportEvent;
use Dibrovnik\SportTech\Models\Sportsman;
use Auth;

class DetailPageComponent extends ComponentBase
{
    public $event;

    public function componentDetails()
    {
        return [
            'name' => 'Detail Page Component',
            'description' => 'Displays detailed information about a sport event'
        ];
    }

    public function defineProperties()
    {
        return [
            'id' => [
                'title'       => 'Event ID',
                'description' => 'ID of the event to display',
                'default'     => null,
                'type'        => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Event ID property is required and should be numeric.'
            ]
        ];
    }

    public function onRun()
    {
        $this->loadEvent();
    }

    protected function loadEvent()
    {
        $eventId = $this->property('id');
        $this->event = SportEvent::with('disciplines')->find($eventId);
        $this->sports = $this->event->disciplines->pluck('sport.name')->unique();
        $this->page['isSubscribedToEvent'] = $this->isSubscribedToEvent();


        if (!$this->event) {
            return \Redirect::to('/404');
        }
    }
    public function onSubscribeEvent()
    {
        // Проверка авторизации пользователя
        $user = Auth::getUser();
        if (!$user) {
            throw new \ApplicationException('Вы должны быть авторизованы для подписки.');
        }

        // Получение кода мероприятия из запроса
        $eventCode = post('event_code');
        if (!$eventCode) {
            throw new \ApplicationException('Не указан код мероприятия.');
        }

        // Получение объекта Sportsman
        $sportsman = Sportsman::where('user_id', $user->id)->first();
        if (!$sportsman) {
            throw new \ApplicationException('Спортсмен не найден.');
        }

        // Добавление подписки
        $result = $sportsman->addSubscription($eventCode);

        if (isset($result['error'])) {
            throw new \ApplicationException($result['error']);
        }

        return [
            'message' => 'Вы успешно подписались на мероприятие!',
            'sub_events' => $result['sub_events']
        ];
    }
    public function onUnsubscribeEvent()
    {
        // Проверка авторизации пользователя
        $user = Auth::getUser();
        if (!$user) {
            throw new \ApplicationException('Вы должны быть авторизованы для отписки.');
        }

        // Получение кода мероприятия из запроса
        $eventCode = post('event_code');
        if (!$eventCode) {
            throw new \ApplicationException('Не указан код мероприятия.');
        }

        // Получение объекта Sportsman
        $sportsman = Sportsman::where('user_id', $user->id)->first();
        if (!$sportsman) {
            throw new \ApplicationException('Спортсмен не найден.');
        }

        // Удаление подписки
        $result = $sportsman->removeSubscription($eventCode);

        if (isset($result['error'])) {
            throw new \ApplicationException($result['error']);
        }

        return [
            'message' => 'Вы успешно отписались от мероприятия!',
            'sub_events' => $result['sub_events']
        ];
    }

    public function isSubscribedToEvent()
    {
        // Проверка авторизации пользователя
        $user = Auth::getUser();
        if (!$user) {
            return false; // Пользователь не авторизован
        }

        // Получение объекта Sportsman
        $sportsman = Sportsman::where('user_id', $user->id)->first();
        if (!$sportsman) {
            return false; // Спортсмен не найден
        }

        // Получение кода мероприятия
        $eventCode = $this->event->code;

        // Проверка, есть ли мероприятие в подписках
        $subEvents = json_decode($sportsman->sub_events, true) ?? [];
        return in_array($eventCode, $subEvents);
    }


}
