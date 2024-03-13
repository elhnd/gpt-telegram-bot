<?php
namespace App\Service;

use App\Adapter\StorageAdapter;
use App\Dto\MessageDto;
use App\Exception\OpenAiException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message as TelegramMessage;

class TelegramService
{
    /**
     * Undocumented variable
     *
     * @var array|object
     */
    private array|object $message;
    
    /**
     * Undocumented function
     *
     * @param OpenAiService $openAiService
     * @param EntityManagerInterface $em
     * @param Api $telegram
     * @param StorageAdapter $storage
     */
    public function __construct(
        private OpenAiService $openAiService,
        private EntityManagerInterface $em,
        private Api $telegram,
        private StorageAdapter $storage
    )
    {}

    /**
     * Undocumented function
     *
     * @param array|object $message
     * @return void
     */
    public function setMessage(array|object $message)
    {
        $this->message = $message;
    }

    /**
     * Undocumented function
     *
     * @return TelegramMessage
     */
    private function createTelegramMessage(): TelegramMessage
    {
        return new TelegramMessage($this->message);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUserId(): string
    {
        return $this->createTelegramMessage()->getChat()->getId();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUsername(): string
    {
        $user = $this->createTelegramMessage()->getFrom();
        return $user->getFirstName()." ".$user->getLastName();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUserText(): string
    {
        return $this->createTelegramMessage()->getText();
    }

    /**
     * Undocumented function
     *
     * @param string|null $message
     * @return void
     */
    public function sendMessage(?string $message): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->getUserId(),
            'text' => $message
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $action
     * @return void
     */
    public function sendChatAction(string $action): void
    {
        $this->telegram->sendChatAction([
            'chat_id' => $this->getUserId(),
            'action' => $action
        ]);
    }

    /**
     * Undocumented function
     *
     * @param string $role
     * @param string|null $messageContentText
     * @return void
     */
    private function saveMessage(string $role, ?string $messageContentText = null): void
    {
        $messageDto = new MessageDto(
            $this->getUserId(),
            $this->getUsername(),
            $role,
            $messageContentText ?? $this->getUserText()
        );

        $this->storage->setData($messageDto);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function saveUserMessage(): void
    {
        $this->saveMessage('user');
    }

    /**
     * Undocumented function
     *
     * @param string $messageContentText
     * @return void
     */
    public function saveAssistantMessage(string $messageContentText): void
    {
        $this->sendEndSaveMessage('assistant', $messageContentText);
    }

    /**
     * Undocumented function
     *
     * @param string $role
     * @param string|null $messageContentText
     * @return void
     */
    private function sendEndSaveMessage(string $role, ?string $messageContentText = null): void
    {
        $this->sendMessage($messageContentText);
        $this->saveMessage($role, $messageContentText);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function findLatestUserAndAssistantDiscussions(): array
    {
        return $this->storage->getData(
            $this->createTelegramMessage()
                ->getChat()
                ->getId()
        );
    }

    /**
     * Undocumented function
     *
     * @param array|object $message
     * @return void
     */
    public function telegramToOpenAi(array|object $message): void
    {
        $this->setMessage($message);
        $this->sendChatAction('typing');

        $messages = $this->findLatestUserAndAssistantDiscussions();
        
        $messages = array_reverse($messages);

        if (isset($message["text"])) {
            try {
                $gptResponse = $this->openAiService->generateResponse($this->getUserText(), $messages);
            } catch (Exception $th) {
                throw new OpenAiException($th->getMessage());
            }
            
            $this->saveUserMessage();
            $this->saveAssistantMessage($gptResponse);
        } else {
            $this->sendMessage("Désolé je ne supporte que du texte !");
        }
    }
}
