<?php namespace Dibrovnik\SportTech\Console;

use Illuminate\Console\Command;
use Dibrovnik\SportTech\Models\SportEvent;
use Dibrovnik\SportTech\Models\Discipline;
use Dibrovnik\SportTech\Models\Sport;
use Carbon\Carbon;

class ImportData extends Command
{
    /**
     * The console command name.
     * 
     * @var string
     */
    protected $name = 'sporttech:import-data';

    /**
     * The console command description.
     * 
     * @var string
     */
    protected $description = 'Import data from JSON file into the database';

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle()
    {
        // Путь к файлу
        $jsonFile = base_path('plugins/dibrovnik/sporttech/src/data/output.json');
        if (!file_exists($jsonFile)) {
            $this->error("JSON file not found: $jsonFile");
            return;
        }

        $data = json_decode(file_get_contents($jsonFile), true);
        if (!$data || !$data['success']) {
            $this->error('Invalid or empty JSON data.');
            return;
        }

        foreach ($data['data'] as $sportName => $events) {
            $sport = Sport::firstOrCreate(['name' => $sportName]);
            $this->info('Sport name: ' . $sportName);

            $count = 0;
            foreach ($events as $key => $eventData) {
                if (empty($eventData['code']))
                {
                    continue;
                }
                $count = $count + 1;
                $this->info($count . ' - Event: ' . $eventData['code']);

                // Преобразование даты
                $startDate = isset($eventData['start_date']) && !empty($eventData['start_date'])
                    ? Carbon::createFromFormat('d.m.Y', $eventData['start_date'])->format('Y-m-d')
                    : null;

                $endDate = isset($eventData['end_date']) && !empty($eventData['end_date'])
                    ? Carbon::createFromFormat('d.m.Y', $eventData['end_date'])->format('Y-m-d')
                    : null;

                if (empty($eventData['title']))
                {
                    continue;
                }
                if (strlen($eventData['title']) > 254) {
                    $this->warn("Discipline name too long, skipping: $disciplineName");
                    continue;
                }



                $sportEvent = SportEvent::updateOrCreate(
                    ['code' => $eventData['code']],
                    [
                        'name' => substr(trim($eventData['title']), 0, 254),
                        'gender' => $eventData['gender_age']  ?? null,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'country' => $eventData['country'] ?? null,
                        'location' => $eventData['location']  ?? null,
                        'participants_count' => $eventData['participants']  ?? null,
                    ]
                );

                $disciplineNames = explode(',', $eventData['disciplines']);
                foreach ($disciplineNames as $disciplineName) {
                    $disciplineName = trim($disciplineName);
                    if (strlen($disciplineName) > 254) {
                        $this->warn("Discipline name too long, skipping: $disciplineName");
                        continue;
                    }
                    // Создаём или обновляем дисциплину с указанием sport_id
                    $discipline = Discipline::updateOrCreate(
                        ['name' => substr(trim($disciplineName), 0, 254)],
                        ['sport_id' => $sport->id] // Привязываем к виду спорта
                    );

                    // Устанавливаем связь дисциплины с видом спорта
                    if (!$sport->disciplines()->where('discipline_id', $discipline->id)->exists()) {
                        $sport->disciplines()->attach($discipline->id);
                    }

                    // Устанавливаем связь мероприятия с дисциплиной
                    if (!$sportEvent->disciplines()->where('discipline_id', $discipline->id)->exists()) {
                        $sportEvent->disciplines()->attach($discipline->id);
                    }

                }
            }
        }

        $this->info('Data imported successfully!');
    }
}
