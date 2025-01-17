<?php 

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryService
{

    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $entityManager,
    ) {}

    public function setDefaultFields(Category $category)
    {
        $this->setCategorySlug($category);
        $this->setCreatedAt($category);
    }

    private function setCategorySlug(Category $category)
    {
        $slug = $this->slugger->slug($category->getTitle());
        $category->setSlug($slug);
    }

    private function setCreatedAt(Category $category)
    {
        $category->setCreatedAt(new DateTimeImmutable());
    }

    public function getCategoryByTitle(?string $title)
    {
        if(is_null($title))
        {
            return $this->entityManager->getRepository(Category::class)->findAll();
        }
        $queryBuilder = $this->entityManager->createQueryBuilder();
        return $queryBuilder->select('c')->from(Category::class, 'c')
        ->where('LOWER(c.title) LIKE :title')->setParameter('title', '%' . strtolower($title) . '%')
        ->getQuery()->getResult();
    }

}