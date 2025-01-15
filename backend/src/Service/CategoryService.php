<?php 

namespace App\Service;

use App\Entity\Category;
use DateTimeImmutable;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryService
{

    public function __construct(
        private SluggerInterface $slugger
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
        $category->setCreated_at(new DateTimeImmutable());
    }
}