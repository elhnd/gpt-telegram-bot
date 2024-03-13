<?php
namespace App\Service;

use App\Exception\OpenAiException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;

class OpenAiService
{
    private $httpClient;

    public function __construct(
        private string $apiKey,
        private string $url,
        private string $model,
        private AboutMe $aboutMe
    )
    {
        $this->httpClient = HttpClient::create();
    }

    public function generateResponse(string $prompt, array $fowardPrompt = []): string
    {
        $messages = [
            [
                'role' => 'system',
                'content' => "Réponds aux questions sur le CV de El Hadji NDIAYE et sur d'autres sujets."
            ]
        ];

        $messages[] = [
            'role' => 'user',
            'content' => "CV:\n{$this->aboutMe->getMe()}"
        ];

         // Ajouter le nouveau message de l'utilisateur à la conversation
         if (!empty($fowardPrompt)) {
            $messages = array_merge($messages, $fowardPrompt);
         }

         $messages[] = [
            'role' => 'user',
            'content' => "\n\nQuestion: {$prompt}\nRéponse:"
        ];

        return $this->requestOpenAi($messages)['choices'][0]['message']['content'];
    }

    private function requestOpenAi(array $messages)
    {
        try {
            $response = $this->httpClient->request('POST', $this->url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model, // Vous pouvez utiliser un autre modèle si vous le souhaitez
                    'messages' => $messages,
                    'max_tokens' => 300, // Limite la réponse à un certain nombre de tokens
                    'temperature' => 0.6, // Ajuste la créativité de la réponse
                ],
            ]);
            dump($response->toArray());

            return $response->toArray();

        } catch (ClientException $e) {
            throw new OpenAiException($e->getResponse()->getContent());
        } catch (\Throwable $th) {
            throw new OpenAiException($th->getMessage());
        }
    }
}
