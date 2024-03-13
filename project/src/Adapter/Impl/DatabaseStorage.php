<?php
namespace App\Adapter\Impl;

use App\Adapter\DataStorageInterface;
use App\Dto\MessageDto;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseStorage implements DataStorageInterface
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    public function getData(string $id)
    {
        $messageRepository = $this->em->getRepository(Message::class);

        $messages = $messageRepository->findBy(
            [
                'userId' => $id
            ],
            [
                'date' => 'DESC'
            ],
            3
        );

        return array_map(function ($message) {
            return [
                'role' => $message->getRole(),
                'content' => $message->getContent(),
            ];
        }, $messages);
    }

    public function setData(MessageDto $message)
    {
        $message = new Message(
            $message->getUserId(),
            $message->getUsername(),
            $message->getRole(),
            $message->getContent()
        );

        $this->em->persist($message);
        $this->em->flush();
    }
}