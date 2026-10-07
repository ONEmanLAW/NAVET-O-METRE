<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationDTO
{
    public function __construct(
        #[Assert\Positive]
        public int $page = 1,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public int $limit = 10,
    ) {}
}