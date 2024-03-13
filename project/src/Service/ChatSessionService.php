<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ChatSessionService
{
    /**
     * Undocumented variable
     *
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * Undocumented function
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Undocumented function
     *
     * @param string $userId
     * @param string|array|mixed $messages
     * @return void
     */
    public function saveUserMessages(string $userId, $messages)
    {
        // Stockez les messages de l'utilisateur dans la session
        $this->session->set('messages_' . $userId, $messages);
    }

    /**
     * Undocumented function
     *
     * @param string $userId
     * @return mixed
     */
    public function getUserMessages(string $userId)
    {
        // Récupérez les messages de l'utilisateur stockés dans la session
        return $this->session->get('messages_' . $userId, []);
    }
}
