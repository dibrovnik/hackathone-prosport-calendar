<?php namespace Dibrovnik\SportTech\Console;

use Illuminate\Console\Command;
use Dibrovnik\SportTech\Models\SportEvent;
use Dibrovnik\SportTech\Models\Discipline;
use Dibrovnik\SportTech\Models\Sport;
use Carbon\Carbon;

use Response;

use Smalot\PdfParser\Parser;

class ParseData extends Command
{
    /**
     * The console command name.
     * 
     * @var string
     */
    protected $name = 'sporttech:parse-data';

    /**
     * The console command description.
     * 
     * @var string
     */
    protected $description = 'Parse data from PDF file into JSON';

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle()
    {
        try {
            ini_set('memory_limit', '512M'); // Увеличиваем лимит памяти
            ini_set('max_execution_time', 300); // Увеличиваем время выполнения
            // Фиксированный путь к PDF-файлу
            $pdfPath = base_path('plugins/dibrovnik/sporttech/python/downloads/EKP-2024.pdf');;
            // $pdfPath = base_path('plugins/dibrovnik/sporttech/src/data/test.pdf');
            // $pdfPath = __DIR__ . '/test.pdf';

            // Проверка существования файла
            if (!file_exists($pdfPath)) {
                throw new \Exception('The PDF file does not exist at the specified path.');
            }

            // Функции для обработки
            $text = $this->extractTextFromPdf($pdfPath);

            $patterns = [
                'code' => '/(\d{16})/m',
                'title' => '/[А-ЯЁ\s\-\«\»\d()]+/u',
                'disciplines' => '/^(.*?)(?=\n\d{2}\.\d{2}\.\d{4})/us',
                // 'gender_age' => '/^(.*?)(?=\n[А-ЯЁ])/us',
                'gender_age' => '/^(.*?)((?=\n[А-ЯЁ])|(?=\n\d{2}\.\d{2}\.\d{4}))/us',
                'start_date' => '/\b\d{2}\.\d{2}\.\d{4}\b/',
                'country' => '/\n([А-ЯЁ\s]+)(?=\n)/u',
                'location' => '/^(.*?)(?=\n\d)/us',
                'participants' => '/\n\d+\n/',
            ];

            $sports = $this->extractSports($text);
            $events = $this->extractEvents($sports, $patterns);

            // Создание JSON-строки
            $jsonData = json_encode([
                'success' => true,
                'data' => $events,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // Сохранение JSON в файл
            // $outputPath = __DIR__ . '/output.json';
            $outputPath = base_path('plugins/dibrovnik/sporttech/src/data/output.json');
            file_put_contents($outputPath, $jsonData);

            // Возврат ответа
            $this->info('Parse OK. File saved to ' . $outputPath);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
    
    private function extractTextFromPdf($filePath)
    {
        return (new Parser())->parseFile($filePath)->getText();
    }

    private function extractSports($text)
    {
        $blocks = preg_split('/Основной состав/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sports = [];
        $previous_last_line = null;
        foreach ($blocks as $key => $block) {
            if ($key === 0) {
                continue;
            }

            $lines = preg_split('/\n/', trim($blocks[$key - 1]), -1, PREG_SPLIT_NO_EMPTY);
            $previous_last_line = end($lines);
            $before = $previous_last_line;
            if (preg_match('/^Стр\. \d+ из \d+$/', $previous_last_line)) {
                $before = $lines[count($lines) - 2];
            }
            $sports[$before] = $block;
        }
        return $sports;
    }

    private function extractEvents($sports, $pattern)
    {
        $result = [];
        foreach ($sports as $key => $sport) {
            $blocks = preg_split($pattern['code'], $sport, -1, PREG_SPLIT_DELIM_CAPTURE);

            for ($i = 0; $i < count($blocks); $i += 2) {
                $code = $blocks[$i + 1] ?? null;
                $block = $blocks[$i + 2] ?? null;

                $result[$key][$i] = [
                    'code' => $code ? trim($code) : null,
                    'title' => $block ? (preg_match($pattern['title'], trim($block), $match) ? $match[0] : null) : null,
                ];

                if (!$result[$key][$i]['title']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['title'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'gender_age' => $block ? (preg_match($pattern['gender_age'], $block, $match) ? $match[1] : null) : null,
                ]);
                if (!$result[$key][$i]['gender_age']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['gender_age'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'disciplines' => $block ? (preg_match($pattern['disciplines'], $block, $match) ? trim($match[1]) : null) : null,
                ]);

                if (!$result[$key][$i]['disciplines']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['disciplines'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'start_date' => $block ? (preg_match($pattern['start_date'], $block, $match) ? $match[0] : null) : null,
                ]);

                if (!$result[$key][$i]['start_date']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['start_date'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'end_date' => $block ? (preg_match($pattern['start_date'], $block, $match) ? $match[0] : null) : null,
                ]);

                if (!$result[$key][$i]['end_date']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['end_date'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'country' => $block ? (preg_match($pattern['country'], $block, $match) ? trim($match[0]) : null) : null,
                ]);

                if (!$result[$key][$i]['country']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['country'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'location' => $block ? (preg_match($pattern['location'], $block, $match) ? trim($match[0]) : null) : null,
                ]);

                if (!$result[$key][$i]['location']) {
                    continue;
                }

                $block = str_replace($result[$key][$i]['location'], '', $block);
                $result[$key][$i] = array_merge($result[$key][$i], [
                    'participants' => $block ? (preg_match($pattern['participants'], $block, $match) ? trim($match[0]) : null) : null,
                ]);
            }
        }
        return $result;
    }
}
