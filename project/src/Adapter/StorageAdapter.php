<?php
namespace App\Adapter;

use App\Dto\MessageDto;

class StorageAdapter
{

    public function __construct(private DataStorageInterface $storage)
    {}

    public function getData(string $id)
    {
        return $this->storage->getData($id);
    }

    public function setData(MessageDto $message)
    {
        $this->storage->setData($message);
    }
}