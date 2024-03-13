<?php
namespace App\Adapter;

use App\Dto\MessageDto;

interface DataStorageInterface
{
    public function getData(string $id);
    public function setData(MessageDto $message);
}
