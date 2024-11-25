<?php

namespace Dibrovnik\SportTech\Console;

use Illuminate\Console\Command;
use DOMDocument;
use DOMXPath;

class SaveFile extends Command
{
    /**
     * The console command name.
     * 
     * @var string
     */
    protected $name = 'sporttech:save-file';

    /**
     * The console command description.
     * 
     * @var string
     */
    protected $description = 'Save data';

    /**
     * Execute the console command.
     * 
     * @return void
     */
   
    public function handle()
    {
        
        $api_url = "http://127.0.0.1:5000/download"; // Адрес вашего API
        $page_url = "https://www.minsport.gov.ru/activity/government-regulation/edinyj-kalendarnyj-plan/";

        $this->info("Загружаем страницу: {$page_url}");
        // Данные для POST-запроса
        $data = json_encode(["page_url" => $page_url]);

        // Настройка cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        // Выполнение запроса
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $result = json_decode($response, true);
            $this->info("Файл успешно загружен: " . $result['file_path']);
        } else {
             $this->info("Произошла ошибка: " . $response);
        }
        
    }
}
