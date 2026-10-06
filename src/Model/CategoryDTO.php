<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title,
    ) {}
}
