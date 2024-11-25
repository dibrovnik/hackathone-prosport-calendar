<?php namespace Dibrovnik\SportTech\Models;

use dibrovnik\sporttech\models\Discipline;
use Model;

/**
 * Model
 */
class Discipline extends Model
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
    public $table = 'dibrovnik_sporttech_disciplines';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    // Связь с видом спорта (многие к одному)
    public $belongsTo = [
        'sport' => 'Dibrovnik\SportTech\Models\Sport'
    ];

    // Связь с мероприятиями через промежуточную таблицу
    public $belongsToMany = [
        'sport_events' => [
            'Dibrovnik\SportTech\Models\SportEvent',
            'table' => 'dibrovnik_sporttech_event_disciplines', // правильное имя таблицы связи
            'primaryKey' => 'discipline_id', // ключ для дисциплины
            'otherKey' => 'event_id',        // используем правильный ключ для мероприятия
        ]
    ];
    public $fillable = ['name', 'sport_id'];

}
