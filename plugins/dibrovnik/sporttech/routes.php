<?php

use  Dibrovnik\SportTech\Models\Sport;
use  Dibrovnik\SportTech\Models\SportEvent;
// use  Dibrovnik\SportTech\Models\Discipline;

Route::group([
    'prefix'     => 'api/v1',
    'namespace'  => 'Dibrovnik\SportTech\ApiControllers',
    'middleware' => 'cors'
], function() {
    Route::get('events', 'EventsController@events');
    Route::get('disciplines', 'DisciplineController@getAll');
    Route::get('events/date/{date}','EventsController@eventsByDay');
    Route::get('event/id/{id}','EventsController@eventById');
    Route::get('event/code/{code}','EventsController@eventByCode');
    Route::get('events/{year}/{month}', 'EventsController@eventsByMonth');
    Route::get('events-by-phone/{phone}', 'EventsController@getEventsByPhoneNumber');
    Route::get('sports', function () {
        return Sport::getSportOptions();
    });
    Route::get('sports/{sportId}/disciplines', function ($sportId) {
        return Sport::getDisciplinesBySportId($sportId);
    });

    Route::post('sportsmen/add-subscription', 'SportsmenController@addSubscription');
    Route::post('sportsmen/remove-subscription', 'SportsmenController@removeSubscription');
    Route::post('discipline', 'DisciplineController@create');
    Route::post('sport', 'SportController@create');

    // Route::get('', 'FilterController@renderPhp');
    Route::get('cities', function () {
        try {
            $cities = SportEvent::getUniqueCities();
            return response()->json([
                'success' => true,
                'data' => $cities
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    });
});


