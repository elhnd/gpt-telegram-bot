<?php
namespace App\Adapter\Impl;

use App\Adapter\DataStorageInterface;
use App\Dto\MessageDto;
use DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements DataStorageInterface
{
    public function __construct(private SessionInterface $session)
    {
        dump($session->all());
    }

    public function getData(string $id)
    {
        return $this->session->get('messages_' . $id, []);
    }

    public function setData(MessageDto $message)
    {
        // Stockez les messages de l'utilisateur dans la session
        $userId = 'messages_' . $message->getUserId();
        $messages = $this->session->get($userId, []);

        $messages[] = [
            'role' => $message->getRole(),
            'content' => $message->getContent(),
        ];

        $expireDate = new DateTime();
        $expireDate->modify('+24 hours');

        $this->session->set($userId, $messages, $expireDate);
    }
}
