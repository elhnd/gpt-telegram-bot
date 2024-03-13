<?php

namespace App\Dto;

use DateTimeImmutable;

class MessageDto
{
    private ?string $userId = null;

    private ?string $username = null;

    private ?\DateTimeInterface $date = null;

    private ?string $role = null;

    private ?string $content = null;

    public function __construct(
        string $userId,
        string $username,
        string $role,
        string $content
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->role = $role;
        $this->content = $content;
        $this->date = new DateTimeImmutable("now");
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
