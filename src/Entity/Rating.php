<?php

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use App\Repository\RatingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_RATING_USER_MOVIE', fields: ['user', 'movie'])]
#[ORM\HasLifecycleCallbacks]
class Rating
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Ignore]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Movie $movie = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $score = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ratedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getRatedAt(): ?\DateTimeImmutable
    {
        return $this->ratedAt;
    }

    public function setRatedAt(\DateTimeImmutable $ratedAt): static
    {
        $this->ratedAt = $ratedAt;

        return $this;
    }
}
