<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class BatchRewriteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:batch-rewrite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Batch request to OpenAI for texts rewrite';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apiKey = config('services.openai.api_key');
        $model  = config('services.openai.model_name');

        $inputDir  = config('services.text_gen.source_dir');
        $batchFile = storage_path('app/batch_input.jsonl');

        if (!is_dir($inputDir)) {
            $this->error("No directory {$inputDir}");
            return 1;
        }

        // === ШАГ 1. Генерация JSONL ===
        $fh = fopen($batchFile, 'w');
        foreach (glob($inputDir . '*.txt') as $file) {
            $text = file_get_contents($file);
            if ($text) {
                $customId = basename($file);

                $entry = [
                    'custom_id' => $customId,
                    'method'    => 'POST',
                    'url'       => '/v1/chat/completions',
                    'body'      => [
                        'model'       => $model,
                        'messages'    => [
                            ['role' => 'system', 'content' => 'Ты помощник, который переписывает тексты.'],
                            ['role' => 'user', 'content' => "Перепиши текст на " . config('services.text_gen.rewrite_percent') . "%, сохраняя смысл, ключи и частные названия, но сделай формулировки чуть более живыми и уникальными:\n\n{$text}"],
                        ],
                        'temperature' => config('services.openai.temperature'),
                    ],
                ];

                fwrite($fh, json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n");
            }
        }
        fclose($fh);
        $this->info("✅ File batch_input.jsonl created");

        $fSize = filesize($batchFile);
        if (!$fSize) {
            $this->error('Batch file is empty');
            return 1;
        }

        // === ШАГ 2. Отправка файла ===
        $client = new Client([
            'base_uri' => config('services.openai.base_uri'),
            'headers'  => [
                'Authorization' => 'Bearer ' . $apiKey,
            ],
        ]);

        $response = $client->post('files', [
            'multipart' => [
                ['name' => 'file', 'contents' => fopen($batchFile, 'r')],
                ['name' => 'purpose', 'contents' => 'batch'],
            ],
        ]);
        $fileData = json_decode($response->getBody(), true);
        $inputFileId = $fileData['id'] ?? null;
        if ($inputFileId) {
            $this->info("📤 File uploaded: {$inputFileId}");
        } else {
            $this->error('Failed to upload file ' . $inputFileId);
            return 1;
        }

        // === ШАГ 3. Создание Batch ===
        try {
            $response = $client->post('batches', [
                'json' => [
                    'input_file_id'     => $inputFileId,
                    'endpoint'          => '/v1/chat/completions',
                    'completion_window' => config('services.openai.completion_window'),
                    'metadata'          => ['task' => 'rewrite'],
                ],
            ]);
            $batchData = json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $body = (string) $e->getResponse()->getBody();
                dd("Request error:", $body);
            } else {
                dd("Error without response:", $e->getMessage());
            }
        }
        $batchId = $batchData['id'] ?? null;

        if ($batchId) {
            $this->info("📝 Batch created: {$batchId}");
        } else {
            $this->error('Failed to create Batch');
            return 1;
        }
        $this->info("⏳ Проверяйте статус: php artisan openai:batch-check {$batchId}");

        return 0;
    }
}
