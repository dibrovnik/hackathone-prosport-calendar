<?php

namespace Dibrovnik\SportTech\Helpers;

require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

class PdfHelper
{
    /**
     * Основной метод для обработки PDF и возврата готовых данных
     *
     * @param string $filePath Путь к PDF-файлу
     * @return array Массив данных для JSON
     */
    public static function processPdf(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['error' => 'Файл PDF не найден.'];
        }

        try {
            $text = self::extractTextFromPdf($filePath);
            $sports = self::extractSports($text);
            $events = self::extractEvents($sports, self::getPatterns());
            return ['events' => $events];
        } catch (\Exception $e) {
            return ['error' => 'Ошибка обработки PDF.', 'details' => $e->getMessage()];
        }
    }

    /**
     * Извлекает текст из PDF
     *
     * @param string $filePath Путь к PDF
     * @return string Извлечённый текст
     */
    private static function extractTextFromPdf(string $filePath): string
    {
        $parser = new Parser();
        return $parser->parseFile($filePath)->getText();
    }

    /**
     * Разделяет текст на блоки спорта
     *
     * @param string $text Текст из PDF
     * @return array Массив блоков спорта
     */
    private static function extractSports(string $text): array
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
            $sports[$before] = $block;
        }

        return $sports;
    }

    /**
     * Извлекает события из блоков спорта
     *
     * @param array $sports Массив блоков спорта
     * @param array $pattern Шаблоны регулярных выражений
     * @return array Массив событий
     */
    private static function extractEvents(array $sports, array $pattern): array
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
                    'disciplines' => $block ? (preg_match($pattern['disciplines'], $block, $match) ? trim($match[1]) : null) : null,
                    'start_date' => $block ? (preg_match($pattern['start_date'], $block, $match) ? $match[0] : null) : null,
                    'end_date' => $block ? (preg_match($pattern['start_date'], $block, $match) ? $match[0] : null) : null,
                    'country' => $block ? (preg_match($pattern['country'], $block, $match) ? trim($match[0]) : null) : null,
                    'location' => $block ? (preg_match($pattern['location'], $block, $match) ? trim($match[0]) : null) : null,
                    'participants' => $block ? (preg_match($pattern['participants'], $block, $match) ? trim($match[0]) : null) : null,
                ]);
            }
        }

        return $result;
    }

    /**
     * Возвращает шаблоны регулярных выражений
     *
     * @return array Шаблоны
     */
    private static function getPatterns(): array
    {
        return [
            'code' => '/(\d{16})/m',
            'title' => '/[А-ЯЁ\s\-\«\»\d()]+/u',
            'disciplines' => '/^(.*?)(?=\n\d{2}\.\d{2}\.\d{4})/us',
            'gender_age' => '/^(.*?)(?=\n[А-ЯЁ])/u',
            'start_date' => '/\b\d{2}\.\d{2}\.\d{4}\b/',
            'country' => '/\n([А-ЯЁ\s]+)(?=\n)/u',
            'location' => '/^(.*?)(?=\n\d)/us',
            'participants' => '/\n\d+\n/',
        ];
    }
}
