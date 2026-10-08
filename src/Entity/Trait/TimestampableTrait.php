<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableTrait
{
    #[Groups(['all:read'])]
    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $createdAt = null;

    #[Groups(['all:read'])]
    #[ORM\Column(nullable: true)]
    public ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
