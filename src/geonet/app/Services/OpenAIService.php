<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;

class OpenAIService
{
    /** @var Client */
    protected $client;

    public function __construct(string $appName)
    {
        $this->client = new Client([
            'base_uri' => config('services.openai.base_uri'),
            'headers'  => [
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type'  => 'application/json',
                'X-Title'       => $appName,
                'HTTP-Referer'  => "https://{$appName}/",
            ],
        ]);
    }

    public function rewriteText(string $text, string $pageContentTitle, $percent = 30): array
    {
        try {
            $prompt = config('prompts.rewrite');
            $prompt = str_replace([':percent', ':page_content_title'], [$percent, $pageContentTitle], $prompt);
            $prompt.= "\n{$text}";

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
            $body = null;
            $response = $this->client->post('chat/completions', $requestBody);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $result = json_decode($response->getBody(), true);
                if (!empty($result['error'])) {
                    $status = $response->getBody();
                } elseif (!empty($result['choices'][0]['error']['message'])) {
                    $status = $result['choices'][0]['error']['message'] . ' Code: ' . ($result['choices'][0]['error']['code'] ?? '');
                } else {
                    $body = $result['choices'][0]['message']['content'] ?? null;
                    $status = $result['choices'][0]['finish_reason'] ?? null;
                }
            } else {
                $status = 'Error response code: ' . $response->getStatusCode() . ' Body:' . $response->getBody();
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $status = 'Request error:' . $e->getResponse()->getBody();
            } else {
                $status = 'Error without response:' . $e->getMessage();
            }
        }

        return [
            'text' => $body ?? null,
            'status' => $status ?? null,
        ];
    }
}