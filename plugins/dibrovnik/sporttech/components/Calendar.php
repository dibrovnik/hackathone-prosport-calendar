<?php namespace Dibrovnik\SportTech\Components;


use Cms\Classes\ComponentBase;
use Carbon\Carbon;

class Calendar extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Calendar Component',
            'description' => 'Generates a calendar with events for a given month and year'
        ];
    }

    public function onRun()
    {
        $month = input('month', date('n')); // Получение месяца из параметров URL или текущего
        $year = input('year', date('Y')); // Получение года из параметров URL или текущего

        $this->page['calendarHtml'] = $this->generateCalendar($month, $year);
    }

    private function generateCalendar($month, $year)
    {
        $daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDayOfMonth = date('N', strtotime("$year-$month-01"));

        $months = [
            1 => 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь',
            'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'
        ];

        $monthName = $months[$month];
        $calendar = "<div id='calendar-container'>";
        $calendar .= "<div class='calendar-header'>";
        $calendar .= "<div id='current-month' class='fs-16 fw-700'>$monthName $year</div>";
        $calendar .= "<button onclick='changeMonth(-1)'>&laquo;</button>";
        $calendar .= "<button onclick='changeMonth(1)'>&raquo;</button>";
        $calendar .= "</div>";

        $calendar .= "<div class='calendar-grid'><div id='calendar-days'>";

        // Пустые дни перед началом месяца
        for ($i = 1; $i < $firstDayOfMonth; $i++) {
            $calendar .= "<div class='day empty'></div>";
        }

        // Генерация дней месяца
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $isToday = ($day == date('j') && $month == date('n') && $year == date('Y'));
            $todayClass = $isToday ? 'today' : '';
            $dayOfWeek = $daysOfWeek[($firstDayOfMonth + $day - 2) % 7];

            $calendar .= "<div class='day $todayClass' data-date='$year-$month-$day'>";
            $calendar .= "<div class='day__title'><p class='fs-12 fw-400'>$dayOfWeek</p> <p class='fs-12 fw-700'>$day</p></div>";
            $calendar .= "<div class='day__events'>" . $this->generateEvents($day, $month, $year) . "</div>";
            $calendar .= "</div>";
        }

        $calendar .= "</div></div></div>";

        return $calendar;
    }

    private function generateEvents($day, $month, $year)
    {
        // Эмуляция данных мероприятий, которые можно заменить запросом API
        $eventsData = [
            1 => [['id' => 1, 'name' => 'Event 1'], ['id' => 2, 'name' => 'Event 2']],
            2 => [['id' => 3, 'name' => 'Event 3']],
            3 => [],
        ];

        $events = $eventsData[$day] ?? [];
        if (empty($events)) {
            return '';
        }

        $html = '';
        foreach ($events as $event) {
            $color = $this->getColorForEvent($event['id']);
            $html .= "<div class='event'>
                <span style='background-color: $color; width: 10px; height: 10px; border-radius: 50%; display: block;'></span>
                <p class='fs-12 fw-400'>{$event['name']}</p>
            </div>";
        }

        return $html;
    }

    private function getColorForEvent($eventId)
    {
        $hash = md5($eventId);
        return '#' . substr($hash, 0, 6);
    }
}
