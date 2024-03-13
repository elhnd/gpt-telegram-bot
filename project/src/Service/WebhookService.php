<?php
namespace App\Service;

use Telegram\Bot\Api;

class WebhookService
{
    public function __construct(
        private string $webhookUrl,
        private Api $telegram
    )
    {}

    public function setWebhook(): string
    {
        $this->telegram->setWebhook(['url' => $this->webhookUrl]);
        return $this->webhookUrl;
    }
}
