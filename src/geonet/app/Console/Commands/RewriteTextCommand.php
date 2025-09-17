<?php

namespace App\Console\Commands;

use App\Models\{PageTopic, AiRewriteLog};
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
                $error = "File {$sourceFile} not found";
                $this->error($error);
                $aiRewriteLog = new AiRewriteLog(['page_topic_id' => $pageTopic->id, 'status' => $error]);
                $aiRewriteLog->save();
                $pageTopic->ai_updated_date = now();
                $pageTopic->save();
                continue;
            }

            $text = file_get_contents($sourceFile);

            try {
                $result = $openAI->rewriteText($text, config('services.text_gen.rewrite_percent'));

                if ($result['text'] && strlen($result['text']) > 100 && $result['status'] === 'stop') {
                    $outputFile = $sourceFile;
                    file_put_contents($outputFile, $result['text']);
                    $this->info("Result saved into {$outputFile}");
                    $status = $result['status'];

                    $pageTopic->ai_updated_date = now();
                    $pageTopic->save();
                } else {
                    $this->error("Can't get response from API");
                    $status = "Can't get response from API. " . $result['status'];
                }
                $aiRewriteLogData = ['page_topic_id' => $pageTopic->id, 'status' => $status];
            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage());
                $aiRewriteLogData = ['page_topic_id' => $pageTopic->id, 'status' => "Error: " . $e->getMessage()];
            } finally {
                $aiRewriteLog = new AiRewriteLog($aiRewriteLogData);
                $aiRewriteLog->save();
            }
        }

        return 0;
    }
}
