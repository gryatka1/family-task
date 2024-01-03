<?php

namespace App\Entity\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAt
{
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?DateTimeImmutable $createdAt;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
