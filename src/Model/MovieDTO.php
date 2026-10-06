<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class MovieDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $description,

        #[Assert\Range(min: 1888, max: 2100)]
        public int $releaseDate,

        /** @var int[] */
        #[Assert\All([new Assert\Type('integer'), new Assert\Positive()])]
        public array $categoryIds = [],
    ) {}
}