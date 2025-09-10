<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class RewriteTextCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'text:rewrite {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrite text using ChatGPT and store result';

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
        $file = $this->argument('file');
        $sourceDir = config('services.text_gen.source_dir');

        if (!file_exists($sourceDir . $file)) {
            $this->error("File {$file} not found");
            return 1;
        }

        $text = file_get_contents($sourceDir . $file);

        $client = new Client([
            'base_uri' => config('services.openai.base_uri'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type'  => 'application/json',
            ],
        ]);

        $prompt = "Перепиши следующий текст на " . config('services.text_gen.rewrite_percent')
            . "%, сохраняя его суть и частные названия:\n\n" . $text;

        try {
            $response = $client->post('chat/completions', [
                'json' => [
                    'model'    => config('services.openai.model_name'), // можно заменить на gpt-4o или gpt-3.5-turbo
                    'messages' => [
                        ['role' => 'system', 'content' => 'Ты помощник, который переписывает тексты.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => config('services.openai.temperature'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $result = $data['choices'][0]['message']['content'] ?? null;

            if ($result) {
                $outputFile = $sourceDir . 'rewritten_' . basename($file);
                file_put_contents($outputFile, $result);
                $this->info("Result saved into {$outputFile}");
            } else {
                $this->error("Can't get response from API");
            }

        } catch (\Exception $e) {
            $this->error("Bad Request: " . $e->getMessage());
        }

        return 0;
    }
}
