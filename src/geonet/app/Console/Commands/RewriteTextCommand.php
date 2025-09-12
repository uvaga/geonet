<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenAIService;

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
    public function handle(OpenAIService $openAI)
    {
        $file = $this->argument('file');
        $sourceDir = config('services.text_gen.source_dir');

        if (!file_exists($sourceDir . $file)) {
            $this->error("File {$file} not found");
            return 1;
        }

        $text = file_get_contents($sourceDir . $file);

        try {
            $result = $openAI->rewriteText($text, config('services.text_gen.rewrite_percent'));

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
