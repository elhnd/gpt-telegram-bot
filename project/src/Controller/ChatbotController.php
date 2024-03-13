<?php

namespace App\Controller;

use App\Service\TelegramService;
use App\Service\WebhookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatbotController extends AbstractController
{
    public function __construct(
        private TelegramService $telegramService,
        private WebhookService $webhookService
    )
    {}

    #[Route('/webhook', name:'webhook')]
    public function webhook(Request $request)
    {
        $update = json_decode($request->getContent(), true);
        $this->telegramService->telegramToOpenAi($update['message']);
        return new Response("OK");
    }

    #[Route('/set-webhook', name:'set_webhook')]
    public function setWebhook(): Response
    {
        $this->webhookService->setWebhook();
        return new Response("Webhook set ok");
    }
}
