<?php namespace Dibrovnik\SportTech;

use System\Classes\PluginBase;
use RainLab\User\Models\User;
use Dibrovnik\SportTech\Models\Sportsman;
use Event;
use Illuminate\Console\Scheduling\Schedule;
use Db;

class Plugin extends PluginBase
{
    public $require = ['Octobro.API'];

    public function register()
    {
        $this->registerConsoleCommand('sporttech.import-data', \Dibrovnik\SportTech\Console\ImportData::class);
        $this->registerConsoleCommand('sporttech.save-file', \Dibrovnik\SportTech\Console\SaveFile::class);
        $this->registerConsoleCommand('sporttech.parse-file', \Dibrovnik\SportTech\Console\ParseData::class);
    }

    public function boot()
    {
        Event::listen('rainlab.user.register', function ($component, $user) {
            $input_tel = post('tel');
            $phone = ltrim($input_tel, '+');
            Db::table('dibrovnik_sporttech_sportsmans')->insert([
                'user_id' => $user->id,
                'sub_events' => null,
                'phone' => $phone
            ]);
        });

        // Добавляем расписание
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Команда для сохранения файла
            $schedule->command('sporttech:save-file')
                ->monthlyOn(1, '00:00') // Выполнять 1 числа каждого месяца
                ->description('Сохранить файл с веб-страницы.');

            // Команда для парсинга данных
            $schedule->command('sporttech:parse-data')
                ->monthlyOn(1, '01:00') // Выполнять 2 числа каждого месяца
                ->description('Парсить данные из PDF в JSON.');

            // Команда для импорта данных
            $schedule->command('sporttech:import-data')
                ->monthlyOn(1, '02:00') // Выполнять 3 числа каждого месяца
                ->description('Импортировать данные из JSON в базу.');
        });
    }

    public function registerComponents()
    {
         return [
            Components\Calendar::class => 'calendar',
            'Dibrovnik\SportTech\Components\DetailPageComponent' => 'detailPage',
        ];
    }

    public function registerSettings()
    {
    }
}
