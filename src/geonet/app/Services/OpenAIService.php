<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

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

    public function rewriteText(string $text, $percent = 30): array
    {
        try {
            $prompt = config('prompts.rewrite');
            $prompt = str_replace(':percent', $percent, $prompt);
            $prompt.= "\n\n{$text}";

            $requestBody =[
                'json' => [
                    'model'       => config('services.openai.model_name'),
                    'messages'    => [
                        ['role' => 'system', 'content' => config('prompts.system_role')],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => config('services.openai.temperature'),
                ],
            ];
            $response = $this->client->post('chat/completions', $requestBody);
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $result = json_decode($response->getBody(), true);
                if (!empty($result['error'])) {
                    $body = null;
                    $status = $response->getBody();
                } else {
                    $body = $result['choices'][0]['message']['content'] ?? null;
                    $status = $result['choices'][0]['finish_reason'] ?? null;
                }
            } else {
                $body = 'Error response code: ' . $response->getStatusCode() . ' Body:' . $response->getBody();
                $status = 'failed';
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $body = 'Request error:' . $e->getResponse()->getBody();
            } else {
                $body = 'Error without response:' . $e->getMessage();
            }
            $status = 'failed';
        }

        return [
            'text' => $body ?? null,
            'status' => $status ?? null,
        ];
    }
}