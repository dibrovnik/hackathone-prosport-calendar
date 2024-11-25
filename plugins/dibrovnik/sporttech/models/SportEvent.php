<?php namespace Dibrovnik\SportTech\Models;

use Model;

/**
 * Model
 */
class SportEvent extends Model
{
    use \October\Rain\Database\Traits\Validation;

    // Связь с дисциплинами через промежуточную таблицу
    public $belongsToMany = [
        'disciplines' => [
            'Dibrovnik\SportTech\Models\Discipline',
            'table' => 'dibrovnik_sporttech_event_disciplines', // правильное имя таблицы связи
            'primaryKey' => 'event_id',    // используем правильный ключ
            'otherKey' => 'discipline_id', // ключ для дисциплины
        ]
    ];

    public $fillable = [
        'name',
        'gender',
        'age_group',
        'start_date',
        'end_date',
        'country',
        'location',
        'participants_count',
        'code',
        'region',
        'city'
    ];


    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_sporttech_sports_events';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /**
     * Get unique cities for filtering.
     *
     * @return array
     */
    public static function getUniqueCities()
    {
        // Получаем уникальные города из базы данных
        return self::query()
            ->distinct()
            ->pluck('country')
            ->filter() // Убираем возможные null или пустые значения
            ->unique() // Убедимся в уникальности (на случай повторов из-за pluck)
            ->values() // Переиндексация массива
            ->toArray(); // Преобразуем в массив
    }
}
