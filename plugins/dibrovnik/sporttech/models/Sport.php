<?php namespace Dibrovnik\SportTech\Models;

use Model;

/**
 * Model
 */
class Sport extends Model
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
    public $table = 'dibrovnik_sporttech_sports';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];
    
    // Связь с дисциплинами (многие ко многим)
    public $belongsToMany = [
        'disciplines' => [
            'Dibrovnik\SportTech\Models\Discipline',
            'table' => 'dibrovnik_sporttech_sport_discipline', // название промежуточной таблицы
            'primaryKey' => 'sport_id',    // ключ для спорта
            'otherKey' => 'discipline_id', // ключ для дисциплины
        ]
    ];
    public $fillable = ['name'];

    /**
     * Получить список видов спорта с ID
     *
     * @return array
     */
    public static function getSportOptions()
    {
        try {
            $sports = self::all(['id', 'name']);
            
            return response()->json($sports, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching sports: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching sports.'], 500);
        }
    }

    /**
     * Получить дисциплины по ID вида спорта
     *
     * @param int $sportId
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getDisciplinesBySportId($sportId)
    {
        try {
            $sport = self::find($sportId);

            if (!$sport) {
                return response()->json(['error' => 'Sport not found.'], 404);
            }

            $disciplines = $sport->disciplines()->get(['id', 'name']);

            return response()->json($disciplines, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching disciplines for sport ID ' . $sportId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching disciplines.'], 500);
        }
    }
}
