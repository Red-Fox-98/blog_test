<?php
namespace App\Filter;

use App\Entity\User;

class BlogFilter
{
    public function __construct(private ?User $user = null)
    {

    }

    private ?string $title = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
