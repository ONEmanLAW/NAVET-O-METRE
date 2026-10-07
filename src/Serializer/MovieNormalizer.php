<?php

namespace App\Serializer;

use App\Entity\Movie;
use App\Entity\User;
use App\Repository\RatingRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MovieNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private Security $security,
        private RatingRepository $ratingRepository,
    ) {}

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $normalized = $this->normalizer->normalize($data, $format, $context);

        $user = $this->security->getUser();
        $normalized['myRating'] = $user instanceof User
            ? $this->ratingRepository->findOneBy(['user' => $user, 'movie' => $data])?->getScore()
            : null;

        return $normalized;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Movie;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Movie::class => true];
    }
}
