<?php

namespace App\Model;

class MovieFilterDTO
{
    public function __construct(
        public ?string $title = null,
        public ?int $year = null,
    ) {}
}