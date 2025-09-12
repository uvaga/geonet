<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.openai.base_uri'),
            'headers'  => [
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function rewriteText($text, $percent = 30)
    {
        try {
            $prompt = config('prompts.rewrite');
            $prompt = str_replace(':percent', $percent, $prompt);
            $prompt.= "\n\n{$text}";

            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model'       => config('services.openai.model_name'),
                    'messages'    => [
                        ['role' => 'system', 'content' => 'Ты помощник, который переписывает тексты.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => config('services.openai.temperature'),
                ],
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $body = (string) $e->getResponse()->getBody();
                dd("Request error:", $body);
            } else {
                dd("Error without response:", $e->getMessage());
            }
        }

        $result = json_decode($response->getBody(), true);
        return $result['choices'][0]['message']['content'] ?? null;
    }
}