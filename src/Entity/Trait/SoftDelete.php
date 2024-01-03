<?php

namespace App\Entity\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait SoftDelete
{
    #[ORM\Column(name: 'deleted_at', type: 'datetime_immutable', nullable: true, options: ['default' => null])]
    private ?DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
