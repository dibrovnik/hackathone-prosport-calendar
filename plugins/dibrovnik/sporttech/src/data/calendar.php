<?php

function generateEvents(
  $day,
  $month,
  $year,
  $eventsData
) {
  $eventsData;

  $maxVisibleEvents = 3;
  $totalEvents = count($eventsData->$day);
  $events = '';

  if (count($eventsData->$day) === 0) {
    return $events;
  }


  for ($i = 1; $i <= min($totalEvents, $maxVisibleEvents - 1); $i++) {
    $name = $eventsData->$day;
    $name = $name[$i - 1]->name;
    $color = getColorForEvent($eventsData->$day[$i - 1]->id);

    $events .= "<div class='event'>
        <span style='background-color: $color; width: 10px; height: 10px; border-radius: 50%; display: block; aspect-ratio: 1;'></span>
        <p class='fs-12 fw-400'>" . $name . "</p>
      </div>";
  }

  if ($totalEvents > $maxVisibleEvents - 1) {
    $extraEvents = $totalEvents - $maxVisibleEvents;
    $name = $eventsData->$day;
    $name = $name[$maxVisibleEvents - 1]->name;
    $color = getColorForEvent($eventsData->$day[$maxVisibleEvents - 1]->id);


    $events .= "<div class='event__row'>";

    $events .= "<div class='event'>
        <span style='background-color: $color; width: 10px; height: 10px; border-radius: 50%; display: block; aspect-ratio: 1;'></span>
        <p class='fs-12 fw-400'>$name</p>
      </div>";

    if ($extraEvents > 0) {
      $events .= "<div class='event extra'>
            <p class='fs-12 fw-400'>+$extraEvents</p>
          </div>";
    }

    $events .= "</div>";
  }

  return $events;
}
function generateCalendar($month, $year)
{
  $daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

  $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

  $firstDayOfMonth = date('N', strtotime("$year-$month-01"));


  $months = [
    1 => 'январь',
    'февраль',
    'март',
    'апрель',
    'май',
    'июнь',
    'июль',
    'август',
    'сентябрь',
    'октябрь',
    'ноябрь',
    'декабрь'
  ];
  $monthName = $months[$month];

  $calendar = "<div id='calendar-container'>";
  $calendar .= "<div class='calendar-header'>";
  $calendar .= "<div id='current-month' class='fs-16 fw-700'>$monthName $year</div>";
  $calendar .= "<button onclick='changeMonth(-1)'>
  <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
  <path d='M16.146 4.93887C16.2854 5.07817 16.396 5.24358 16.4714 5.42563C16.5469 5.60768 16.5857 5.80281 16.5857 5.99987C16.5857 6.19694 16.5469 6.39207 16.4714 6.57412C16.396 6.75617 16.2854 6.92157 16.146 7.06087L11.561 11.6469C11.4673 11.7406 11.4146 11.8678 11.4146 12.0004C11.4146 12.133 11.4673 12.2601 11.561 12.3539L16.146 16.9389C16.4274 17.2201 16.5855 17.6017 16.5856 17.9995C16.5857 18.3974 16.4278 18.779 16.1465 19.0604C15.8653 19.3418 15.4837 19.4999 15.0859 19.5C14.688 19.5001 14.3064 19.3421 14.025 19.0609L9.43901 14.4749C9.11397 14.1499 8.85613 13.764 8.68022 13.3393C8.5043 12.9147 8.41376 12.4595 8.41376 11.9999C8.41376 11.5402 8.5043 11.0851 8.68022 10.6604C8.85613 10.2357 9.11397 9.84988 9.43901 9.52487L14.025 4.93887C14.3063 4.65767 14.6878 4.49969 15.0855 4.49969C15.4833 4.49969 15.8647 4.65767 16.146 4.93887Z' fill='#212121'/>
</svg>
</button>";
  $calendar .= "<button onclick='changeMonth(1)'>
  <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
  <path d='M7.85399 4.93887C7.7146 5.07817 7.60402 5.24358 7.52858 5.42563C7.45314 5.60768 7.41431 5.80281 7.41431 5.99987C7.41431 6.19694 7.45314 6.39207 7.52858 6.57412C7.60402 6.75617 7.7146 6.92157 7.85399 7.06087L12.439 11.6469C12.5327 11.7406 12.5854 11.8678 12.5854 12.0004C12.5854 12.133 12.5327 12.2601 12.439 12.3539L7.85399 16.9389C7.57259 17.2201 7.41445 17.6017 7.41436 17.9995C7.41427 18.3974 7.57222 18.779 7.85349 19.0604C8.13475 19.3418 8.51627 19.4999 8.91413 19.5C9.31199 19.5001 9.69359 19.3421 9.97499 19.0609L14.561 14.4749C14.886 14.1499 15.1439 13.764 15.3198 13.3393C15.4957 12.9147 15.5862 12.4595 15.5862 11.9999C15.5862 11.5402 15.4957 11.0851 15.3198 10.6604C15.1439 10.2357 14.886 9.84988 14.561 9.52487L9.97499 4.93887C9.6937 4.65767 9.31223 4.49969 8.91449 4.49969C8.51674 4.49969 8.13528 4.65767 7.85399 4.93887Z' fill='#212121'/>
</svg>
</button>";
  $calendar .= "<div class='calendar-filter-button'>
  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16' fill='none'>
  <g clip-path='url(#clip0_1_203)'>
    <path d='M12.3333 1.33333H12V1C12 0.734784 11.8946 0.48043 11.7071 0.292893C11.5196 0.105357 11.2652 0 11 0V0C10.7348 0 10.4804 0.105357 10.2929 0.292893C10.1054 0.48043 10 0.734784 10 1V1.33333H6V1C6 0.734784 5.89464 0.48043 5.70711 0.292893C5.51957 0.105357 5.26522 0 5 0V0C4.73478 0 4.48043 0.105357 4.29289 0.292893C4.10536 0.48043 4 0.734784 4 1V1.33333H3.66667C2.69421 1.33333 1.76158 1.71964 1.07394 2.40728C0.386308 3.09491 0 4.02754 0 5L0 12.3333C0 13.3058 0.386308 14.2384 1.07394 14.9261C1.76158 15.6137 2.69421 16 3.66667 16H12.3333C13.3058 16 14.2384 15.6137 14.9261 14.9261C15.6137 14.2384 16 13.3058 16 12.3333V5C16 4.02754 15.6137 3.09491 14.9261 2.40728C14.2384 1.71964 13.3058 1.33333 12.3333 1.33333ZM12.3333 14H3.66667C3.22464 14 2.80072 13.8244 2.48816 13.5118C2.17559 13.1993 2 12.7754 2 12.3333V6.66667H14V12.3333C14 12.7754 13.8244 13.1993 13.5118 13.5118C13.1993 13.8244 12.7754 14 12.3333 14Z' fill='#1E88E5'/>
  </g>
  <defs>
    <clipPath id='clip0_1_203'>
      <rect width='16' height='16' fill='white'/>
    </clipPath>
  </defs>
</svg>
<p class='fs-14 fw-400'>Фильтры</p>
</div>";
  $calendar .= "</div>";

  $calendar .= "<div class='calendar-grid'>";

  $calendar .= "<div id='calendar-days'>";

  $opts = [
    "http" => [
      "method" => "GET",
      "header" => "Accept-language: ru\r\n" .
        "Cookie: foo=bar\r\n"
    ]
  ];

  $context = stream_context_create($opts);

  $query = getQueries();
  $url = 'https://dev-level.ru/api/v1/events/' . $year . '/' . $month . '/' . '?' . $query;
  $eventsData = json_decode(file_get_contents($url));
  for ($i = 1; $i < $firstDayOfMonth; $i++) {
    $calendar .= "<div class='day empty'></div>";
  }

  for ($day = 1; $day <= $daysInMonth; $day++) {
    $isToday = ($day == date('j') && $month == date('n') && $year == date('Y'));
    $dayOfWeek = $daysOfWeek[date('N', strtotime("$year-$month-$day")) - 1];
    $todayClass = $isToday ? 'today' : '';


    $calendar .=
      "<div data-date='$year-$month-$day' class='day $todayClass'>
      <div class='day__title'><p class='fs-12 fw-400'>$dayOfWeek</p> <p class='fs-12 fw-700'>$day</p></div>
      <div class='day__events_color' style='margin-bottom: auto;'>" .
      generateEventLine($eventsData->events_by_day, $day)
      . "</div>
      <div class='day__events'>
      " .
      generateEvents($day, $month, $year, $eventsData->events_by_day)
      .
      "</div>
    </div>";
  }



  $calendar .= "</div></div></div>";
  return $calendar;
}
function getColorForEvent($eventId)
{
  $hash = md5($eventId);
  return '#' . substr($hash, 0, 6);
}
function generateEventLine($eventsData, $day)
{
  $eventLines = '';
  if (count($eventsData->$day) === 0) {
    return $eventLines;
  }
  foreach ($eventsData->$day as $value) {
    $color = getColorForEvent($value->id);
    // $start_date = @str_split(@$value->start_date, 2)[0];
    // $end_date = @str_split(@$value->end_date, 2)[0];
    // if ($start_date <= $day && @$end_date >= $day) {
    $eventLines .= "<span data-day='$day' style='display: block; background-color: $color; height: 5px;'></span>";
    // }
  }
  return $eventLines;
}

function getQueries()
{
  $query = '';
  foreach ($_GET as $key => $value) {
    $query .= "$key=$value&";
  }
  return isset($query) ? str_replace(' ', '%20', $query) : '';
}

$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

if ($month < 1) {
  $month = 12;
  $year--;
} elseif ($month > 12) {
  $month = 1;
  $year++;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <main>
    <div class="wrapper calendar">
      <div class="calendar__title">
        <h2 class="fs-36 fw-500">Календарь мероприятий</h2>
      </div>
      <? echo generateCalendar($month, $year); ?>
    </div>
    <dialog id="modal_day" class="_modal">
      <div class="_modal__inner">
        <div class="_modal__title">
          <h2 class="fs-16 fw-700 dayOfWeek" style="text-transform: capitalize"></h2>
          <button class="modal__close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path
                d="M14.1211 11.9998L18.0001 8.11682C18.2519 7.83058 18.3853 7.45918 18.3732 7.07811C18.361 6.69705 18.2042 6.33491 17.9346 6.06532C17.665 5.79573 17.3029 5.63891 16.9218 5.62674C16.5407 5.61458 16.1693 5.74797 15.8831 5.99982L12.0001 9.87882L8.11008 5.98782C7.97074 5.84849 7.80533 5.73796 7.62329 5.66256C7.44124 5.58715 7.24612 5.54834 7.04908 5.54834C6.85203 5.54834 6.65691 5.58715 6.47487 5.66256C6.29282 5.73796 6.12741 5.84849 5.98808 5.98782C5.84874 6.12715 5.73822 6.29256 5.66281 6.47461C5.58741 6.65666 5.5486 6.85177 5.5486 7.04882C5.5486 7.24587 5.58741 7.44098 5.66281 7.62303C5.73822 7.80508 5.84874 7.97049 5.98808 8.10982L9.87908 11.9998L6.00008 15.8818C5.84795 16.0178 5.72517 16.1834 5.63924 16.3685C5.55331 16.5536 5.50605 16.7542 5.50034 16.9582C5.49463 17.1621 5.5306 17.3651 5.60604 17.5547C5.68148 17.7443 5.79481 17.9165 5.9391 18.0608C6.08338 18.2051 6.25558 18.3184 6.44517 18.3939C6.63476 18.4693 6.83775 18.5053 7.04172 18.4996C7.24569 18.4938 7.44635 18.4466 7.63142 18.3607C7.81649 18.2747 7.98209 18.1519 8.11808 17.9998L12.0001 14.1208L15.8781 17.9998C16.1595 18.2812 16.5411 18.4393 16.9391 18.4393C17.337 18.4393 17.7187 18.2812 18.0001 17.9998C18.2815 17.7184 18.4396 17.3368 18.4396 16.9388C18.4396 16.5409 18.2815 16.1592 18.0001 15.8778L14.1211 11.9998Z"
                fill="#212121" />
            </svg>
          </button>
        </div>
        <div class="modal__events" id="eventsContainer">

        </div>
    </dialog>
  </main>
  <!-- <script src="./assets/js/script.js"></script> -->
</body>

</html>