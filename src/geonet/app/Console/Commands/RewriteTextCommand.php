<?php

namespace App\Console\Commands;

use App\Models\PageTopic;
use App\Services\OpenAIService;
use Illuminate\Console\Command;

class RewriteTextCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'text:rewrite';

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
        $sourceDir = config('services.text_gen.source_dir');

        $pageTopics = PageTopic::oldAiUpdated(config('services.text_gen.ai_rewrite_days'))->get();

        /** @var PageTopic $pageTopic */
        foreach ($pageTopics as $pageTopic) {
            $sourceFile = $sourceDir . $pageTopic->site_id . '/' . $pageTopic->id . '.txt';
            if (!file_exists($sourceFile)) {
                $this->error("File {$sourceFile} not found");
                continue;
            }
            //TODO create table with logs (topic_id, date, result)
            $text = file_get_contents($sourceFile);

            try {
                $result = $openAI->rewriteText($text, config('services.text_gen.rewrite_percent'));

                if ($result) {
                    $outputFile = $sourceFile . '_rewritten_';
                    file_put_contents($outputFile, $result);
                    $this->info("Result saved into {$outputFile}");
                } else {
                    $this->error("Can't get response from API");
                }

            } catch (\Exception $e) {
                $this->error("Bad Request: " . $e->getMessage());
            }
        }

        return 1;





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
