<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class RatingDTO
{
    public function __construct(
        #[Assert\Range(min: 1, max: 10)]
        public int $score,
    ) {}
}
