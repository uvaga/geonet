<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class BatchCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai:batch-check {batch_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Batch status and download result';

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
        $apiKey   = config('services.openai.api_key');
        $batchId  = $this->argument('batch_id');
        $outputDir = storage_path('app/output');

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $client = new Client([
            'base_uri' => config('services.openai.base_uri'),
            'headers'  => ['Authorization' => 'Bearer ' . $apiKey],
        ]);

        // === Проверка статуса ===
        $response = $client->get("batches/{$batchId}");
        $batchData = json_decode($response->getBody(), true);

        $this->info("Статус: " . $batchData['status']);

        if ($batchData['status'] === 'completed') {
            $fileId = $batchData['output_file_id'];

            // === Скачивание результатов ===
            $response = $client->get("files/{$fileId}/content");
            $outputFile = storage_path('app/batch_output.jsonl');
            file_put_contents($outputFile, $response->getBody());
            $this->info("✅ Результаты сохранены в {$outputFile}");

            // === Разбор по файлам ===
            foreach (file($outputFile) as $line) {
                $row = json_decode($line, true);
                $customId = $row['custom_id'];
                $result = $row['response']['body']['choices'][0]['message']['content'] ?? null;

                if ($result) {
                    file_put_contents("{$outputDir}/rewritten_{$customId}", $result);
                    $this->info("📂 rewritten_{$customId} сохранён");
                }
            }
        }
        return 0;
    }
}
