<?php

namespace App\Command;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Movie;
use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-movies',
    description: 'Importe les films du fichier movies-db.json en base',
)]
class ImportMoviesCommand
{
    /** @var array<string, Category> */
    private array $categories = [];

    /** @var array<string, Actor> */
    private array $actors = [];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategoryRepository $categoryRepository,
        private ActorRepository $actorRepository,
    ) {}

    public function __invoke(SymfonyStyle $io): int
    {
        foreach ($this->categoryRepository->findAll() as $category) {
            $this->categories[$category->getTitle()] = $category;
        }
        foreach ($this->actorRepository->findAll() as $actor) {
            $this->actors[$actor->getName()] = $actor;
        }

        $file = fopen(__DIR__ . "/movies-db.json", 'r');

        ini_set('memory_limit', '2048M');

        $count = 0;

        while (!feof($file)) {
            $line = fgets($file);
            if (!$line) continue;

            $movieData = json_decode($line, true);

            if ($movieData['type'] !== 'movie') {
                continue;
            }

            $year = $movieData['year'];

            $movie = new Movie();
            $movie->setTitle($movieData['title']);
            $movie->setDescription($movieData['plot'] ?? '');
            $movie->setReleaseDate((int) (is_array($year) ? $year['$numberInt'] : $year));
            $movie->setPoster($movieData['poster'] ?? null);

            foreach ($movieData['genres'] ?? [] as $genre) {
                $movie->addCategory($this->getCategory($genre));
            }

            foreach ($movieData['cast'] ?? [] as $name) {
                $movie->addActor($this->getActor($name));
            }

            $this->entityManager->persist($movie);
            $count++;

            if ($count % 1000 === 0) {
                $this->entityManager->flush();
                $io->writeln("$count films importés...");
            }
        }

        fclose($file);
        $this->entityManager->flush();

        $io->success("$count films importés !");

        return Command::SUCCESS;
    }

    private function getCategory(string $title): Category
    {
        if (!isset($this->categories[$title])) {
            $category = (new Category())->setTitle($title);
            $this->entityManager->persist($category);
            $this->categories[$title] = $category;
        }

        return $this->categories[$title];
    }

    private function getActor(string $name): Actor
    {
        if (!isset($this->actors[$name])) {
            $actor = (new Actor())->setName($name);
            $this->entityManager->persist($actor);
            $this->actors[$name] = $actor;
        }

        return $this->actors[$name];
    }
}
